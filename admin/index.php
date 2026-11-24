<?php
$pageTitle = 'Dashboard Admin';
require_once __DIR__ . '/../includes/header.php';

if (!isLoggedIn() || !isAdmin()) {
    setFlash('error', 'Anda harus login sebagai admin.');
    redirect(BASE_URL . '/auth/login.php');
}

if ($conn) {
    $totalProducts = $conn->query("SELECT COUNT(*) as c FROM products")->fetch_assoc()['c'];
    $totalOrders = $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'];
    $pendingOrders = $conn->query("SELECT COUNT(*) as c FROM orders WHERE status='pending'")->fetch_assoc()['c'];
    $totalRevenue = $conn->query("SELECT COALESCE(SUM(total_amount),0) as s FROM orders WHERE status='completed'")->fetch_assoc()['s'];
    $recentOrders = [];
    $res = $conn->query("SELECT o.*, u.name as user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5");
    while ($row = $res->fetch_assoc()) $recentOrders[] = $row;
} else {
    $totalProducts = 8;
    $totalOrders = 5;
    $pendingOrders = 2;
    $totalRevenue = 9500000;
    $recentOrders = getDummyOrders();
}
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1>📊 Dashboard</h1>
            <p class="text-muted">Selamat datang kembali, <?= sanitize($_SESSION['user_name']) ?>!</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/product_form.php" class="btn btn-primary">+ Tambah Produk</a>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card stat-primary">
            <div class="stat-icon">📦</div>
            <div class="stat-value"><?= $totalProducts ?></div>
            <div class="stat-label">Total Produk</div>
        </div>
        <div class="stat-card stat-success">
            <div class="stat-icon">🛒</div>
            <div class="stat-value"><?= $totalOrders ?></div>
            <div class="stat-label">Total Pesanan</div>
        </div>
        <div class="stat-card stat-warning">
            <div class="stat-icon">⏳</div>
            <div class="stat-value"><?= $pendingOrders ?></div>
            <div class="stat-label">Pesanan Pending</div>
        </div>
        <div class="stat-card stat-danger">
            <div class="stat-icon">💰</div>
            <div class="stat-value"><?= formatRupiah($totalRevenue) ?></div>
            <div class="stat-label">Total Pendapatan</div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card">
        <div class="card-header">
            <span>🕐 Pesanan Terbaru</span>
            <a href="<?= BASE_URL ?>/admin/orders.php" class="btn btn-sm btn-outline">Lihat Semua</a>
        </div>
        <div class="card-body">
            <?php if (empty($recentOrders)): ?>
                <div class="empty-state">
                    <span class="empty-icon">📋</span>
                    <h3>Belum ada pesanan</h3>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $order): ?>
                                <tr>
                                    <td><strong><?= sanitize($order['order_number']) ?></strong></td>
                                    <td><?= sanitize($order['user_name']) ?></td>
                                    <td><?= formatRupiah($order['total_amount']) ?></td>
                                    <td><span class="badge <?= statusBadge($order['status']) ?>"><?= statusLabel($order['status']) ?></span></td>
                                    <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/admin/order_detail.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline">Detail</a>
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
