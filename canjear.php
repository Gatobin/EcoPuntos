<?php
include 'config.php';

// Verificar sesión
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$username = $_SESSION['username'];
$users = getUsers();
$userData = $users[$username] ?? null;

if (!$userData) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Procesar canje de puntos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item'])) {
    $item = $_POST['item'];
    $costos = [
        'insignia' => 50,
        'semillas' => 100,
        'bolsa' => 200
    ];
    
    if (isset($costos[$item])) {
        if ($userData['puntosRestantes'] >= $costos[$item]) {
            // Actualizar puntos
            $users[$username]['puntosRestantes'] -= $costos[$item];
            
            // Registrar canje
            if ($item === 'insignia') {
                $users[$username]['reedem']['insigniaVirtual']++;
            } elseif ($item === 'semillas') {
                $users[$username]['reedem']['semillasPlatano']++;
            } elseif ($item === 'bolsa') {
                if (!isset($users[$username]['reedem']['bolsaEcologica'])) {
                    $users[$username]['reedem']['bolsaEcologica'] = 0;
                }
                $users[$username]['reedem']['bolsaEcologica']++;
            }
            
            // Guardar cambios
            saveUsers($users);
            $_SESSION['success_message'] = "¡Canje exitoso!";
            header('Location: canjear.php');
            exit;
        } else {
            $_SESSION['error_message'] = "No tienes suficientes puntos para este artículo.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canjear puntos - Eco Puntos La Joya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .reward-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 15px;
            overflow: hidden;
        }
        .reward-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .reward-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }
        .btn-redeem {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            font-weight: 600;
        }
        .btn-redeem:hover {
            background: linear-gradient(135deg, #218838, #17a2b8);
        }
        .points-badge {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top shadow">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-arrow-left me-2"></i>
                <i class="fas fa-leaf me-2"></i>Eco Puntos
            </a>
            <div class="d-flex align-items-center">
                <div class="me-3 text-white">
                    <i class="fas fa-coins me-1"></i>
                    <span class="fw-bold"><?php echo $userData['puntosRestantes']; ?> pts</span>
                </div>
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Salir
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container py-5">
        <!-- Mensajes de éxito/error -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <h1 class="text-center mb-5">
            <i class="fas fa-gift text-success me-2"></i>
            <span class="fw-bold">Canjea tus puntos</span>
        </h1>

        <div class="row g-4">
            <!-- Insignia Virtual -->
            <div class="col-md-4">
                <div class="card reward-card h-100 border-success">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="card-title mb-0 text-center">Insignia Virtual</h5>
                    </div>
                    <div class="card-body text-center py-4">
                        <div class="reward-icon text-warning">
                            <i class="fas fa-medal"></i>
                        </div>
                        <p class="card-text">Muestra tu compromiso ecológico con esta insignia digital en tu perfil.</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                Canjeado: <?php echo $userData['reedem']['insigniaVirtual']; ?> veces
                            </span>
                            <span class="badge bg-primary points-badge">
                                <i class="fas fa-coins me-1"></i>50 pts
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <form method="POST">
                            <input type="hidden" name="item" value="insignia">
                            <button type="submit" class="btn btn-redeem text-white w-100 py-2">
                                CANJEAR AHORA
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Semillas de Plátano -->
            <div class="col-md-4">
                <div class="card reward-card h-100 border-success">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="card-title mb-0 text-center">Semillas de Plátano</h5>
                    </div>
                    <div class="card-body text-center py-4">
                        <div class="reward-icon text-success">
                            <i class="fas fa-seedling"></i>
                        </div>
                        <p class="card-text">Kit con 10 semillas de plátano para cultivar en tu hogar o comunidad.</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                Canjeado: <?php echo $userData['reedem']['semillasPlatano']; ?> veces
                            </span>
                            <span class="badge bg-primary points-badge">
                                <i class="fas fa-coins me-1"></i>100 pts
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <form method="POST">
                            <input type="hidden" name="item" value="semillas">
                            <button type="submit" class="btn btn-redeem text-white w-100 py-2">
                                CANJEAR AHORA
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bolsa Ecológica -->
            <div class="col-md-4">
                <div class="card reward-card h-100 border-success">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="card-title mb-0 text-center">Bolsa Ecológica</h5>
                    </div>
                    <div class="card-body text-center py-4">
                        <div class="reward-icon text-info">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <p class="card-text">Bolsa reutilizable de tela para tus compras, reduce el uso de plástico.</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                Canjeado: <?php echo $userData['reedem']['bolsaEcologica'] ?? 0; ?> veces
                            </span>
                            <span class="badge bg-primary points-badge">
                                <i class="fas fa-coins me-1"></i>200 pts
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <form method="POST">
                            <input type="hidden" name="item" value="bolsa">
                            <button type="submit" class="btn btn-redeem text-white w-100 py-2">
                                CANJEAR AHORA
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de instrucciones -->
        <div class="card border-success mt-5">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>¿Cómo canjear tus puntos?</h5>
            </div>
            <div class="card-body">
                <ol class="list-group list-group-numbered">
                    <li class="list-group-item border-0">Selecciona la recompensa que deseas</li>
                    <li class="list-group-item border-0">Haz clic en "CANJEAR AHORA"</li>
                    <li class="list-group-item border-0">Los puntos se descontarán automáticamente</li>
                    <li class="list-group-item border-0">Para productos físicos, te contactaremos para coordinar la entrega</li>
                    <li class="list-group-item border-0">Las insignias virtuales aparecerán en tu perfil inmediatamente</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Eco Puntos La Joya. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>