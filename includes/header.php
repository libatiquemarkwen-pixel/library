<?php
$current = basename($_SERVER['PHP_SELF']);
$page_title = isset($page_title) ? $page_title . ' | Library of the PCNL' : 'Library of the PCNL';
if (!function_exists('nav_active')) {
    function nav_active($files, $current) {
        return in_array($current, (array) $files) ? ' active' : '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($page_title) ?></title>
    <link rel="icon" type="image/png" href="assets/img/pcnl-logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<header class="site-header">
    <div class="topbar">
        <div class="container">
            <span><strong>Official Library Portal</strong> &middot; Philippine College of Northwestern Luzon</span>
            <span>
                <?php if (isset($_SESSION['user_id'])): ?>
                    Signed in as <strong><?= e($_SESSION['username']) ?></strong> (<?= $_SESSION['role'] == 'admin' ? 'Librarian' : 'Student' ?>)
                <?php else: ?>
                    Welcome, guest
                <?php endif; ?>
            </span>
        </div>
    </div>
    <nav class="navbar navbar-expand-md main-nav">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/img/pcnl-logo.png" alt="PCNL seal" class="brand-logo">
                <span class="brand-text">
                    <strong>Library of the PCNL</strong>
                    <small>Philippine College of Northwestern Luzon</small>
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto align-items-md-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link<?= nav_active('dashboard.php', $current) ?>" href="dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link<?= nav_active(['books.php', 'book_form.php'], $current) ?>" href="books.php">Catalog</a></li>
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <li class="nav-item"><a class="nav-link<?= nav_active('records.php', $current) ?>" href="records.php">Borrow Records</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link<?= nav_active('my_books.php', $current) ?>" href="my_books.php">My Books</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link<?= nav_active('about.php', $current) ?>" href="about.php">About</a></li>
                        <li class="nav-item"><a class="nav-link nav-cta" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link<?= nav_active('index.php', $current) ?>" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link<?= nav_active(['books.php'], $current) ?>" href="books.php">Catalog</a></li>
                        <li class="nav-item"><a class="nav-link<?= nav_active('about.php', $current) ?>" href="about.php">About</a></li>
                        <li class="nav-item"><a class="nav-link<?= nav_active('login.php', $current) ?>" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link nav-cta" href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
<main class="site-main">
