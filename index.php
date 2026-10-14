<?php
require 'includes/config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$titles  = (int) $db->query('SELECT COUNT(*) FROM books')->fetchColumn();
$copies  = (int) $db->query('SELECT COALESCE(SUM(copies), 0) FROM books')->fetchColumn();
$members = (int) $db->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$cats    = $db->query('SELECT category, COUNT(*) n FROM books GROUP BY category ORDER BY n DESC, category LIMIT 8')->fetchAll(PDO::FETCH_ASSOC);
$latest  = $db->query('SELECT * FROM books ORDER BY id DESC LIMIT 4')->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Welcome';
include 'includes/header.php';
?>

<section class="hero hero--home">
    <div class="container">
        <span class="eyebrow">Philippine College of Northwestern Luzon</span>
        <h1>Welcome to the Library of the PCNL</h1>
        <p class="lead">Search our catalog, borrow books online and keep track of your loans, all in one place.</p>
        <form action="books.php" method="get" class="hero-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" name="q" placeholder="Search by title, author or subject..." aria-label="Search the catalog">
            <button type="submit" class="btn btn-gold">Search</button>
        </form>
        <div class="hero-actions">
            <a href="login.php" class="btn btn-outline-light">Login</a>
            <a href="register.php" class="btn btn-outline-light">Create an account</a>
        </div>
    </div>
</section>

<div class="band">
    <div class="container band-grid">
        <div><strong><?= $titles ?></strong><span>Titles</span></div>
        <div><strong><?= $copies ?></strong><span>Copies on the shelf</span></div>
        <div><strong><?= $members ?></strong><span>Student members</span></div>
    </div>
</div>

<?php if ($cats): ?>
<section class="page pb-0">
    <div class="container">
        <div class="section-title">
            <span class="eyebrow">Browse</span>
            <h2>Explore by subject</h2>
        </div>
        <div class="chips chips--center">
            <?php foreach ($cats as $c): ?>
                <a class="chip" href="books.php?q=<?= urlencode($c['category']) ?>"><?= e($c['category']) ?> <span class="chip-n"><?= (int) $c['n'] ?></span></a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($latest): ?>
<section class="page pb-0">
    <div class="container">
        <div class="section-title">
            <span class="eyebrow">New on the shelf</span>
            <h2>Recently added books</h2>
        </div>
        <div class="book-grid">
            <?php foreach ($latest as $b): ?>
                <a class="panel book-card" href="books.php?q=<?= urlencode($b['title']) ?>">
                    <?= book_cover($b) ?>
                    <div class="book-info">
                        <span class="cat"><?= e($b['category']) ?></span>
                        <h3><?= e($b['title']) ?></h3>
                        <p class="author">by <?= e($b['author']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4"><a href="books.php" class="btn btn-success">View full catalog</a></div>
    </div>
</section>
<?php endif; ?>

<section class="page">
    <div class="container">
        <div class="section-title">
            <span class="eyebrow">How it works</span>
            <h2>Borrow a book in three steps</h2>
        </div>
        <div class="feature-grid">
            <div class="panel feature step"><span class="step-n">1</span><h3>Create an account</h3><p>Register with a username and password. It takes less than a minute.</p></div>
            <div class="panel feature step"><span class="step-n">2</span><h3>Find and borrow</h3><p>Search or filter by subject, then borrow any available title with one click.</p></div>
            <div class="panel feature step"><span class="step-n">3</span><h3>Read and return</h3><p>Each loan is due in 14 days. Return books anytime from My Books.</p></div>
        </div>
    </div>
</section>

<section class="cta">
    <div class="container">
        <h2>Ready to start reading?</h2>
        <p>Join the Library of the PCNL today.</p>
        <a href="register.php" class="btn btn-gold btn-lg">Create your account</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
