<?php
$pageTitle = 'Kelola Produk';
require_once __DIR__ . '/../includes/header.php';

if (!isLoggedIn() || !isAdmin()) {
    setFlash('error', 'Akses ditolak.');
    redirect(BASE_URL . '/auth/login.php');
}

// Handle delete
if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    if ($conn) {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $deleteId);
        $stmt->execute();
        $stmt->close();
    }
    setFlash('success', 'Produk berhasil dihapus.');
    redirect(BASE_URL . '/admin/products.php');
}

// Get products
if ($conn) {
    $sql = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id";
    $where = [];
    if (!empty($_GET['category'])) {
        $where[] = "p.category_id = " . (int)$_GET['category'];
    }
    if (!empty($_GET['search'])) {
        $search = $conn->real_escape_string($_GET['search']);
        $where[] = "p.name LIKE '%$search%'";
    }
    if ($where) $sql .= " WHERE " . implode(' AND ', $where);
    $sql .= " ORDER BY p.created_at DESC";
    $result = $conn->query($sql);
    $products = [];
    while ($row = $result->fetch_assoc()) $products[] = $row;
    $catResult = $conn->query("SELECT * FROM categories ORDER BY name");
    $categories = [];
    while ($row = $catResult->fetch_assoc()) $categories[] = $row;
} else {
    $products = getDummyProducts();
    $categories = getDummyCategories();
    // Apply filters on dummy
    if (!empty($_GET['category'])) {
        $catId = (int)$_GET['category'];
        $products = array_filter($products, fn($p) => $p['category_id'] == $catId);
    }
    if (!empty($_GET['search'])) {
        $s = strtolower($_GET['search']);
        $products = array_filter($products, fn($p) => str_contains(strtolower($p['name']), $s));
    }
}
?>

<div class="container">
    <div class="page-header">
        <h1>📦 Kelola Produk</h1>
        <a href="<?= BASE_URL ?>/admin/product_form.php" class="btn btn-primary">+ Tambah Produk Baru</a>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <a href="<?= BASE_URL ?>/admin/products.php" class="filter-btn <?= empty($_GET['category']) ? 'active' : '' ?>">Semua</a>
        <?php foreach ($categories as $cat): ?>
            <a href="<?= BASE_URL ?>/admin/products.php?category=<?= $cat['id'] ?>"
               class="filter-btn <?= ($_GET['category'] ?? '') == $cat['id'] ? 'active' : '' ?>">
                <?= sanitize($cat['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-body">
            <?php if (empty($products)): ?>
                <div class="empty-state">
                    <span class="empty-icon">📦</span>
                    <h3>Tidak ada produk</h3>
                    <p>Belum ada produk yang sesuai filter.</p>
                    <a href="<?= BASE_URL ?>/admin/product_form.php" class="btn btn-primary">+ Tambah Produk</a>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Kondisi</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $p): ?>
                                <tr>
                                    <td><?= $p['id'] ?></td>
                                    <td>
                                        <?php if (!empty($p['image'])): ?>
                                            <div class="table-img">
                                                <img src="<?= BASE_URL ?>/uploads/products/<?= sanitize($p['image']) ?>" alt="">
                                            </div>
                                        <?php else: ?>
                                            <div class="table-img">📷</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?= sanitize($p['name']) ?></strong></td>
                                    <td><?= sanitize($p['category_name']) ?></td>
                                    <td><?= formatRupiah($p['price']) ?></td>
                                    <td><span class="badge <?= conditionClass($p['condition']) ?>"><?= conditionLabel($p['condition']) ?></span></td>
                                    <td><?= $p['stock'] ?></td>
                                    <td><span class="badge <?= statusBadge($p['status']) ?>"><?= statusLabel($p['status']) ?></span></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= BASE_URL ?>/admin/product_form.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline">✏️ Edit</a>
                                            <a href="<?= BASE_URL ?>/admin/products.php?delete=<?= $p['id'] ?>" class="btn btn-sm btn-danger btn-delete">🗑️ Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
