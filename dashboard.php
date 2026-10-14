<?php
require 'includes/config.php';
need_login();

$admin = $_SESSION['role'] == 'admin';
if ($admin) {
    $stats = [
        ['Titles in catalog', (int) $db->query('SELECT COUNT(*) FROM books')->fetchColumn()],
        ['Student members', (int) $db->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn()],
        ['Books on loan', (int) $db->query('SELECT COUNT(*) FROM borrows WHERE returned_at IS NULL')->fetchColumn()],
        ['Overdue loans', (int) $db->query('SELECT COUNT(*) FROM borrows WHERE returned_at IS NULL AND due_at < CURDATE()')->fetchColumn()],
    ];
    $recent = $db->query(
        'SELECT borrows.*, users.username, books.title FROM borrows
         JOIN users ON borrows.user_id = users.id JOIN books ON borrows.book_id = books.id
         ORDER BY borrows.borrowed_at DESC LIMIT 6'
    )->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $db->prepare('SELECT COUNT(*) FROM borrows WHERE user_id = ? AND returned_at IS NULL');
    $stmt->execute([$_SESSION['user_id']]);
    $active = (int) $stmt->fetchColumn();
    $stmt = $db->prepare('SELECT COUNT(*) FROM borrows WHERE user_id = ? AND returned_at IS NOT NULL');
    $stmt->execute([$_SESSION['user_id']]);
    $done = (int) $stmt->fetchColumn();
    $stats = [
        ['Books you have now', $active],
        ['Books returned', $done],
        ['Titles available', (int) $db->query('SELECT COUNT(*) FROM books WHERE copies > 0')->fetchColumn()],
    ];
    $stmt = $db->prepare(
        'SELECT borrows.*, books.title, books.author FROM borrows
         JOIN books ON borrows.book_id = books.id WHERE borrows.user_id = ?
         ORDER BY borrows.borrowed_at DESC LIMIT 6'
    );
    $stmt->execute([$_SESSION['user_id']]);
    $recent = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$page_title = 'Dashboard';
include 'includes/header.php';
?>

<section class="hero hero--compact">
    <div class="container">
        <span class="eyebrow"><?= $admin ? 'Librarian Dashboard' : 'Student Dashboard' ?></span>
        <h1>Hello, <?= e($_SESSION['username']) ?></h1>
        <p class="lead"><?= $admin ? 'Manage the collection and keep an eye on library activity.' : 'Browse books, borrow online and manage your loans.' ?></p>
    </div>
</section>

<section class="page page--overlap">
    <div class="container">
        <div class="stats">
            <?php foreach ($stats as $st): ?>
                <div class="panel stat">
                    <div><div class="stat-num"><?= $st[1] ?></div><div class="stat-label"><?= e($st[0]) ?></div></div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="dash-grid">
            <div class="panel panel-pad">
                <h3 class="h5 mb-3">Quick actions</h3>
                <div class="quick">
                    <a href="books.php" class="btn btn-success">Browse catalog</a>
                    <?php if ($admin): ?>
                        <a href="book_form.php" class="btn btn-gold">+ Add a book</a>
                        <a href="records.php" class="btn btn-secondary">Borrow records</a>
                    <?php else: ?>
                        <a href="my_books.php" class="btn btn-gold">My books</a>
                    <?php endif; ?>
                    <a href="about.php" class="btn btn-secondary">About the library</a>
                </div>
            </div>

            <div class="panel table-wrap">
                <div class="panel-pad pb-2"><h3 class="h5 mb-0"><?= $admin ? 'Recent activity' : 'Your recent loans' ?></h3></div>
                <div class="table-responsive">
                <table class="table">
                    <tbody>
                    <?php if (count($recent) == 0): ?>
                        <tr><td class="text-center text-muted py-4">Nothing here yet.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($recent as $r): ?>
                        <tr>
                            <td class="book-title"><?= e($r['title']) ?>
                                <div class="small text-muted fw-normal"><?= $admin ? e($r['username']) : e($r['author']) ?> &middot; <?= e(substr($r['borrowed_at'], 0, 10)) ?></div>
                            </td>
                            <td class="actions"><?= $r['returned_at'] ? '<span class="pill pill-ok">Returned</span>' : '<span class="pill pill-low">On loan</span>' ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
