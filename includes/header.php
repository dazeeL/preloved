<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? 'PreLoved Marketplace';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize($pageTitle) ?> - PreLoved</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container navbar-container">
            <a href="<?= BASE_URL ?>/" class="navbar-brand">
                <span class="brand-icon">♻️</span> PreLoved
            </a>

            <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
                <span></span><span></span><span></span>
            </button>

            <ul class="nav-links" id="navLinks">
                <?php if (isLoggedIn() && isAdmin()): ?>
                    <li><a href="<?= BASE_URL ?>/admin/">📊 Dashboard</a></li>
                    <li><a href="<?= BASE_URL ?>/admin/products.php">📦 Produk</a></li>
                    <li><a href="<?= BASE_URL ?>/admin/orders.php">🛒 Pesanan</a></li>
                    <li><a href="<?= BASE_URL ?>/admin/users.php">👥 Pengguna</a></li>
                    <li class="nav-user">
                        <span class="user-greeting">Halo, <?= sanitize($_SESSION['user_name']) ?></span>
                        <a href="<?= BASE_URL ?>/auth/logout.php" class="btn btn-sm btn-danger">Logout</a>
                    </li>
                <?php elseif (isLoggedIn() && isBuyer()): ?>
                    <li><a href="<?= BASE_URL ?>/buyer/">🏠 Home</a></li>
                    <li>
                        <a href="<?= BASE_URL ?>/buyer/cart.php" class="cart-link">
                            🛒 Keranjang
                            <?php $cartCount = getCartCount(); if ($cartCount > 0): ?>
                                <span class="cart-badge"><?= $cartCount ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li><a href="<?= BASE_URL ?>/buyer/orders.php">📋 Pesanan Saya</a></li>
                    <li class="nav-user">
                        <span class="user-greeting">Halo, <?= sanitize($_SESSION['user_name']) ?></span>
                        <a href="<?= BASE_URL ?>/auth/logout.php" class="btn btn-sm btn-danger">Logout</a>
                    </li>
                <?php else: ?>
                    <li><a href="<?= BASE_URL ?>/buyer/">🛍️ Katalog</a></li>
                    <li><a href="<?= BASE_URL ?>/auth/login.php" class="btn btn-sm btn-outline">Masuk</a></li>
                    <li><a href="<?= BASE_URL ?>/auth/register.php" class="btn btn-sm btn-primary">Daftar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main class="main-content">
        <?php $flash = getFlash(); if ($flash): ?>
            <div class="container">
                <div class="alert alert-<?= $flash['type'] ?>" id="flashAlert">
                    <?= sanitize($flash['message']) ?>
                    <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
            </div>
        <?php endif; ?>
