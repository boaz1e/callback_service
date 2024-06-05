<!-- views/login.php -->
<h2>Login</h2>
<form action="backoffice.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <button type="submit" name="login">Login</button>
    <?php if (isset($login_error)): ?>
        <p class="error-message"><?php echo $login_error; ?></p>
    <?php endif; ?>
</form>
