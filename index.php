<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Puntos La Joya</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-leaf me-2"></i>Eco Puntos La Joya
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">
                            <i class="fas fa-user-plus me-1"></i> Registrarse
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section py-5 bg-light">
        <div class="container text-center py-5">
            <h1 class="display-4 fw-bold text-success mb-4">Transforma tus acciones ecológicas en recompensas</h1>
            <p class="lead mb-4">Únete a nuestra comunidad y comienza a ganar puntos por tus actividades sostenibles.</p>
            <button class="btn btn-success btn-lg px-4 me-2" data-bs-toggle="modal" data-bs-target="#registerModal">
                <i class="fas fa-user-plus me-2"></i>Únete ahora
            </button>
            <button class="btn btn-outline-success btn-lg px-4" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión
            </button>

            <!-- Botón para afiliados -->
            <div class="mt-4">
                <a href="#" class="text-success fw-bold" data-bs-toggle="modal" data-bs-target="#affiliateModal">
                    <i class="fas fa-handshake me-1"></i>Únete como afiliado
                </a>
            </div>
        </div>
    </section>

    <!-- Modal de Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="loginForm" method="POST" action="login.php">
                    <div class="modal-body">
                        <?php if (isset($_SESSION['login_error'])): ?>
                            <div class="alert alert-danger"><?php echo $_SESSION['login_error'];
                                                            unset($_SESSION['login_error']); ?></div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="loginUsername" class="form-label">Nombre de usuario</label>
                            <input type="text" class="form-control" id="loginUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="loginPassword" name="password" required>
                        </div>
                        <div class="text-center">
                            <p>¿No tienes cuenta? <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">Regístrate aquí</a></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Ingresar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Registro -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Registro</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="registerForm" method="POST" action="process_register.php">
                    <div class="modal-body">
                        <?php if (isset($_SESSION['register_error'])): ?>
                            <div class="alert alert-danger"><?php echo $_SESSION['register_error'];
                                                            unset($_SESSION['register_error']); ?></div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="registerUsername" class="form-label">Nombre de usuario</label>
                            <input type="text" class="form-control" id="registerUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerPassword" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="registerPassword" name="password" required>
                        </div>
                        <div class="text-center">
                            <p>¿Ya tienes cuenta? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Inicia sesión aquí</a></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Registrarse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Afiliado -->
    <div class="modal fade" id="affiliateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-handshake me-2"></i>Únete como afiliado</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Quieres ser parte de nuestro programa de afiliados? Completa el formulario y nos pondremos en contacto contigo.</p>
                    <form>
                        <div class="mb-3">
                            <label for="affiliateName" class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" id="affiliateName" required>
                        </div>
                        <div class="mb-3">
                            <label for="affiliateEmail" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="affiliateEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="affiliatePhone" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="affiliatePhone">
                        </div>
                        <div class="mb-3">
                            <label for="affiliateBusiness" class="form-label">Tipo de negocio</label>
                            <input type="text" class="form-control" id="affiliateBusiness">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success">Enviar solicitud</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section (igual que antes) -->
    <section class="py-5">
        <!-- ... mismo contenido de features ... -->
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; 2023 Eco Puntos La Joya. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script personalizado -->
    <script src="assets/js/script.js"></script>
</body>

</html>