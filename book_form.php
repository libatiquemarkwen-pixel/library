<?php

require 'includes/config.php';
need_admin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$book = ['title' => '', 'author' => '', 'category' => '', 'copies' => 1];


if ($id > 0) {
    $stmt = $db->prepare('SELECT * FROM books WHERE id = ?');
    $stmt->execute([$id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        header('Location: books.php');
        exit;
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $copies = (int) $_POST['copies'];
    $category = trim($_POST['category'] ?? '');

    if ($title == '' || strlen($title) > 100) {
        $error = 'Title is required (max 100 characters).';
    } elseif ($author == '' || strlen($author) > 100) {
        $error = 'Author is required (max 100 characters).';
    } elseif ($category == '' || strlen($category) > 50) {
        $error = 'Category is required (max 50 characters).';
    } elseif ($copies < 0 || $copies > 999) {
        $error = 'Copies must be from 0 to 999.';
    } else {
        if ($id > 0) {
            $stmt = $db->prepare('UPDATE books SET title = ?, author = ?, category = ?, copies = ? WHERE id = ?');
            $stmt->execute([$title, $author, $category, $copies, $id]);
            $msg = 'Book updated.';
        } else {
            $stmt = $db->prepare('INSERT INTO books (title, author, category, copies) VALUES (?, ?, ?, ?)');
            $stmt->execute([$title, $author, $category, $copies]);
            $msg = 'Book added.';
        }
        header('Location: books.php?msg=' . urlencode($msg));
        exit;
    }

    $book = ['title' => $title, 'author' => $author, 'category' => $category, 'copies' => $copies];
}

$page_title = $id > 0 ? 'Edit Book' : 'Add Book';
$cats = $db->query('SELECT DISTINCT category FROM books ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);
include 'includes/header.php';
?>

<section class="page">
    <div class="container" style="max-width: 640px;">
        <div class="page-head">
            <div>
                <h2><?= $id > 0 ? 'Edit Book' : 'Add Book' ?></h2>
                <p><?= $id > 0 ? 'Update the details of this title.' : 'Add a new title to the library catalog.' ?></p>
            </div>
        </div>

        <div class="panel panel-pad">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label" for="title">Title</label>
                    <input type="text" id="title" name="title" class="form-control" value="<?= e($book['title']) ?>" maxlength="100" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="author">Author</label>
                    <input type="text" id="author" name="author" class="form-control" value="<?= e($book['author']) ?>" maxlength="100" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="category">Category</label>
                    <input type="text" id="category" name="category" class="form-control" list="catList" value="<?= e($book['category']) ?>" maxlength="50" placeholder="e.g. Computer Science" required>
                    <datalist id="catList"><?php foreach ($cats as $c): ?><option value="<?= e($c) ?>"><?php endforeach; ?></datalist>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="copies">Copies in stock</label>
                    <input type="number" id="copies" name="copies" class="form-control" value="<?= e($book['copies']) ?>" min="0" max="999" required>
                </div>
                <button type="submit" class="btn btn-success">Save</button>
                <a href="books.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
