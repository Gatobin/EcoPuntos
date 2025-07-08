<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validaciones básicas
    if (empty($username) || empty($password)) {
        $_SESSION['register_error'] = "Por favor completa todos los campos.";
        header('Location: index.php');
        exit;
    }

    if (strlen($username) < 4) {
        $_SESSION['register_error'] = "El nombre de usuario debe tener al menos 4 caracteres.";
        header('Location: index.php');
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['register_error'] = "La contraseña debe tener al menos 6 caracteres.";
        header('Location: index.php');
        exit;
    }

    $users = getUsers();

    // Verificar si el usuario ya existe
    if (isset($users[$username])) {
        $_SESSION['register_error'] = "El nombre de usuario ya está en uso.";
        header('Location: index.php');
        exit;
    }

    // Crear nuevo usuario
    $users[$username] = [
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'retosComplete' => [
            'sigeLaPagina' => 0,
            'recolectarBotellas' => 0
        ],
        'reedem' => [
            'insigniaVirtual' => 0,
            'semillasPlatano' => 0
        ],
        'puntosALoLargoDelTiempo' => 0,
        'puntosRestantes' => 0
    ];



    // Guardar usuarios
    if (saveUsers($users)) {
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit;
    }
    // Después de saveUsers($users)
    if (!file_exists(DATA_FILE)) {
        error_log("Error: El archivo no se creó. Verifica permisos en:");
        die("Error: El archivo no se creó. Verifica permisos en: " . DATA_FILE);
    } else {
        $_SESSION['register_error'] = "Error al guardar el registro. Por favor intenta nuevamente.";
        error_log("Error al guardar el registro. Por favor intenta nuevamente.");
        header('Location: index.php');
        exit;
    }
} else {
    error_log("location index php. fail ");
    header('Location: index.php');
    exit;
}
