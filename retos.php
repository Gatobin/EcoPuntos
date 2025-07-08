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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['challenge'])) {
    $challenge = $_POST['challenge'];
    $puntos = [
        'sigeLaPagina' => 50,
        'recolectarBotellas' => 100,
        'reciclarPapel' => 75,
        'talleres' => 150
    ];

    if (isset($puntos[$challenge])) {
        // Sumar puntos
        $users[$username]['puntosRestantes'] += $puntos[$challenge];
        $users[$username]['puntosALoLargoDelTiempo'] += $puntos[$challenge];

        // Registrar reto completado
        $users[$username]['retosComplete'][$challenge]++;

        // Guardar cambios
        saveUsers($users);
        $_SESSION['success_message'] = "¡Reto completado! Has ganado {$puntos[$challenge]} puntos.";
        header('Location: retos.php');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retos - Eco Puntos La Joya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .challenge-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .challenge-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .challenge-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 1.5rem;
        }

        .challenge-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: rgba(255, 255, 255, 0.2);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-complete {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            border: none;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .btn-complete:hover {
            background: linear-gradient(135deg, #e0a800, #d66a00);
        }

        .points-badge {
            background: linear-gradient(135deg, #17a2b8, #6f42c1);
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }

        .completed-badge {
            background-color: #6c757d;
            color: white;
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
                    <i class="fas fa-user-circle me-1"></i>
                    <span class="fw-bold"><?php echo $username; ?></span>
                </div>
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Salir
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-success">
                <i class="fas fa-trophy me-2"></i>Retos Ecológicos
            </h1>
            <p class="lead">Completa estos desafíos y gana puntos para canjear por increíbles recompensas</p>
        </div>

        <div class="row g-4">
            <!-- Reto 1 - Sigue nuestra página -->
            <div class="col-md-6">
                <div class="card challenge-card h-100">
                    <div class="challenge-header text-center">
                        <div class="challenge-icon mx-auto">
                            <i class="fas fa-thumbs-up text-white"></i>
                        </div>
                        <h3 class="card-title mb-0">Sigue nuestra página</h3>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Síguenos en nuestras redes sociales y mantente al día con nuestras iniciativas ecológicas.</p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>Facebook
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>Instagram
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>Twitter
                            </li>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge rounded-pill points-badge">
                                <i class="fas fa-coins me-1"></i>50 puntos
                            </span>
                            <span class="badge rounded-pill completed-badge">
                                Completado: <?php echo $userData['retosComplete']['sigeLaPagina']; ?> veces
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <form method="POST">
                            <input type="hidden" name="challenge" value="sigeLaPagina">
                            <button type="submit" class="btn btn-complete text-white w-100 py-2">
                                <i class="fas fa-check-circle me-1"></i> COMPLETAR RETO
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Reto 2 - Recolecta botellas -->
            <div class="col-md-6">
                <div class="card challenge-card h-100">
                    <div class="challenge-header text-center">
                        <div class="challenge-icon mx-auto">
                            <i class="fas fa-wine-bottle text-white"></i>
                        </div>
                        <h3 class="card-title mb-0">Recolecta botellas</h3>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Lleva botellas plásticas a nuestros centros de acopio y ayuda a reducir la contaminación.</p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>Centro de acopio principal
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-clock text-info me-2"></i>Lunes a Viernes 8am-5pm
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-info-circle text-warning me-2"></i>Mínimo 10 botellas
                            </li>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge rounded-pill points-badge">
                                <i class="fas fa-coins me-1"></i>100 puntos
                            </span>
                            <span class="badge rounded-pill completed-badge">
                                Completado: <?php echo $userData['retosComplete']['recolectarBotellas']; ?> veces
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <button class="btn btn-complete text-white w-100 py-2">
                            <i class="fas fa-check-circle me-1"></i> COMPLETAR RETO
                        </button>
                    </div>
                </div>
            </div>

            <!-- Reto 3 - Recicla papel -->
            <div class="col-md-6">
                <div class="card challenge-card h-100">
                    <div class="challenge-header text-center">
                        <div class="challenge-icon mx-auto">
                            <i class="fas fa-recycle text-white"></i>
                        </div>
                        <h3 class="card-title mb-0">Recicla papel</h3>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Entrega papel para reciclar en nuestros puntos autorizados y contribuye a salvar árboles.</p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>Papel blanco
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>Revistas
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-times-circle text-danger me-2"></i>No papel higiénico
                            </li>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge rounded-pill points-badge">
                                <i class="fas fa-coins me-1"></i>75 puntos
                            </span>
                            <span class="badge rounded-pill completed-badge">
                                Completado: 0 veces
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <button class="btn btn-complete text-white w-100 py-2">
                            <i class="fas fa-check-circle me-1"></i> COMPLETAR RETO
                        </button>
                    </div>
                </div>
            </div>

            <!-- Reto 4 - Participa en talleres -->
            <div class="col-md-6">
                <div class="card challenge-card h-100">
                    <div class="challenge-header text-center">
                        <div class="challenge-icon mx-auto">
                            <i class="fas fa-chalkboard-teacher text-white"></i>
                        </div>
                        <h3 class="card-title mb-0">Participa en talleres</h3>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Asiste a nuestros talleres de educación ambiental y conviértete en un agente de cambio.</p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>Primer sábado de cada mes
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-clock text-info me-2"></i>10am - 12pm
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-map-marker-alt text-warning me-2"></i>Centro comunitario
                            </li>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge rounded-pill points-badge">
                                <i class="fas fa-coins me-1"></i>150 puntos
                            </span>
                            <span class="badge rounded-pill completed-badge">
                                Completado: 0 veces
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <button class="btn btn-complete text-white w-100 py-2">
                            <i class="fas fa-check-circle me-1"></i> COMPLETAR RETO
                        </button>
                    </div>
                </div>
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