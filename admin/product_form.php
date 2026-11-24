<?php
$pageTitle = 'Form Produk';
require_once __DIR__ . '/../includes/header.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect(BASE_URL . '/auth/login.php');
}

$isEdit = isset($_GET['id']);
$product = ['id'=>'','name'=>'','category_id'=>'','description'=>'','price'=>'','condition'=>'like_new','stock'=>1,'status'=>'available','image'=>''];

if ($conn) {
    $catResult = $conn->query("SELECT * FROM categories ORDER BY name");
    $categories = [];
    while ($row = $catResult->fetch_assoc()) $categories[] = $row;
} else {
    $categories = getDummyCategories();
}

if ($isEdit) {
    $editId = (int)$_GET['id'];
    if ($conn) {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $editId);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    } else {
        $allProducts = getDummyProducts();
        foreach ($allProducts as $p) {
            if ($p['id'] == $editId) { $product = $p; break; }
        }
    }
    if (!$product) {
        setFlash('error', 'Produk tidak ditemukan.');
        redirect(BASE_URL . '/admin/products.php');
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $description = sanitize($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $condition = sanitize($_POST['condition'] ?? 'like_new');
    $stock = (int)($_POST['stock'] ?? 1);
    $status = sanitize($_POST['status'] ?? 'available');

    if (empty($name) || $category_id == 0 || $price <= 0) {
        $error = 'Nama, kategori, dan harga harus diisi dengan benar.';
    } else {
        $imageName = $product['image'] ?? '';
        if (!empty($_FILES['image']['name'])) {
            $uploaded = uploadImage($_FILES['image']);
            if ($uploaded) {
                // Delete old image if editing
                if ($isEdit && !empty($product['image']) && file_exists(UPLOAD_DIR . $product['image'])) {
                    unlink(UPLOAD_DIR . $product['image']);
                }
                $imageName = $uploaded;
            } else {
                $error = 'Gagal upload gambar. Pastikan format JPG/PNG/WebP dan ukuran < 5MB.';
            }
        }

        if (empty($error)) {
            if ($conn) {
                if ($isEdit) {
                    $stmt = $conn->prepare("UPDATE products SET name=?, category_id=?, description=?, price=?, `condition`=?, stock=?, status=?, image=? WHERE id=?");
                    $stmt->bind_param("sisdssssi", $name, $category_id, $description, $price, $condition, $stock, $status, $imageName, $editId);
                } else {
                    $stmt = $conn->prepare("INSERT INTO products (name, category_id, description, price, `condition`, stock, status, image) VALUES (?,?,?,?,?,?,?,?)");
                    $stmt->bind_param("sisdsiss", $name, $category_id, $description, $price, $condition, $stock, $status, $imageName);
                }
                $stmt->execute();
                $stmt->close();
            }
            setFlash('success', $isEdit ? 'Produk berhasil diperbarui!' : 'Produk berhasil ditambahkan!');
            redirect(BASE_URL . '/admin/products.php');
        }
    }

    // Keep form data
    $product = compact('name','category_id','description','price','condition','stock','status') + ['image'=>$product['image']??'','id'=>$product['id']??''];
}
?>

<div class="container">
    <div class="page-header">
        <h1><?= $isEdit ? '✏️ Edit Produk' : '➕ Tambah Produk Baru' ?></h1>
        <a href="<?= BASE_URL ?>/admin/products.php" class="btn btn-outline">← Kembali</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= sanitize($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nama Produk *</label>
                        <input type="text" id="name" name="name" class="form-control"
                               value="<?= sanitize($product['name']) ?>" placeholder="Contoh: iPhone 13 Pro" required>
                    </div>
                    <div class="form-group">
                        <label for="category_id">Kategori *</label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                    <?= sanitize($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" class="form-control"
                              placeholder="Deskripsikan kondisi dan detail produk..."><?= sanitize($product['description']) ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Harga (Rp) *</label>
                        <input type="number" id="price" name="price" class="form-control"
                               value="<?= $product['price'] ?>" placeholder="Contoh: 500000" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="condition">Kondisi</label>
                        <select id="condition" name="condition" class="form-control">
                            <option value="baru" <?= $product['condition'] == 'baru' ? 'selected' : '' ?>>Baru</option>
                            <option value="like_new" <?= $product['condition'] == 'like_new' ? 'selected' : '' ?>>Like New</option>
                            <option value="baik" <?= $product['condition'] == 'baik' ? 'selected' : '' ?>>Baik</option>
                            <option value="cukup" <?= $product['condition'] == 'cukup' ? 'selected' : '' ?>>Cukup</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="stock">Stok</label>
                        <input type="number" id="stock" name="stock" class="form-control"
                               value="<?= $product['stock'] ?>" min="0">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="available" <?= $product['status'] == 'available' ? 'selected' : '' ?>>Tersedia</option>
                            <option value="sold" <?= $product['status'] == 'sold' ? 'selected' : '' ?>>Terjual</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Gambar Produk</label>
                    <div class="image-upload">
                        <input type="file" name="image" id="imageInput" accept="image/*">
                        <span class="upload-icon">📁</span>
                        <span class="upload-text">Klik atau drag gambar ke sini (JPG, PNG, WebP — max 5MB)</span>
                        <img id="imagePreview" class="image-preview" src="" alt="Preview">
                    </div>
                    <?php if ($isEdit && !empty($product['image'])): ?>
                        <div class="mt-2">
                            <p class="text-muted">Gambar saat ini:</p>
                            <img src="<?= BASE_URL ?>/uploads/products/<?= sanitize($product['image']) ?>"
                                 style="max-width:150px;border-radius:8px;">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="btn-group mt-3">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <?= $isEdit ? '💾 Simpan Perubahan' : '➕ Tambah Produk' ?>
                    </button>
                    <a href="<?= BASE_URL ?>/admin/products.php" class="btn btn-secondary btn-lg">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
