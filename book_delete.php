<?php

require 'includes/config.php';
need_admin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int) $_POST['id'];
    $stmt = $db->prepare('DELETE FROM books WHERE id = ?');
    $stmt->execute([$id]);
}

header('Location: books.php?msg=' . urlencode('Book deleted.'));
exit;
