<?php
require 'includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Wrong username or password.';
    }
}

$page_title = 'Login';
include 'includes/header.php';
?>

<div class="auth">
    <div class="auth-art" role="img" aria-label="Library shelves">
        <div>
            <span class="eyebrow">Library of the PCNL</span>
            <h2>Knowledge opens every door.</h2>
            <p>Sign in to browse the catalog, borrow books and manage your loans.</p>
        </div>
    </div>
    <div class="auth-form">
        <div class="auth-card">
            <img src="assets/img/pcnl-logo.png" alt="PCNL seal" class="crest">
            <h1>Welcome back</h1>
            <p class="sub">Sign in to your library account.</p>

            <?php if (isset($_GET['registered'])): ?>
                <div class="alert alert-success">Account created! Please login.</div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" autocomplete="username" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" autocomplete="current-password" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Login</button>
            </form>
            <p class="mt-4 mb-0 text-center text-muted">No account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
