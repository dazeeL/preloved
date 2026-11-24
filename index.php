<?php
$pageTitle = 'Beranda';
require_once __DIR__ . '/includes/header.php';

// Get products
if ($conn) {
    $result = $conn->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 'available' AND p.stock > 0 ORDER BY p.created_at DESC LIMIT 8");
    $products = [];
    while ($row = $result->fetch_assoc()) $products[] = $row;

    $catResult = $conn->query("SELECT c.*, COUNT(p.id) as product_count FROM categories c LEFT JOIN products p ON c.id = p.category_id AND p.status='available' GROUP BY c.id");
    $categories = [];
    while ($row = $catResult->fetch_assoc()) $categories[] = $row;
} else {
    $products = getDummyProducts();
    $categories = getDummyCategories();
    // Add counts
    foreach ($categories as &$cat) {
        $cat['product_count'] = count(array_filter($products, fn($p) => $p['category_id'] == $cat['id']));
    }
}

$catIcons = ['💻','👔','👗','📚','🏠','⚽'];
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Temukan Barang Preloved Berkualitas</h1>
        <p>Belanja barang bekas berkualitas dengan harga terjangkau. Hemat uang, selamatkan bumi.</p>
        <a href="<?= BASE_URL ?>/buyer/" class="btn btn-lg" style="background:white;color:var(--primary);">🛍️ Jelajahi Produk</a>
    </div>
</section>

<!-- Search Section -->
<section class="search-section">
    <div class="container">
        <form action="<?= BASE_URL ?>/buyer/" method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Cari barang preloved...">
            <button type="submit">🔍 Cari</button>
        </form>
    </div>
</section>

<!-- Categories Section -->
<section class="section">
    <div class="container">
        <h2 class="section-title">📂 Kategori</h2>
        <div class="category-grid">
            <?php foreach ($categories as $i => $cat): ?>
                <a href="<?= BASE_URL ?>/buyer/?category=<?= $cat['id'] ?>" class="category-card">
                    <span class="cat-icon"><?= $catIcons[$i] ?? '📦' ?></span>
                    <div class="cat-name"><?= sanitize($cat['name']) ?></div>
                    <div class="cat-count"><?= $cat['product_count'] ?> produk</div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="section" style="background: var(--white);">
    <div class="container">
        <h2 class="section-title">🆕 Produk Terbaru</h2>
        <p class="section-subtitle">Barang preloved berkualitas yang baru ditambahkan</p>

        <?php if (empty($products)): ?>
            <div class="empty-state">
                <span class="empty-icon">📦</span>
                <h3>Belum ada produk</h3>
                <p>Produk akan segera tersedia.</p>
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <a href="<?= BASE_URL ?>/buyer/product_detail.php?id=<?= $product['id'] ?>" class="product-card" style="text-decoration:none;color:inherit;">
                        <?php if (!empty($product['image'])): ?>
                            <div class="product-image">
                                <img src="<?= BASE_URL ?>/uploads/products/<?= sanitize($product['image']) ?>" alt="<?= sanitize($product['name']) ?>">
                            </div>
                        <?php else: ?>
                            <div class="product-image-placeholder">📷</div>
                        <?php endif; ?>
                        <div class="product-info">
                            <span class="product-category"><?= sanitize($product['category_name']) ?></span>
                            <h4 class="product-name"><?= sanitize($product['name']) ?></h4>
                            <div class="product-meta">
                                <span class="badge <?= conditionClass($product['condition']) ?>"><?= conditionLabel($product['condition']) ?></span>
                            </div>
                            <div class="product-price"><?= formatRupiah($product['price']) ?></div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-4">
                <a href="<?= BASE_URL ?>/buyer/" class="btn btn-primary btn-lg">Lihat Semua Produk →</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
