<?php
require 'includes/config.php';
need_login();

$stmt = $db->prepare(
    'SELECT borrows.*, books.title, books.author
     FROM borrows JOIN books ON borrows.book_id = books.id
     WHERE borrows.user_id = ?
     ORDER BY borrows.returned_at IS NOT NULL, borrows.borrowed_at DESC'
);
$stmt->execute([$_SESSION['user_id']]);
$list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'My Books';
include 'includes/header.php';
?>

<section class="page">
    <div class="container">
        <div class="page-head">
            <div>
                <h2>My Books</h2>
                <p>Books you have borrowed and your return history.</p>
            </div>
            <a href="books.php" class="btn btn-success">Browse catalog</a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success"><?= e($_GET['msg']) ?></div>
        <?php endif; ?>

        <div class="panel table-wrap">
        <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>Title</th><th>Author</th><th>Borrowed</th><th>Due</th><th class="actions">Status</th></tr>
            </thead>
            <tbody>
            <?php if (count($list) == 0): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">You have not borrowed any books yet.</td></tr>
            <?php endif; ?>

            <?php foreach ($list as $row): ?>
                <tr>
                    <td class="book-title"><?= e($row['title']) ?></td>
                    <td><?= e($row['author']) ?></td>
                    <td><?= e(substr($row['borrowed_at'], 0, 10)) ?></td>
                    <td><?= $row['returned_at'] ? '&mdash;' : e($row['due_at'] ?? '&mdash;') ?>
                        <?php if (!$row['returned_at'] && !empty($row['due_at']) && $row['due_at'] < date('Y-m-d')): ?><span class="pill pill-out ms-1">Overdue</span><?php endif; ?></td>
                    <td class="actions">
                        <?php if ($row['returned_at']): ?>
                            <span class="pill pill-ok">Returned <?= e(substr($row['returned_at'], 0, 10)) ?></span>
                        <?php else: ?>
                            <form method="post" action="return.php" class="d-inline">
                                <input type="hidden" name="borrow_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-primary">Return</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
