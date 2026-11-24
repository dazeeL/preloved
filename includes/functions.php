<?php
// ============ AUTH HELPERS ============
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['user_role'] === 'admin';
}

function isBuyer() {
    return isLoggedIn() && $_SESSION['user_role'] === 'buyer';
}

function redirect($url) {
    header("Location: $url");
    exit();
}

// ============ FORMATTING ============
function formatRupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}

function sanitize($data) {
    return htmlspecialchars(trim(stripslashes($data)), ENT_QUOTES, 'UTF-8');
}

function generateOrderNumber() {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
}

// ============ CART ============
function getCartCount() {
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) return 0;
    return array_sum($_SESSION['cart']);
}

// ============ IMAGE UPLOAD ============
function uploadImage($file) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) return false;
    if ($file['size'] > 5 * 1024 * 1024) return false; // 5MB max

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'product_' . uniqid() . '.' . $ext;
    $destination = UPLOAD_DIR . $filename;

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $filename;
    }
    return false;
}

// ============ FLASH MESSAGES ============
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// ============ TIME ============
function timeAgo($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->y > 0) return $diff->y . ' tahun lalu';
    if ($diff->m > 0) return $diff->m . ' bulan lalu';
    if ($diff->d > 0) return $diff->d . ' hari lalu';
    if ($diff->h > 0) return $diff->h . ' jam lalu';
    if ($diff->i > 0) return $diff->i . ' menit lalu';
    return 'Baru saja';
}

// ============ CONDITION LABEL ============
function conditionLabel($condition) {
    $labels = [
        'baru' => 'Baru',
        'like_new' => 'Like New',
        'baik' => 'Baik',
        'cukup' => 'Cukup'
    ];
    return $labels[$condition] ?? $condition;
}

function conditionClass($condition) {
    $classes = [
        'baru' => 'badge-success',
        'like_new' => 'badge-info',
        'baik' => 'badge-warning',
        'cukup' => 'badge-danger'
    ];
    return $classes[$condition] ?? 'badge-secondary';
}

function statusBadge($status) {
    $badges = [
        'pending' => 'badge-warning',
        'confirmed' => 'badge-info',
        'shipped' => 'badge-primary',
        'completed' => 'badge-success',
        'cancelled' => 'badge-danger',
        'available' => 'badge-success',
        'sold' => 'badge-danger'
    ];
    return $badges[$status] ?? 'badge-secondary';
}

function statusLabel($status) {
    $labels = [
        'pending' => 'Menunggu',
        'confirmed' => 'Dikonfirmasi',
        'shipped' => 'Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        'available' => 'Tersedia',
        'sold' => 'Terjual'
    ];
    return $labels[$status] ?? ucfirst($status);
}

// ============ DUMMY DATA ============
function getDummyCategories() {
    return [
        ['id' => 1, 'name' => 'Elektronik', 'slug' => 'elektronik'],
        ['id' => 2, 'name' => 'Fashion Pria', 'slug' => 'fashion-pria'],
        ['id' => 3, 'name' => 'Fashion Wanita', 'slug' => 'fashion-wanita'],
        ['id' => 4, 'name' => 'Buku', 'slug' => 'buku'],
        ['id' => 5, 'name' => 'Peralatan Rumah', 'slug' => 'peralatan-rumah'],
        ['id' => 6, 'name' => 'Olahraga', 'slug' => 'olahraga'],
    ];
}

