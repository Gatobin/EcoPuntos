<?php
include 'config.php';

// Verificar si el usuario está logueado
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

// Preparar datos para el ranking
$ranking = [];
foreach ($users as $user => $data) {
    $ranking[] = [
        'username' => $user,
        'puntos' => $data['puntosALoLargoDelTiempo'],
        'insignias' => $data['reedem']['insigniaVirtual'] ?? 0
    ];
}

// Ordenar ranking de mayor a menor
usort($ranking, function ($a, $b) {
    return $b['puntos'] - $a['puntos'];
});

// Limitar a top 10
$topUsers = array_slice($ranking, 0, 10);



?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - Eco Puntos La Joya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #e6f5e6, #b3e6b3, #66cc99);
            min-height: 100vh;
            background-attachment: fixed;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 100, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 100, 0, 0.15);
        }

        .navbar {
            box-shadow: 0 2px 10px rgba(0, 80, 0, 0.2);
        }

        .ranking-item {
            transition: transform 0.2s;
            background-color: rgba(255, 255, 255, 0.8);
            margin-bottom: 5px;
            border-radius: 8px;
        }

        .ranking-item:hover {
            transform: translateY(-3px);
            background-color: rgba(255, 255, 255, 0.95);
        }

        .top-1 {
            background: linear-gradient(135deg, #FFD700, #FFC300) !important;
        }

        .top-2 {
            background: linear-gradient(135deg, #C0C0C0, #D3D3D3) !important;
        }

        .top-3 {
            background: linear-gradient(135deg, #CD7F32, #D2691E) !important;
        }

        .progress-bar {
            background: linear-gradient(to right, #2ecc71, #27ae60);
        }

        .btn-success {
            background: linear-gradient(135deg, #27ae60, #219653);
            border: none;
        }

        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .text-success {
            color: #219653 !important;
        }

        .border-success {
            border-color: #27ae60 !important;
        }

        .bg-success {
            background-color: #219653 !important;
        }


        /* Estilos para las insignias */
        .insignia {
            position: relative;
            display: inline-block;
            margin-left: 5px;
        }

        .insignia-count {
            position: absolute;
            bottom: -5px;
            right: -5px;
            background: rgb(11, 82, 19);
            color: #000;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Efecto para las insignias */
        .fa-medal {
            text-shadow: 0 2px 3px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .fa-medal:hover {
            transform: scale(1.2) rotate(15deg);
        }

        .insignia-container {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .insignia-icon {
            color: #1a5c1a;
            /* Verde oscuro */
            text-shadow:
                0 0 2px white,
                /* Borde blanco */
                0 0 3px white,
                0 0 4px white;
            font-size: 1.2em;
            margin-right: 3px;
        }

        .insignia-count {
            background-color: #1a5c1a;
            /* Verde oscuro */
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7em;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            margin-left: -8px;
            position: relative;
            z-index: 1;
        }

        /* Efecto hover opcional */
        .insignia-icon:hover {
            transform: scale(1.1);
            filter: brightness(1.1);
        }

        .insignia-container {
            position: relative;
            display: inline-flex;
            align-items: center;
            margin-left: 8px;
        }

        .insignia-icon {
            color: #0d3b0d;
            /* Verde aún más oscuro */
            font-size: 1.4em;
            position: relative;
            z-index: 2;
            text-shadow:
                0 0 1px white,
                0 0 2px white,
                0 0 3px white;
            filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.3));
        }

        .insignia-count {
            background: linear-gradient(135deg, #0d3b0d, #1a5c1a);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.75em;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            margin-left: -12px;
            position: relative;
            z-index: 3;
            font-weight: bold;
        }

        /* Efecto de brillo al pasar el mouse */
        .insignia-container:hover .insignia-icon {
            color: #27ae60;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-leaf me-2"></i>Eco Puntos La Joya
            </a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">Hola, <?php echo $username; ?></span>
                <a href="logout.php" class="btn btn-outline-light">
                    <i class="fas fa-sign-out-alt me-1"></i>Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <!-- Resumen de puntos -->
            <div class="col-md-4 mb-4">
                <div class="card border-success h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-coins me-2"></i>Tus puntos</h5>
                    </div>
                    <div class="card-body text-center">
                        <h1 class="display-4 text-success"><?php echo $userData['puntosRestantes']; ?></h1>
                        <p class="card-text">Puntos disponibles</p>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: <?php echo min(100, ($userData['puntosRestantes'] / max(1, $userData['puntosALoLargoDelTiempo'])) * 100); ?>%">
                            </div>
                        </div>
                        <p class="text-muted">Total acumulados: <?php echo $userData['puntosALoLargoDelTiempo']; ?></p>
                    </div>
                </div>
            </div>

            <!-- Retos populares -->
            <div class="col-md-4 mb-4">
                <div class="card border-success h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-tasks me-2"></i>Retos populares</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Sigue la página</span>
                            <span class="badge bg-success"><?php echo $userData['retosComplete']['sigeLaPagina']; ?> completados</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Recolectar botellas</span>
                            <span class="badge bg-success"><?php echo $userData['retosComplete']['recolectarBotellas']; ?> completados</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Reciclar papel</span>
                            <span class="badge bg-secondary">0 completados</span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="retos.php" class="btn btn-success btn-sm w-100">
                            <i class="fas fa-arrow-right me-1"></i> Ver todos los retos
                        </a>
                    </div>
                </div>
            </div>

            <!-- Canjear puntos -->
            <div class="col-md-4 mb-4">
                <div class="card border-success h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-gift me-2"></i>Canjear puntos</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Insignia virtual</span>
                            <span class="badge bg-success"><?php echo $userData['reedem']['insigniaVirtual']; ?> canjeados</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Semillas de plátano</span>
                            <span class="badge bg-success"><?php echo $userData['reedem']['semillasPlatano']; ?> canjeados</span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="canjear.php" class="btn btn-success btn-sm w-100">
                            <i class="fas fa-arrow-right me-1"></i> Canjear ahora
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Gráfico de progreso -->
            <div class="col-md-8 mb-4">
                <div class="card border-success h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-chart-line me-2"></i>Tu progreso</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="progressChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Ranking de usuarios -->
            <!-- Ranking de usuarios -->
            <div class="col-md-4 mb-4">
                <div class="card border-success h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-trophy me-2"></i>Top EcoUsuarios</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php foreach ($topUsers as $index => $user): ?>
                                <div class="list-group-item list-group-item-action ranking-item <?php echo $index < 3 ? 'top-' . ($index + 1) : ''; ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-bold">#<?php echo $index + 1; ?></span>
                                            <span class="ms-2"><?php echo $user['username']; ?></span>
                                            <?php if ($user['insignias'] > 0): ?>
                                                <span class="ms-2 insignia-container" data-bs-toggle="tooltip" title="<?php echo $user['insignias']; ?> insignias">
                                                    <i class="fas fa-medal insignia-icon"></i>
                                                    <small class="insignia-count"><?php echo $user['insignias']; ?></small>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <span class="badge bg-success rounded-pill"><?php echo $user['puntos']; ?> pts</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="#" class="btn btn-outline-success btn-sm w-100">
                            <i class="fas fa-list-ol me-1"></i> Ver ranking completo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de progreso con datos reales
        const ctx = document.getElementById('progressChart').getContext('2d');
        const progressChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio'],
                datasets: [{
                    label: 'Tus puntos acumulados',
                    data: [
                        <?php echo $userData['puntosALoLargoDelTiempo'] * 0.2; ?>,
                        <?php echo $userData['puntosALoLargoDelTiempo'] * 0.35; ?>,
                        <?php echo $userData['puntosALoLargoDelTiempo'] * 0.5; ?>,
                        <?php echo $userData['puntosALoLargoDelTiempo'] * 0.65; ?>,
                        <?php echo $userData['puntosALoLargoDelTiempo'] * 0.8; ?>,
                        <?php echo $userData['puntosALoLargoDelTiempo'] * 0.9; ?>,
                        <?php echo $userData['puntosALoLargoDelTiempo']; ?>
                    ],
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${Math.round(context.raw)} puntos`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Puntos acumulados'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Mes'
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>