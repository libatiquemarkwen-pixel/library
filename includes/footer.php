</main>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <img src="assets/img/pcnl-logo.png" alt="PCNL seal">
                <div>
                    <h4>Library of the PCNL</h4>
                    <p>Serving the students, faculty and staff of the Philippine College of Northwestern Luzon with books, knowledge and a quiet place to learn.</p>
                </div>
            </div>
            <div>
                <h5>Explore</h5>
                <ul>
                    <li><a href="<?= isset($_SESSION['user_id']) ? 'dashboard.php' : 'index.php' ?>"><?= isset($_SESSION['user_id']) ? 'Dashboard' : 'Home' ?></a></li>
                    <li><a href="books.php">Catalog</a></li>
                    <li><a href="about.php">About the Library</a></li>
                </ul>
            </div>
            <div>
                <h5>Account</h5>
                <ul>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?= $_SESSION['role'] == 'admin' ? 'records.php' : 'my_books.php' ?>"><?= $_SESSION['role'] == 'admin' ? 'Borrow Records' : 'My Books' ?></a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php if (array_filter($library)): ?>
            <div>
                <h5>Contact</h5>
                <ul>
                    <?php foreach (['address', 'hours', 'email', 'phone'] as $k): if ($library[$k] !== ''): ?>
                        <li><?= e($library[$k]) ?></li>
                    <?php endif; endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; <?= date('Y') ?> Library of the PCNL &middot; Philippine College of Northwestern Luzon. All rights reserved.
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
