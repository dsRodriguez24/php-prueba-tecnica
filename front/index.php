<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Gestión de Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="auth-body bg-transparent">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="mb-3 text-center">
                    <div class="auth-brand mb-1">Gestión de Pacientes</div>
                    <div class="auth-subtitle">Accede al panel administrativo</div>
                </div>
                <div class="card auth-card border-0">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-3 auth-title">Iniciar Sesión</h3>
                        <form id="loginForm">
                            <div class="mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" id="email" class="form-control auth-input" required placeholder="admin@ejemplo.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="password" id="password" class="form-control auth-input" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 btn-auth-primary">Entrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/main.js"></script>
    <script src="js/auth.js"></script>
</body>
</html>