<?php

$host = 'sql109.infinityfree.com';
$dbname = 'if0_43172178_library';
$dbuser = 'if0_43172178';
$dbpass = 'qkaGoHAAAdTt';

session_start();

$library = ['address' => '', 'hours' => '', 'email' => '', 'phone' => ''];

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed. Check config.php');
}


function e($text) {
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}


function need_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}
function need_admin() {
    need_login();
    if ($_SESSION['role'] != 'admin') {
        die('Access denied. Admins only.');
    }
}

function book_tone($title) {
    $tones = ['navy', 'plum', 'rust', 'teal', 'slate', 'green'];
    return $tones[abs(crc32($title)) % count($tones)];
}
function book_cover($b) {
    return '<div class="book-cover tone-' . book_tone($b['title']) . '"><span class="bc-title">' . e($b['title']) . '</span><span class="bc-author">' . e($b['author']) . '</span></div>';
}
