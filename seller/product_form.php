<?php
ob_start();
$pageTitle = 'Produk Toko';
require_once __DIR__ . '/../includes/header.php';
if (!isBuyer()) redirect(BASE_URL . '/auth/login.php');
$store = getUserStore();
if (!$store) redirect(BASE_URL . '/seller/');

$categories = $conn ? [] : getDummyCategories();
if ($conn) {
    $result = $conn->query('SELECT id,name FROM categories ORDER BY name');
    while ($result && $row = $result->fetch_assoc()) $categories[] = $row;
}
$id = (int)($_GET['id'] ?? 0);
$product = ['name'=>'','category_id'=>'','description'=>'','price'=>'','condition'=>'like_new','stock'=>1,'status'=>'available','image'=>''];
if ($id) {
    if ($conn) {
        $sid = (int)$store['id'];
        $result = $conn->query("SELECT * FROM products WHERE id=$id AND store_id=$sid");
        $product = $result ? $result->fetch_assoc() : null;
    } else $product = $_SESSION['demo_seller_products'][$id] ?? null;
    if (!$product) { setFlash('error', 'Produk tidak ditemukan di tokomu.'); redirect(BASE_URL . '/seller/products.php'); }
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product['name'] = trim($_POST['name'] ?? '');
    $product['category_id'] = (int)($_POST['category_id'] ?? 0);
    $product['description'] = trim($_POST['description'] ?? '');
    $product['price'] = (float)($_POST['price'] ?? 0);
    $product['condition'] = $_POST['condition'] ?? 'baik';
    $product['stock'] = max(0, (int)($_POST['stock'] ?? 0));
    $product['status'] = $product['stock'] ? 'available' : 'sold';
    if (mb_strlen($product['name']) < 3 || !$product['category_id'] || $product['price'] <= 0) $error = 'Nama, kategori, dan harga harus diisi dengan benar.';
    if (!in_array($product['condition'], ['baru','like_new','baik','cukup'], true)) $error = 'Kondisi produk tidak valid.';
    if (!$error && !empty($_FILES['image']['name'])) {
        $uploaded = uploadImage($_FILES['image']);
        if (!$uploaded) $error = 'Gagal mengunggah gambar. Gunakan JPG, PNG, WebP, atau GIF di bawah 5 MB.';
        else $product['image'] = $uploaded;
    }
    if (!$error) {
        $name=$product['name']; $categoryId=(int)$product['category_id']; $description=$product['description'];
        $price=(float)$product['price']; $condition=$product['condition']; $stock=(int)$product['stock'];
        $status=$product['status']; $image=$product['image'] ?? '';
        if ($conn) {
            $storeId=(int)$store['id'];
            if ($id) {
                $stmt=$conn->prepare('UPDATE products SET name=?,category_id=?,description=?,price=?,`condition`=?,stock=?,status=?,image=? WHERE id=? AND store_id=?');
            } else {
                $stmt=$conn->prepare('INSERT INTO products (name,category_id,description,price,`condition`,stock,status,image,store_id) VALUES (?,?,?,?,?,?,?,?,?)');
            }
            // mysqli type strings: name(s), category(i), description(s), price(d), condition(s), stock(i), status(s), image(s), ids(i).
            if ($id) $stmt->bind_param('sisdsissii', $name,$categoryId,$description,$price,$condition,$stock,$status,$image,$id,$storeId);
            else $stmt->bind_param('sisdsissi', $name,$categoryId,$description,$price,$condition,$stock,$status,$image,$storeId);
            if (!$stmt->execute()) $error='Produk gagal disimpan. Periksa kembali data dan coba lagi.';
            $stmt->close();
        } else {
            $key=$id ?: (int)($_SESSION['demo_next_product_id'] ?? 100);
            $_SESSION['demo_next_product_id']=$key+1; $product['id']=$key; $product['category_name']='Preloved';
            $product['store_name']=$store['name']; $product['store_id']=$store['id'];
            $_SESSION['demo_seller_products'][$key]=$product;
        }
        if (!$error) { setFlash('success', $id ? 'Produk berhasil diperbarui.' : 'Produk berhasil ditambahkan ke tokomu.'); redirect(BASE_URL . '/seller/products.php'); }
    }
}
?>
<section class="section"><div class="container seller-form-wrap"><div class="page-header"><div><span class="eyebrow">TOKO: <?= sanitize($store['name']) ?></span><h1><?= $id ? 'Edit produk' : 'Produk baru' ?></h1></div><a class="btn btn-outline" href="<?= BASE_URL ?>/seller/products.php">← Kembali</a></div>
<?php if($error): ?><div class="alert alert-error"><?= sanitize($error) ?></div><?php endif; ?>
<form class="card seller-product-form" method="post" enctype="multipart/form-data"><div class="form-row"><div class="form-group"><label>Nama produk</label><input class="form-control" name="name" value="<?= sanitize($product['name']) ?>" required maxlength="180" placeholder="Contoh: Jaket denim vintage"></div><div class="form-group"><label>Kategori</label><select class="form-control" name="category_id" required><option value="">Pilih kategori</option><?php foreach($categories as $category): ?><option value="<?= (int)$category['id'] ?>" <?= (int)$product['category_id']===(int)$category['id']?'selected':'' ?>><?= sanitize($category['name']) ?></option><?php endforeach; ?></select></div></div><div class="form-group"><label>Cerita dan detail produk</label><textarea class="form-control" name="description" rows="5" placeholder="Kondisi, ukuran, kekurangan kecil, atau cerita barang ini..."><?= sanitize($product['description']) ?></textarea><small class="text-muted">Deskripsi jujur membantu pembeli merasa yakin.</small></div><div class="form-row"><div class="form-group"><label>Harga (Rp)</label><input class="form-control" name="price" type="number" min="1" value="<?= (float)$product['price'] ?>" required></div><div class="form-group"><label>Kondisi barang</label><select class="form-control" name="condition"><?php foreach(['like_new'=>'Seperti baru','baru'=>'Baru','baik'=>'Baik','cukup'=>'Cukup'] as $value=>$label): ?><option value="<?= $value ?>" <?= $product['condition']===$value?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select></div><div class="form-group"><label>Jumlah stok</label><input class="form-control" name="stock" type="number" min="0" value="<?= (int)$product['stock'] ?>" required></div></div><div class="form-group"><label>Foto produk</label><input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif"><small class="text-muted">JPG, PNG, WebP, atau GIF · maksimal 5 MB.</small><?php if($product['image']): ?><div class="seller-image-preview"><img src="<?= BASE_URL ?>/uploads/products/<?= sanitize($product['image']) ?>" alt="Foto produk saat ini"></div><?php endif; ?></div><button class="btn btn-primary btn-lg">Simpan produk</button></form></div></section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
