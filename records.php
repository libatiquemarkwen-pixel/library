<?php

require 'includes/config.php';
need_admin();

$records = $db->query(
    'SELECT borrows.*, users.username, books.title
     FROM borrows
     JOIN users ON borrows.user_id = users.id
     JOIN books ON borrows.book_id = books.id
     ORDER BY borrows.borrowed_at DESC'
)->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Borrow Records';
include 'includes/header.php';
?>

<section class="page">
    <div class="container">
        <div class="page-head">
            <div>
                <h2>Borrow Records</h2>
                <p>Every loan made through the library portal.</p>
            </div>
        </div>

        <div class="panel table-wrap">
        <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>User</th><th>Book</th><th>Borrowed</th><th>Due</th><th>Returned</th></tr>
            </thead>
            <tbody>
            <?php if (count($records) == 0): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">No records yet.</td></tr>
            <?php endif; ?>

            <?php foreach ($records as $r): ?>
                <tr>
                    <td><?= e($r['username']) ?></td>
                    <td class="book-title"><?= e($r['title']) ?></td>
                    <td><?= e(substr($r['borrowed_at'], 0, 10)) ?></td>
                    <td><?= e($r['due_at'] ?? '—') ?></td>
                    <td><?= $r['returned_at'] ? e($r['returned_at']) : ((!empty($r['due_at']) && $r['due_at'] < date('Y-m-d')) ? '<span class="pill pill-out">Overdue</span>' : '<span class="pill pill-low">On loan</span>') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