function getDummyProducts() {
    return [
        ['id'=>1, 'category_id'=>1, 'category_name'=>'Elektronik', 'name'=>'iPhone 13 Pro 256GB', 'description'=>'iPhone 13 Pro dalam kondisi mulus, baterai health 89%. Fullset dengan box dan charger original. Tidak ada goresan di layar, menggunakan case dan tempered glass sejak awal. Warna Sierra Blue.', 'price'=>8500000, 'condition'=>'like_new', 'image'=>'', 'stock'=>1, 'status'=>'available', 'created_at'=>'2026-11-20 10:00:00'],
        ['id'=>2, 'category_id'=>2, 'category_name'=>'Fashion Pria', 'name'=>'Jaket Kulit Zara Original', 'description'=>'Jaket kulit Zara original size L. Dipakai hanya beberapa kali untuk acara formal. Bahan kulit sintetis premium, sangat lembut dan nyaman. Warna hitam classic.', 'price'=>450000, 'condition'=>'like_new', 'image'=>'', 'stock'=>1, 'status'=>'available', 'created_at'=>'2026-11-19 14:30:00'],
        ['id'=>3, 'category_id'=>3, 'category_name'=>'Fashion Wanita', 'name'=>'Tas Coach Tabby Shoulder Bag', 'description'=>'Tas Coach Tabby authentic, dibeli langsung dari store. Kondisi 95% mulus, ada dustbag dan kartu garansi. Warna Chalk multi. Cocok untuk daily use.', 'price'=>2800000, 'condition'=>'baik', 'image'=>'', 'stock'=>1, 'status'=>'available', 'created_at'=>'2026-11-18 09:15:00'],
        ['id'=>4, 'category_id'=>4, 'category_name'=>'Buku', 'name'=>'Atomic Habits - James Clear (Hardcover)', 'description'=>'Buku Atomic Habits edisi hardcover bahasa Inggris. Kondisi seperti baru, hanya dibaca sekali. Tidak ada coretan atau lipatan halaman.', 'price'=>95000, 'condition'=>'like_new', 'image'=>'', 'stock'=>3, 'status'=>'available', 'created_at'=>'2026-11-17 16:45:00'],
        ['id'=>5, 'category_id'=>5, 'category_name'=>'Peralatan Rumah', 'name'=>'Dyson V8 Absolute Vacuum Cleaner', 'description'=>'Vacuum cleaner Dyson V8 Absolute, masih berfungsi dengan baik. Semua aksesoris lengkap termasuk wall mount. Baterai masih awet sekitar 30 menit pemakaian.', 'price'=>3200000, 'condition'=>'baik', 'image'=>'', 'stock'=>1, 'status'=>'available', 'created_at'=>'2026-11-16 11:20:00'],
        ['id'=>6, 'category_id'=>6, 'category_name'=>'Olahraga', 'name'=>'Sepatu Nike Air Max 270 Original', 'description'=>'Sepatu Nike Air Max 270 original size 42. Dibeli di Nike Store PIK. Sole masih tebal, upper bersih. Cocok untuk casual dan olahraga ringan.', 'price'=>650000, 'condition'=>'baik', 'image'=>'', 'stock'=>1, 'status'=>'available', 'created_at'=>'2026-11-15 08:00:00'],
        ['id'=>7, 'category_id'=>1, 'category_name'=>'Elektronik', 'name'=>'Samsung Galaxy Buds Pro', 'description'=>'Samsung Galaxy Buds Pro warna Phantom Black. ANC berfungsi sempurna, suara jernih. Dilengkapi case charging yang masih mulus. Baterai awet.', 'price'=>750000, 'condition'=>'like_new', 'image'=>'', 'stock'=>1, 'status'=>'available', 'created_at'=>'2026-11-14 13:00:00'],
        ['id'=>8, 'category_id'=>3, 'category_name'=>'Fashion Wanita', 'name'=>'Dress Mango Floral Midi', 'description'=>'Dress Mango motif floral size S. Bahan ringan dan adem, cocok untuk acara semi-formal. Panjang midi. Dipakai 2x saja.', 'price'=>185000, 'condition'=>'like_new', 'image'=>'', 'stock'=>1, 'status'=>'available', 'created_at'=>'2026-11-13 17:30:00'],
    ];
}

