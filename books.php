<?php

require 'includes/config.php';
$role = isset($_SESSION['user_id']) ? $_SESSION['role'] : 'guest';
$books = $db->query('SELECT * FROM books ORDER BY title')->fetchAll(PDO::FETCH_ASSOC);
$borrowed = [];
if ($role == 'user') {
    $stmt = $db->prepare('SELECT book_id FROM borrows WHERE user_id = ? AND returned_at IS NULL');
    $stmt->execute([$_SESSION['user_id']]);
    $borrowed = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

$total_titles = count($books);
$tones = ['navy', 'plum', 'rust', 'teal', 'slate', 'green'];
$total_copies = 0;
foreach ($books as $b) {
    $total_copies += (int) $b['copies'];
}
$page_title = 'Catalog';

include 'includes/header.php';
?>

<section class="hero hero--compact">
    <div class="container">
        <span class="eyebrow">Library Catalog</span>
        <h1>Find your next book</h1>
        <p class="lead">Browse every title in the PCNL Library collection.</p>
    </div>
</section>

<section class="page page--overlap">
    <div class="container">

        <div class="stats">
            <div class="panel stat">
                <div class="stat-icon"><?php echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5z"/><path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20v-5"/></svg>'; ?></div>
                <div><div class="stat-num"><?= $total_titles ?></div><div class="stat-label">Titles</div></div>
            </div>
            <div class="panel stat">
                <div class="stat-icon"><?php echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4 12 14.01l-3-3"/></svg>'; ?></div>
                <div><div class="stat-num"><?= $total_copies ?></div><div class="stat-label">Copies available</div></div>
            </div>
            <?php if ($role == 'user'): ?>
            <div class="panel stat">
                <div class="stat-icon"><?php echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'; ?></div>
                <div><div class="stat-num"><?= count($borrowed) ?></div><div class="stat-label">Books you have</div></div>
            </div>
            <?php endif; ?>
        </div>

        <div class="page-head">
            <div>
                <h2>Books</h2>
                <p>Search by title or author.</p>
            </div>
            <?php if ($role == 'admin'): ?>
                <a href="book_form.php" class="btn btn-success">+ Add Book</a>
            <?php endif; ?>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success"><?= e($_GET['msg']) ?></div>
        <?php endif; ?>
        <div id="message" role="status"></div>

        <div class="search-bar mb-3">
            <?php echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>'; ?>
            <input type="text" id="searchBox" class="form-control" placeholder="Search by title or author..." aria-label="Search books" value="<?= e($_GET['q'] ?? '') ?>">
        </div>

        <?php $cats = array_values(array_unique(array_column($books, 'category'))); sort($cats); ?>
        <div class="filters">
            <div class="chips" id="chips">
                <button type="button" class="chip is-active" data-cat="">All</button>
                <?php foreach ($cats as $c): ?><button type="button" class="chip" data-cat="<?= e($c) ?>"><?= e($c) ?></button><?php endforeach; ?>
            </div>
            <label class="avail-toggle"><input type="checkbox" id="onlyAvail"> Available only</label>
        </div>

        <div class="book-grid" id="bookGrid">
        <?php foreach ($books as $b): ?>
            <article class="panel book-card" data-cat="<?= e($b['category']) ?>" data-avail="<?= $b['copies'] > 0 ? 1 : 0 ?>">
                <div class="book-cover tone-<?= $tones[abs(crc32($b['title'])) % count($tones)] ?>">
                    <span class="bc-title"><?= e($b['title']) ?></span>
                    <span class="bc-author"><?= e($b['author']) ?></span>
                </div>
                <div class="book-info">
                    <span class="cat"><?= e($b['category']) ?></span>
                    <h3><?= e($b['title']) ?></h3>
                    <p class="author">by <?= e($b['author']) ?></p>
                    <span class="pill <?= $b['copies'] < 1 ? 'pill-out' : ($b['copies'] <= 1 ? 'pill-low' : 'pill-ok') ?>" id="copies-<?= $b['id'] ?>"><?= $b['copies'] < 1 ? 'None left' : (int) $b['copies'] . ' ' . ($b['copies'] == 1 ? 'copy' : 'copies') ?></span>
                    <div class="book-actions">
                        <?php if ($role == 'admin'): ?>
                            <a href="book_form.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form method="post" action="book_delete.php" class="d-inline" onsubmit="return confirm('Delete this book?');">
                                <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        <?php elseif ($role == 'guest'): ?>
                            <a href="login.php" class="btn btn-sm btn-primary">Login to borrow</a>
                        <?php elseif (in_array($b['id'], $borrowed)): ?>
                            <button class="btn btn-sm btn-secondary" disabled>Borrowed</button>
                        <?php elseif ($b['copies'] < 1): ?>
                            <button class="btn btn-sm btn-secondary" disabled>Not available</button>
                        <?php else: ?>
                            <button class="btn btn-sm btn-primary btn-borrow" data-id="<?= $b['id'] ?>">Borrow</button>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
        </div>
        <p class="text-center text-muted py-4" id="noResults" style="display:<?= count($books) == 0 ? 'block' : 'none' ?>"><?= count($books) == 0 ? 'No books yet.' : 'No books match your search.' ?></p>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
