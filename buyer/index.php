<?php
ob_start();
$pageTitle = 'Katalog Produk';
require_once __DIR__ . '/../includes/header.php';
$categories = $conn ? [] : getDummyCategories();
$products = [];
$search = trim($_GET['search'] ?? '');
$categoryId = (int)($_GET['category'] ?? 0);
$sort = $_GET['sort'] ?? 'newest';
if ($conn) {
    $r = $conn->query("SELECT id,name FROM categories ORDER BY name");
    while ($r && $row = $r->fetch_assoc()) $categories[] = $row;
    $order = $sort === 'price_asc' ? 'p.price ASC' : ($sort === 'price_desc' ? 'p.price DESC' : 'p.created_at DESC');
    $sql = "SELECT p.*, c.name category_name, s.name store_name FROM products p LEFT JOIN categories c ON c.id=p.category_id LEFT JOIN stores s ON s.id=p.store_id WHERE p.status='available' AND p.stock>0";
    if ($search !== '') { $like = '%' . $conn->real_escape_string($search) . '%'; $sql .= " AND (p.name LIKE '$like' OR p.description LIKE '$like')"; }
    if ($categoryId) $sql .= ' AND p.category_id=' . $categoryId;
    $r = $conn->query($sql . " ORDER BY $order");
    while ($r && $row = $r->fetch_assoc()) $products[] = $row;
} else {
    $products = array_merge(getDummyProducts(), array_values($_SESSION['demo_seller_products'] ?? []));
    $products = array_filter($products, function($p) use ($search,$categoryId) { return ($p['status']??'available')==='available' && (int)$p['stock']>0 && (!$categoryId || $p['category_id']==$categoryId) && (!$search || stripos($p['name'].' '.$p['description'],$search)!==false); });
    if ($sort === 'price_asc') usort($products, fn($a,$b)=>$a['price']<=>$b['price']);
    if ($sort === 'price_desc') usort($products, fn($a,$b)=>$b['price']<=>$a['price']);
}
?>
<section class="section"><div class="container">
 <div class="page-header"><div><h1>Temukan barang favoritmu</h1><p class="text-muted">Pilihan preloved berkualitas, siap punya cerita baru.</p></div></div>
 <form class="catalog-toolbar" method="get"><input class="form-control" type="search" name="search" value="<?= sanitize($search) ?>" placeholder="Cari nama atau deskripsi produk"><select class="form-control" name="sort"><option value="newest" <?= $sort==='newest'?'selected':'' ?>>Terbaru</option><option value="price_asc" <?= $sort==='price_asc'?'selected':'' ?>>Harga terendah</option><option value="price_desc" <?= $sort==='price_desc'?'selected':'' ?>>Harga tertinggi</option></select><button class="btn btn-primary">Cari</button></form>
 <div class="catalog-layout"><aside class="catalog-sidebar"><h3>Kategori</h3><a class="sidebar-link <?= !$categoryId?'active':'' ?>" href="<?= BASE_URL ?>/buyer/">Semua produk</a><?php foreach($categories as $c): ?><a class="sidebar-link <?= $categoryId==(int)$c['id']?'active':'' ?>" href="?category=<?= (int)$c['id'] ?>"><?= sanitize($c['name']) ?></a><?php endforeach; ?></aside>
 <div><?php if($search): ?><p class="text-muted">Hasil pencarian untuk “<?= sanitize($search) ?>” · <?= count($products) ?> produk</p><?php endif; ?><?php if(!$products): ?><div class="empty-state"><span class="empty-icon">📦</span><h3>Produk belum ditemukan</h3><p>Coba kata kunci atau kategori lain.</p><a class="btn btn-outline" href="<?= BASE_URL ?>/buyer/">Reset filter</a></div><?php else: ?><div class="product-grid"><?php foreach($products as $p): ?><a class="product-card" href="<?= BASE_URL ?>/buyer/product_detail.php?id=<?= (int)$p['id'] ?>"><div class="product-image"><?php if($p['image']): ?><img src="<?= BASE_URL ?>/uploads/products/<?= sanitize($p['image']) ?>" alt="<?= sanitize($p['name']) ?>"><?php else: ?><span>♻️</span><?php endif; ?></div><div class="product-info"><span class="product-category"><?= sanitize($p['category_name'] ?? 'Preloved') ?></span><h4 class="product-name"><?= sanitize($p['name']) ?></h4><?php if(!empty($p['store_name'])): ?><span class="store-byline">🏪 <?= sanitize($p['store_name']) ?></span><?php endif; ?><span class="badge <?= conditionClass($p['condition']) ?>"><?= conditionLabel($p['condition']) ?></span><div class="product-price"><?= formatRupiah($p['price']) ?></div></div></a><?php endforeach; ?></div><?php endif; ?></div></div>
</div></section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
