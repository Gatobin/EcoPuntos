<?php
include 'config.php';

// Destruir sesión
session_destroy();

// Redirigir al inicio
header('Location: index.php');
exit;
?>