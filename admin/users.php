<?php
$pageTitle = 'Kelola Pengguna';
require_once __DIR__ . '/../includes/header.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect(BASE_URL . '/auth/login.php');
}

$filterRole = $_GET['role'] ?? '';

if ($conn) {
    $sql = "SELECT * FROM users";
    if ($filterRole) $sql .= " WHERE role = '" . $conn->real_escape_string($filterRole) . "'";
    $sql .= " ORDER BY created_at DESC";
    $result = $conn->query($sql);
    $users = [];
    while ($row = $result->fetch_assoc()) $users[] = $row;
} else {
    $users = getDummyUsers();
    if ($filterRole) {
        $users = array_filter($users, fn($u) => $u['role'] === $filterRole);
    }
}
?>

<div class="container">
    <div class="page-header">
        <h1>👥 Kelola Pengguna</h1>
        <span class="text-muted"><?= count($users) ?> pengguna terdaftar</span>
    </div>

    <div class="filter-bar">
        <a href="<?= BASE_URL ?>/admin/users.php" class="filter-btn <?= empty($filterRole) ? 'active' : '' ?>">Semua</a>
        <a href="<?= BASE_URL ?>/admin/users.php?role=admin" class="filter-btn <?= $filterRole === 'admin' ? 'active' : '' ?>">Admin</a>
        <a href="<?= BASE_URL ?>/admin/users.php?role=buyer" class="filter-btn <?= $filterRole === 'buyer' ? 'active' : '' ?>">Pembeli</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Role</th>
                            <th>Tanggal Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><strong><?= sanitize($user['name']) ?></strong></td>
                                <td><?= sanitize($user['email']) ?></td>
                                <td><?= sanitize($user['phone'] ?? '-') ?></td>
                                <td>
                                    <span class="badge <?= $user['role'] === 'admin' ? 'badge-primary' : 'badge-success' ?>">
                                        <?= ucfirst($user['role']) ?>
                                    </span>
                                </td>
                                <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
