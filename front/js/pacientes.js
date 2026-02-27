const API_URL = `${URL_BASE_API}patients`;
const token   = localStorage.getItem('token');
let tabla;

// Redirigir si no hay token (Seguridad básica frontend)
if (!token) {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Sesión expirada', 'Vuelve a iniciar sesión para continuar', 'warning')
            .then(() => {
                window.location.href = 'index.php';
            });
    } else {
        window.location.href = 'index.php';
    }
}

$(document).ready(function() {
    // Inicializar DataTable
    tabla = $('#tablaPacientes').DataTable({
        ajax: {
            url: API_URL,
            headers: { 'Authorization': `Bearer ${token}` },
            dataSrc: ''
        },
        columns: [
            { data: 'id' },
            { data: 'nombre1' },
            { data: 'apellido1' },
            { data: 'correo' },
            {
                data: null,
                render: function(data) {
                    return `
                        <button class="btn btn-sm btn-primary" onclick='prepararEdicion(${JSON.stringify(data)})'>Editar</button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarPaciente(${data.id})">Eliminar</button>
                    `;
                }
            }
        ]
        //language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' } // Traducción a español
    });

    // Cargar combos de datos maestros
    cargarTiposDocumento();
    cargarGeneros();
    cargarDepartamentos();

    // Cuando cambia el departamento, cargar municipios
    $('#departamento_id').on('change', function() {
        const deptoId = $(this).val();
        cargarMunicipios(deptoId);
    });

    // Preview de foto al seleccionar archivo
    $('#foto').on('change', function(e) {
        const file   = e.target.files[0];
        const preview = document.getElementById('foto_preview');
        if (!preview) return;

        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                preview.src = ev.target.result;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.classList.add('d-none');
        }
    });
});

// Convertir archivo a Base64
function convertirABase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
    });
}

// Guardar o Actualizar
$('#pacienteForm').on('submit', async function(e) {
    e.preventDefault();

    const id        = $('#pacienteId').val();

    const fotoInput = document.getElementById('foto');
    let fotoBase64  = null;

    // Convertir imagen si existe
    if (fotoInput && fotoInput.files && fotoInput.files[0] && !id ) {
        fotoBase64 = await convertirABase64(fotoInput.files[0]);
    }

    const data = {
        tipo_documento_id: $('#tipo_documento_id').val(),
        numero_documento: $('#numero_documento').val(),
        nombre1: $('#nombre1').val(),
        nombre2: $('#nombre2').val(),
        apellido1: $('#apellido1').val(),
        apellido2: $('#apellido2').val(),
        genero_id: $('#genero_id').val(),
        departamento_id: $('#departamento_id').val(),
        municipio_id: $('#municipio_id').val(),
        correo: $('#correo').val(),
        foto: fotoBase64
    };

    const metodo = id ? 'PUT' : 'POST';
    const url = id ? `${API_URL}/${id}` : API_URL;

    try {
        const resp = await fetch(url, {
            method: metodo,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(data)
        });

        const response = await resp.json();

        if (!response.errors) {
            Swal.fire('Éxito', 'Operación realizada correctamente', 'success');
            bootstrap.Modal.getInstance(document.getElementById('pacienteModal')).hide();
            tabla.ajax.reload();
        } else {
            Swal.fire('Error', response.message || 'Error en los datos', 'error');
        }

    } catch (error) {
        Swal.fire('Error', 'No se pudo conectar con la API', 'error');
    }
});

function eliminarPaciente(id) {
    Swal.fire({
        title: '¿Eliminar paciente?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, borrar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            const resp = await fetch(`${API_URL}/${id}`, {
                method: 'DELETE',
                headers: { 'Authorization': `Bearer ${token}` }
            });
            if (resp.ok) {
                tabla.ajax.reload();
                Swal.fire('Eliminado', '', 'success');
            }
        }
    });
}

function abrirModalCrear() {
    $('#pacienteForm')[0].reset();
    $('#pacienteId').val('');
    $('#modalTitle').text('Nuevo Paciente');

    // Reiniciar selects
    $('#tipo_documento_id').val('');
    $('#genero_id').val('');
    $('#departamento_id').val('');
    $('#municipio_id').empty().append('<option value="">Seleccione</option>');

    // Limpiar input y preview de foto
    const fotoInput = document.getElementById('foto');
    const preview   = document.getElementById('foto_preview');
    if (fotoInput) fotoInput.value = '';
    if (preview) {
        preview.src = '';
        preview.classList.add('d-none');
    }

    new bootstrap.Modal(document.getElementById('pacienteModal')).show();
}

function prepararEdicion(paciente) {
    $('#pacienteId').val(paciente.id);
    $('#tipo_documento_id').val(paciente.tipo_documento_id);
    $('#numero_documento').val(paciente.numero_documento);
    $('#nombre1').val(paciente.nombre1);
    $('#nombre2').val(paciente.nombre2);
    $('#apellido1').val(paciente.apellido1);
    $('#apellido2').val(paciente.apellido2);
    $('#genero_id').val(paciente.genero_id);
    $('#departamento_id').val(paciente.departamento_id);
    $('#correo').val(paciente.correo);

    // Mostrar preview si viene una URL de foto desde la API
    const preview   = document.getElementById('foto_preview');
    const fotoUrl   = paciente.foto_url || paciente.foto || '';
    if (preview && fotoUrl) {
        preview.src = fotoUrl;
        preview.classList.remove('d-none');
    } else if (preview) {
        preview.src = '';
        preview.classList.add('d-none');
    }

    // Cargar municipios del departamento y seleccionar el del paciente
    cargarMunicipios(paciente.departamento_id, paciente.municipio_id);

    $('#modalTitle').text('Editar Paciente');
    new bootstrap.Modal(document.getElementById('pacienteModal')).show();
}

$("#pacienteModal").on("shown.bs.modal", function () {

    const id = $("#pacienteId").val();
    const isEdit = !!id;

    $("#div-foto-paciente").toggle(!isEdit);

});