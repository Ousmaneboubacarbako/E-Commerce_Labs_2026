<?php

require_once __DIR__ . '/../core/core.php';

core_logout();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="0;url=../view/login.php">
    <title>Logging out...</title>
</head>

<body>
    <script>
        alert('Logout successful');
        window.location.href = '../view/login.php';
    </script>
</body>

</html>