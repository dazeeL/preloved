<?php
$pageTitle = 'Detail Pesanan';
require_once __DIR__ . '/../includes/header.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect(BASE_URL . '/auth/login.php');
}

$orderId = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_status'])) {
    $newStatus = sanitize($_POST['new_status']);
    if ($conn) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $orderId);
        $stmt->execute();
        $stmt->close();
    }
    setFlash('success', 'Status pesanan berhasil diperbarui!');
    redirect(BASE_URL . '/admin/order_detail.php?id=' . $orderId);
}

if ($conn) {
    $stmt = $conn->prepare("SELECT o.*, u.name as user_name, u.email as user_email, u.phone as user_phone FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $items = [];
    if ($order) {
        $stmt2 = $conn->prepare("SELECT oi.*, p.name as product_name, p.image as product_image FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
        $stmt2->bind_param("i", $orderId);
        $stmt2->execute();
        $res = $stmt2->get_result();
        while ($row = $res->fetch_assoc()) $items[] = $row;
        $stmt2->close();
    }
} else {
    $allOrders = getDummyOrders();
    $order = null;
    foreach ($allOrders as $o) {
        if ($o['id'] == $orderId) { $order = $o; break; }
    }
    $items = $order['items'] ?? [];
}

if (!$order) {
    setFlash('error', 'Pesanan tidak ditemukan.');
    redirect(BASE_URL . '/admin/orders.php');
}

$statuses = ['pending','confirmed','shipped','completed','cancelled'];
?>

<div class="container">
    <div class="page-header">
        <h1>📋 Detail Pesanan</h1>
        <a href="<?= BASE_URL ?>/admin/orders.php" class="btn btn-outline">← Kembali</a>
    </div>

    <div class="row">
        <div class="col-two-thirds">
            <!-- Order Info -->
            <div class="card mb-3">
                <div class="card-header">
                    <span>Pesanan <?= sanitize($order['order_number']) ?></span>
                    <span class="badge <?= statusBadge($order['status']) ?>"><?= statusLabel($order['status']) ?></span>
                </div>
                <div class="card-body">
                    <p><strong>Tanggal:</strong> <?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
                    <p><strong>Metode Pembayaran:</strong> <?= sanitize($order['payment_method']) ?></p>

                    <h4 class="mt-3 mb-2">Item Pesanan</h4>
                    <div class="table-wrapper">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-center gap-1">
                                                <div class="table-img"><?= !empty($item['product_image']) ? '<img src="'.BASE_URL.'/uploads/products/'.sanitize($item['product_image']).'">' : '📷' ?></div>
                                                <strong><?= sanitize($item['product_name']) ?></strong>
                                            </div>
                                        </td>
                                        <td><?= formatRupiah($item['price']) ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td><strong><?= formatRupiah($item['price'] * $item['quantity']) ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-right mt-2">
                        <span class="product-detail-price">Total: <?= formatRupiah($order['total_amount']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-third">
            <!-- Customer Info -->
            <div class="card mb-3">
                <div class="card-header">👤 Info Pelanggan</div>
                <div class="card-body">
                    <p><strong>Nama:</strong> <?= sanitize($order['user_name']) ?></p>
                    <p><strong>Email:</strong> <?= sanitize($order['user_email'] ?? '-') ?></p>
                    <p><strong>Telepon:</strong> <?= sanitize($order['user_phone'] ?? '-') ?></p>
                    <p><strong>Alamat Pengiriman:</strong></p>
                    <p class="text-muted"><?= nl2br(sanitize($order['shipping_address'])) ?></p>
                </div>
            </div>

            <!-- Update Status -->
            <?php if ($order['status'] !== 'completed' && $order['status'] !== 'cancelled'): ?>
                <div class="card">
                    <div class="card-header">🔄 Update Status</div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <select name="new_status" class="form-control">
                                    <?php foreach ($statuses as $s): ?>
                                        <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>><?= statusLabel($s) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Update Status</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
