<?php

require 'includes/config.php';
need_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $borrow_id = (int) $_POST['borrow_id'];

   
    $stmt = $db->prepare('SELECT * FROM borrows WHERE id = ? AND user_id = ? AND returned_at IS NULL');
    $stmt->execute([$borrow_id, $_SESSION['user_id']]);
    $borrow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($borrow) {
        $db->beginTransaction();
        $stmt = $db->prepare('UPDATE borrows SET returned_at = NOW() WHERE id = ?');
        $stmt->execute([$borrow_id]);
        $stmt = $db->prepare('UPDATE books SET copies = copies + 1 WHERE id = ?');
        $stmt->execute([$borrow['book_id']]);
        $db->commit();
    }
}

header('Location: my_books.php?msg=' . urlencode('Book returned. Thank you!'));
exit;
