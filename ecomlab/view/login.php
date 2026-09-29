<?php

require_once __DIR__ . '/../core/core.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
</head>

<body>
    <div class="page-shell auth-page">
        <div class="card auth-card">
            <!-- <nav>
                <a href="../index.php">Home</a>
                <a href="register.php">Register</a>
            </nav> -->

            <section class="section">
                <h1>Login</h1>

                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="alert error">
                        <?= htmlspecialchars($_SESSION['login_error'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <?php unset($_SESSION['login_error']); ?>
                <?php endif; ?>

                <div class="form-panel auth-form-panel">
                    <form id="loginForm" class="auth-form">
                        <div class="form-group full">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="form-group full">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>

                        <div class="form-group full">
                            <button type="submit">Login</button>
                        </div>
                    </form>
                    <p id="loginMessage"></p>
                </div>

                <p class="auth-link">
                    Don't have an account?
                    <a href="register.php">Register</a>
                </p>
            </section>
        </div>
    </div>
</body>

<script>
    const loginForm = document.getElementById('loginForm');
    const loginMessage = document.getElementById('loginMessage');

    if (loginForm) {
        loginForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(loginForm);

            fetch('../actions/login_action.php', {
                method: 'POST',
                body: formData
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    loginMessage.textContent = data.message;
                    window.alert(data.message);

                    if (data.success) {
                        loginForm.reset();
                        window.location.href = '../index.php';
                    }
                })
                .catch(function () {
                    const msg = 'Something went wrong. Please try again.';
                    loginMessage.textContent = msg;
                    window.alert(msg);
                });
        });
    }
</script>

</html>