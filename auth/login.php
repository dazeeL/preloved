<?php
$pageTitle = 'Login';
require_once __DIR__ . '/../includes/header.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect(isAdmin() ? BASE_URL . '/admin/' : BASE_URL . '/buyer/');
}

$error = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi.';
    } else {
        if ($conn) {
            // Database login
            $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                setFlash('success', 'Selamat datang, ' . $user['name'] . '!');
                redirect($user['role'] === 'admin' ? BASE_URL . '/admin/' : BASE_URL . '/buyer/');
            } else {
                $error = 'Email atau password salah.';
            }
            $stmt->close();
        } else {
            // Demo mode: allow demo logins
            if ($email === 'admin@preloved.com' && $password === 'admin123') {
                $_SESSION['user_id'] = 1;
                $_SESSION['user_name'] = 'Administrator';
                $_SESSION['user_email'] = 'admin@preloved.com';
                $_SESSION['user_role'] = 'admin';
                setFlash('success', 'Selamat datang, Administrator! (Mode Demo)');
                redirect(BASE_URL . '/admin/');
            } elseif ($email === 'buyer@preloved.com' && $password === 'buyer123') {
                $_SESSION['user_id'] = 2;
                $_SESSION['user_name'] = 'Budi Santoso';
                $_SESSION['user_email'] = 'buyer@preloved.com';
                $_SESSION['user_role'] = 'buyer';
                setFlash('success', 'Selamat datang, Budi! (Mode Demo)');
                redirect(BASE_URL . '/buyer/');
            } else {
                $error = 'Email atau password salah. (Demo: admin@preloved.com / admin123 atau buyer@preloved.com / buyer123)';
            }
        }
    }
}
?>

<div class="auth-container">
    <div class="auth-card">
        <h2>👋 Selamat Datang</h2>
        <p class="auth-subtitle">Masuk ke akun PreLoved Anda</p>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <?= sanitize($error) ?>
                <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
            </div>
        <?php endif; ?>

        <?php if (!$conn): ?>
            <div class="alert alert-warning">
                ⚠️ Database belum terhubung. Gunakan akun demo untuk mencoba:
                <br><strong>Admin:</strong> admin@preloved.com / admin123
                <br><strong>Pembeli:</strong> buyer@preloved.com / buyer123
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control"
                       placeholder="Masukkan email Anda" value="<?= sanitize($email ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control"
                       placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">Masuk</button>
        </form>

        <p class="auth-footer">
            Belum punya akun? <a href="<?= BASE_URL ?>/auth/register.php">Daftar sekarang</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
