<?php
$pageTitle = 'Kelola Pesanan';
require_once __DIR__ . '/../includes/header.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect(BASE_URL . '/auth/login.php');
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
    $orderId = (int)$_POST['order_id'];
    $newStatus = sanitize($_POST['new_status']);
    if ($conn) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $orderId);
        $stmt->execute();
        $stmt->close();
    }
    setFlash('success', 'Status pesanan berhasil diperbarui!');
    redirect(BASE_URL . '/admin/orders.php');
}

$filterStatus = $_GET['status'] ?? '';

if ($conn) {
    $sql = "SELECT o.*, u.name as user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id";
    if ($filterStatus) $sql .= " WHERE o.status = '" . $conn->real_escape_string($filterStatus) . "'";
    $sql .= " ORDER BY o.created_at DESC";
    $result = $conn->query($sql);
    $orders = [];
    while ($row = $result->fetch_assoc()) $orders[] = $row;
} else {
    $orders = getDummyOrders();
    if ($filterStatus) {
        $orders = array_filter($orders, fn($o) => $o['status'] === $filterStatus);
    }
}

$statuses = ['pending','confirmed','shipped','completed','cancelled'];
?>

<div class="container">
    <div class="page-header">
        <h1>🛒 Kelola Pesanan</h1>
        <span class="text-muted"><?= count($orders) ?> pesanan</span>
    </div>

    <div class="filter-bar">
        <a href="<?= BASE_URL ?>/admin/orders.php" class="filter-btn <?= empty($filterStatus) ? 'active' : '' ?>">Semua</a>
        <?php foreach ($statuses as $s): ?>
            <a href="<?= BASE_URL ?>/admin/orders.php?status=<?= $s ?>"
               class="filter-btn <?= $filterStatus === $s ? 'active' : '' ?>">
                <?= statusLabel($s) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($orders)): ?>
                <div class="empty-state">
                    <span class="empty-icon">📋</span>
                    <h3>Tidak ada pesanan</h3>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Pembayaran</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><strong><?= sanitize($order['order_number']) ?></strong></td>
                                    <td><?= sanitize($order['user_name']) ?></td>
                                    <td><?= formatRupiah($order['total_amount']) ?></td>
                                    <td><?= sanitize($order['payment_method']) ?></td>
                                    <td><span class="badge <?= statusBadge($order['status']) ?>"><?= statusLabel($order['status']) ?></span></td>
                                    <td><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="<?= BASE_URL ?>/admin/order_detail.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline">Detail</a>
                                            <?php if ($order['status'] !== 'completed' && $order['status'] !== 'cancelled'): ?>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                    <select name="new_status" onchange="this.form.submit()" class="form-control" style="padding:4px 8px;width:auto;font-size:0.8rem;">
                                                        <option value="">Ubah →</option>
                                                        <?php foreach ($statuses as $s): ?>
                                                            <?php if ($s !== $order['status']): ?>
                                                                <option value="<?= $s ?>"><?= statusLabel($s) ?></option>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </form>
                                            <?php endif; ?>
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
