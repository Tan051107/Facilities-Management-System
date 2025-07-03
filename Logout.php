<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSphere</title>
    <link rel="icon" href="img/UniSphere.png" type="image/png">
</head>
<body>
<script>
    localStorage.setItem('theme', 'light');
    window.location.href = "Login.php";
</script>
</body>
</html>