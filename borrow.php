<?php
require 'includes/config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    echo json_encode(['ok' => false, 'message' => 'You are not allowed to borrow.']);
    exit;
}

$book_id = (int) $_POST['book_id'];
$user_id = $_SESSION['user_id'];


$stmt = $db->prepare('SELECT id FROM borrows WHERE user_id = ? AND book_id = ? AND returned_at IS NULL');
$stmt->execute([$user_id, $book_id]);
if ($stmt->fetch()) {
    echo json_encode(['ok' => false, 'message' => 'You already borrowed this book.']);
    exit;
}

$db->beginTransaction();

$stmt = $db->prepare('UPDATE books SET copies = copies - 1 WHERE id = ? AND copies > 0');
$stmt->execute([$book_id]);

if ($stmt->rowCount() == 0) {
    $db->rollBack();
    echo json_encode(['ok' => false, 'message' => 'Sorry, no copies left.']);
    exit;
}

$stmt = $db->prepare('INSERT INTO borrows (user_id, book_id, due_at) VALUES (?, ?, DATE_ADD(CURDATE(), INTERVAL 14 DAY))');
$stmt->execute([$user_id, $book_id]);
$db->commit();


$stmt = $db->prepare('SELECT copies FROM books WHERE id = ?');
$stmt->execute([$book_id]);
$copies = $stmt->fetchColumn();

echo json_encode(['ok' => true, 'message' => 'Book borrowed!', 'copies' => (int) $copies]);
