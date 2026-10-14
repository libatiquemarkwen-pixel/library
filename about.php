<?php
require 'includes/config.php';
$page_title = 'About';
include 'includes/header.php';
?>

<section class="hero hero--compact">
    <div class="container">
        <span class="eyebrow">About us</span>
        <h1>About the Library of the PCNL</h1>
        <p class="lead">Part of the Philippine College of Northwestern Luzon, guided by its <em>quality and excellence.</em></p>
    </div>
</section>

<section class="page">
    <div class="container">
        <div class="about-grid">
            <div class="panel panel-pad">
                <img src="assets/img/pcnl-logo.png" alt="PCNL seal" class="about-logo">
                <h2 class="h4">Our purpose</h2>
                <p>The library supports teaching, learning and research at PCNL by giving students and faculty easy access to books and references.</p>
                <p class="mb-0">This portal lets members search the catalog, borrow books online, track their loans and return them, while librarians manage the collection and borrowing records.</p>
            </div>
            <div class="stack">
                <div class="panel feature"><h3>For students</h3><p>Register, search the catalog, borrow available titles and return them from My Books.</p></div>
                <div class="panel feature"><h3>For librarians</h3><p>Add, edit and remove books, and view every borrow record from the dashboard.</p></div>
                <?php if (array_filter($library)): ?>
                <div class="panel feature"><h3>Visit or contact us</h3>
                    <?php foreach (['address' => 'Address', 'hours' => 'Hours', 'email' => 'Email', 'phone' => 'Phone'] as $k => $label): if ($library[$k] !== ''): ?>
                        <p><strong><?= $label ?>:</strong> <?= e($library[$k]) ?></p>
                    <?php endif; endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
