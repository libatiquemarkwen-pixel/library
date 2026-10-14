<?php
require 'includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

   
    if (strlen($username) < 3 || strlen($username) > 30) {
        $error = 'Username must be 3 to 30 characters.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password != $confirm) {
        $error = 'Passwords do not match.';
    } else {
    
        $stmt = $db->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $error = 'Username is already taken.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
            $stmt->execute([$username, $hash]);
            header('Location: login.php?registered=1');
            exit;
        }
    }
}

$page_title = 'Register';
include 'includes/header.php';
?>

<div class="auth">
    <div class="auth-art" role="img" aria-label="Library shelves">
        <div>
            <span class="eyebrow">Join the library</span>
            <h2>Your PCNL library account in a minute.</h2>
            <p>Create an account to borrow books and keep track of everything you read.</p>
        </div>
    </div>
    <div class="auth-form">
        <div class="auth-card">
            <img src="assets/img/pcnl-logo.png" alt="PCNL seal" class="crest">
            <h1>Create account</h1>
            <p class="sub">Register as a PCNL library member.</p>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="post" id="registerForm">
                <div class="mb-3">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required minlength="3" maxlength="30" autocomplete="username" value="<?= isset($username) ? e($username) : '' ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required minlength="6" autocomplete="new-password">
                    <div class="form-text">At least 6 characters.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="confirm">Confirm password</label>
                    <input type="password" name="confirm" id="confirm" class="form-control" required autocomplete="new-password">
                </div>
                <div class="text-danger mb-2 small" id="errorMsg" role="alert"></div>
                <button type="submit" class="btn btn-success w-100">Register</button>
            </form>
            <p class="mt-4 mb-0 text-center text-muted">Already registered? <a href="login.php">Login</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
