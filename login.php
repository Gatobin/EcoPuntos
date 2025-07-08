<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (authenticateUser($username, $password)) {
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $_SESSION['login_error'] = "Usuario o contraseña incorrectos";
        header('Location: index.php');
        exit;
    }
}

header('Location: index.php');
?>