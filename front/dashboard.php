<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="dashboard-body">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-glass mb-4">
        <div class="container">
            <span class="navbar-brand">Panel de pacientes </span>
            <span class="badge-pill-soft ms-auto me-3 d-none d-md-inline">Activo</span>
            <button class="btn btn-outline-light-soft btn-sm" onclick="logout()">Cerrar Sesión</button>
        </div>
    </nav>

    <div class="container dashboard-shell">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="dashboard-title mb-1">Listado de Pacientes</h2>
                <p class="text-secondary mb-0 small">Administra la información de tus pacientes de forma centralizada.</p>
            </div>
            <button class="btn btn-success" onclick="abrirModalCrear()">+ Nuevo Paciente</button>
        </div>

        <div class="card dashboard-card p-3">
            <table id="tablaPacientes" class="table table-striped w-100">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Primer Nombre</th>
                        <th>Primer Apellido</th>
                        <th>Correo</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="pacienteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="pacienteForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Paciente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="pacienteId">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipo de documento</label>
                                <select id="tipo_documento_id" class="form-select" required>
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Número de documento</label>
                                <input type="number" id="numero_documento" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Primer nombre</label>
                                <input type="text" id="nombre1" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Segundo nombre</label>
                                <input type="text" id="nombre2" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Primer apellido</label>
                                <input type="text" id="apellido1" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Segundo apellido</label>
                                <input type="text" id="apellido2" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Género</label>
                                <select id="genero_id" class="form-select" required>
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Departamento</label>
                                <select id="departamento_id" class="form-select" required>
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Municipio</label>
                                <select id="municipio_id" class="form-select" required>
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" id="correo" class="form-control" required>
                        </div>

                        <div class="mb-3" id="div-foto-paciente">
                            <label class="form-label">Foto</label>
                            <input type="file" id="foto" class="form-control" accept="image/*">
                            <img id="foto_preview" class="img-thumbnail mt-2 d-none" alt="Previsualización foto" style="max-height: 140px;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/api.js"></script>
    <script src="js/main.js"></script>
    <script src="js/auth.js"></script>
    <script src="js/pacientes.js"></script>
</body>
</html>