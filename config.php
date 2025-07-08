<?php
// Configuración básica
define('DATA_FILE', __DIR__ . '/data/users.json');

// Función para leer usuarios
function getUsers() {
    if (!file_exists(DATA_FILE)) {
        // Crear directorio si no existe
        if (!is_dir(dirname(DATA_FILE))) {
            mkdir(dirname(DATA_FILE), 0755, true);
        }
        
        // Crear archivo con array vacío
        file_put_contents(DATA_FILE, json_encode([], JSON_PRETTY_PRINT));
        return [];
    }
    
    $data = file_get_contents(DATA_FILE);
    return json_decode($data, true) ?: [];
}

// Función para guardar usuarios
function saveUsers($users) {
    try {
        $result = file_put_contents(DATA_FILE, json_encode($users, JSON_PRETTY_PRINT));
        return $result !== false;
    } catch (Exception $e) {
        error_log("Error saving users: " . $e->getMessage());
        return false;
    }
}

// Iniciar sesión
session_start();


// Función para autenticar usuario
function authenticateUser($username, $password) {
    $users = getUsers();
    
    if (!isset($users[$username])) {
        return false;
    }
    
    return password_verify($password, $users[$username]['password']);
}