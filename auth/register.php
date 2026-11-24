<?php
$pageTitle = 'Daftar Akun';
require_once __DIR__ . '/../includes/header.php';

if (isLoggedIn()) {
    redirect(isAdmin() ? BASE_URL . '/admin/' : BASE_URL . '/buyer/');
}

$error = '';
$success = '';
$old = ['name'=>'','email'=>'','phone'=>'','address'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['name'] = sanitize($_POST['name'] ?? '');
    $old['email'] = sanitize($_POST['email'] ?? '');
    $old['phone'] = sanitize($_POST['phone'] ?? '');
    $old['address'] = sanitize($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($old['name']) || empty($old['email']) || empty($password)) {
        $error = 'Nama, email, dan password harus diisi.';
    } elseif ($password !== $confirm) {
        $error = 'Password dan konfirmasi password tidak cocok.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        if ($conn) {
            // Check email uniqueness
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $old['email']);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $error = 'Email sudah terdaftar. Silakan gunakan email lain.';
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt2 = $conn->prepare("INSERT INTO users (name, email, phone, address, password, role) VALUES (?, ?, ?, ?, ?, 'buyer')");
                $stmt2->bind_param("sssss", $old['name'], $old['email'], $old['phone'], $old['address'], $hashed);
                if ($stmt2->execute()) {
                    $_SESSION['user_id'] = $conn->insert_id;
                    $_SESSION['user_name'] = $old['name'];
                    $_SESSION['user_email'] = $old['email'];
                    $_SESSION['user_role'] = 'buyer';
                    setFlash('success', 'Registrasi berhasil! Selamat datang, ' . $old['name'] . '!');
                    redirect(BASE_URL . '/buyer/');
                } else {
                    $error = 'Terjadi kesalahan. Silakan coba lagi.';
                }
                $stmt2->close();
            }
            $stmt->close();
        } else {
            // Demo mode
            $_SESSION['user_id'] = 99;
            $_SESSION['user_name'] = $old['name'];
            $_SESSION['user_email'] = $old['email'];
            $_SESSION['user_role'] = 'buyer';
            setFlash('success', 'Registrasi berhasil! (Mode Demo)');
            redirect(BASE_URL . '/buyer/');
        }
    }
}
?>

<div class="auth-container">
    <div class="auth-card">
        <h2>🚀 Buat Akun</h2>
        <p class="auth-subtitle">Daftar untuk mulai belanja barang preloved</p>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <?= sanitize($error) ?>
                <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Nama Lengkap *</label>
                <input type="text" id="name" name="name" class="form-control"
                       placeholder="Masukkan nama lengkap" value="<?= $old['name'] ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-control"
                       placeholder="Masukkan email" value="<?= $old['email'] ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">No. Telepon</label>
                <input type="text" id="phone" name="phone" class="form-control"
                       placeholder="Contoh: 081234567890" value="<?= $old['phone'] ?>">
            </div>

            <div class="form-group">
                <label for="address">Alamat</label>
                <textarea id="address" name="address" class="form-control"
                          placeholder="Masukkan alamat lengkap"><?= $old['address'] ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="Min. 6 karakter" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                           placeholder="Ulangi password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">Daftar</button>
        </form>

        <p class="auth-footer">
            Sudah punya akun? <a href="<?= BASE_URL ?>/auth/login.php">Masuk di sini</a>
        </p>
    </div>
</div>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const pw = document.getElementById('password').value;
    const cpw = document.getElementById('confirm_password').value;
    if (pw !== cpw) {
        e.preventDefault();
        alert('Password dan konfirmasi password tidak cocok!');
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