function getDummyOrders() {
    return [
        ['id'=>1, 'order_number'=>'ORD-20261120-A1B2C', 'user_id'=>2, 'user_name'=>'Budi Santoso', 'user_email'=>'budi@email.com', 'user_phone'=>'081234567890', 'total_amount'=>8500000, 'status'=>'completed', 'shipping_address'=>'Jl. Sudirman No. 45, Jakarta Selatan', 'payment_method'=>'Transfer Bank BCA', 'created_at'=>'2026-11-20 10:30:00',
         'items'=>[['product_name'=>'iPhone 13 Pro 256GB','quantity'=>1,'price'=>8500000]]],
        ['id'=>2, 'order_number'=>'ORD-20261121-D3E4F', 'user_id'=>3, 'user_name'=>'Siti Rahayu', 'user_email'=>'siti@email.com', 'user_phone'=>'082345678901', 'total_amount'=>545000, 'status'=>'shipped', 'shipping_address'=>'Jl. Gatot Subroto No. 12, Bandung', 'payment_method'=>'Transfer Bank Mandiri', 'created_at'=>'2026-11-21 14:15:00',
         'items'=>[['product_name'=>'Jaket Kulit Zara Original','quantity'=>1,'price'=>450000],['product_name'=>'Atomic Habits','quantity'=>1,'price'=>95000]]],
        ['id'=>3, 'order_number'=>'ORD-20261122-G5H6I', 'user_id'=>4, 'user_name'=>'Ahmad Wijaya', 'user_email'=>'ahmad@email.com', 'user_phone'=>'083456789012', 'total_amount'=>650000, 'status'=>'pending', 'shipping_address'=>'Jl. Diponegoro No. 78, Surabaya', 'payment_method'=>'COD', 'created_at'=>'2026-11-22 09:00:00',
         'items'=>[['product_name'=>'Sepatu Nike Air Max 270','quantity'=>1,'price'=>650000]]],
        ['id'=>4, 'order_number'=>'ORD-20261123-J7K8L', 'user_id'=>5, 'user_name'=>'Dewi Lestari', 'user_email'=>'dewi@email.com', 'user_phone'=>'084567890123', 'total_amount'=>2800000, 'status'=>'confirmed', 'shipping_address'=>'Jl. Ahmad Yani No. 33, Yogyakarta', 'payment_method'=>'Transfer Bank BRI', 'created_at'=>'2026-11-23 16:45:00',
         'items'=>[['product_name'=>'Tas Coach Tabby Shoulder Bag','quantity'=>1,'price'=>2800000]]],
        ['id'=>5, 'order_number'=>'ORD-20261124-M9N0O', 'user_id'=>3, 'user_name'=>'Siti Rahayu', 'user_email'=>'siti@email.com', 'user_phone'=>'082345678901', 'total_amount'=>935000, 'status'=>'pending', 'shipping_address'=>'Jl. Gatot Subroto No. 12, Bandung', 'payment_method'=>'Transfer Bank BCA', 'created_at'=>'2026-11-24 08:20:00',
         'items'=>[['product_name'=>'Samsung Galaxy Buds Pro','quantity'=>1,'price'=>750000],['product_name'=>'Dress Mango Floral Midi','quantity'=>1,'price'=>185000]]],
    ];
}

function getDummyUsers() {
    return [
        ['id'=>1, 'name'=>'Administrator', 'email'=>'admin@preloved.com', 'phone'=>'081200000000', 'role'=>'admin', 'created_at'=>'2026-11-01 00:00:00'],
        ['id'=>2, 'name'=>'Budi Santoso', 'email'=>'budi@email.com', 'phone'=>'081234567890', 'role'=>'buyer', 'created_at'=>'2026-11-10 09:00:00'],
        ['id'=>3, 'name'=>'Siti Rahayu', 'email'=>'siti@email.com', 'phone'=>'082345678901', 'role'=>'buyer', 'created_at'=>'2026-11-12 14:00:00'],
        ['id'=>4, 'name'=>'Ahmad Wijaya', 'email'=>'ahmad@email.com', 'phone'=>'083456789012', 'role'=>'buyer', 'created_at'=>'2026-11-15 11:00:00'],
        ['id'=>5, 'name'=>'Dewi Lestari', 'email'=>'dewi@email.com', 'phone'=>'084567890123', 'role'=>'buyer', 'created_at'=>'2026-11-18 16:00:00'],
    ];
}
