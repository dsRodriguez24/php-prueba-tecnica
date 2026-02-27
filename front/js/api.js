
/**
 * Helper para realizar peticiones fetch con autenticación automática
 */
async function apiRequest(endpoint, method = 'GET', body = null) {
    const token = localStorage.getItem('token');
    
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };

    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    const config = {
        method,
        headers,
    };

    if (body) {
        config.body = JSON.stringify(body);
    }

    try {
        const response = await fetch(`${URL_BASE_API}${endpoint}`, config);
        
        // Si el token expiró o es inválido (401), mostrar mensaje y redirigir al login
        if (response.status === 401) {
            localStorage.removeItem('token');
            if (typeof Swal !== 'undefined') {
                Swal.fire('Sesión expirada', 'Vuelve a iniciar sesión para continuar', 'warning')
                    .then(() => {
                        window.location.href = 'index.php';
                    });
            } else {
                window.location.href = 'index.php';
            }
            return;
        }

        return response;
    } catch (error) {
        console.error('Error en la petición:', error);
        throw error;
    }
}

// ----- Funciones para cargar listas -----
// Endpoints auxiliares (ajusta los nombres según tu API)
const ENDPOINT_TIPOS_DOCUMENTO = 'tipos_documento';
const ENDPOINT_GENEROS         = 'genero';
const ENDPOINT_DEPARTAMENTOS   = 'departamentos';
const ENDPOINT_MUNICIPIOS      = 'municipios';

async function cargarTiposDocumento() {
    try {
        const resp = await apiRequest(ENDPOINT_TIPOS_DOCUMENTO, 'GET');
        const data = await resp.json();
        const $select = $('#tipo_documento_id');
        $select.empty().append('<option value="">Seleccione</option>');
        data.forEach(td => {
            $select.append(`<option value="${td.id}">${td.nombre || td.descripcion}</option>`);
        });
    } catch (error) {
        console.error('Error cargando tipos de documento', error);
    }
}

async function cargarGeneros() {
    try {
        const resp = await apiRequest(ENDPOINT_GENEROS, 'GET');
        const data = await resp.json();
        const $select = $('#genero_id');
        $select.empty().append('<option value="">Seleccione</option>');
        data.forEach(g => {
            $select.append(`<option value="${g.id}">${g.nombre || g.descripcion}</option>`);
        });
    } catch (error) {
        console.error('Error cargando géneros', error);
    }
}

async function cargarDepartamentos() {
    try {
        const resp = await apiRequest(ENDPOINT_DEPARTAMENTOS, 'GET');
        const data = await resp.json();
        const $select = $('#departamento_id');
        $select.empty().append('<option value="">Seleccione</option>');
        data.forEach(d => {
            $select.append(`<option value="${d.id}">${d.nombre}</option>`);
        });
    } catch (error) {
        console.error('Error cargando departamentos', error);
    }
}

async function cargarMunicipios(departamentoId, selectedMunicipioId = null) {
    const $select = $('#municipio_id');

    if (!departamentoId) {
        $select.empty().append('<option value="">Seleccione</option>');
        return;
    }

    try {
        const resp = await apiRequest(`${ENDPOINT_MUNICIPIOS}?departamento_id=${departamentoId}`, 'GET');
        const data = await resp.json();
        $select.empty().append('<option value="">Seleccione</option>');
        data.forEach(m => {
            $select.append(`<option value="${m.id}">${m.nombre}</option>`);
        });
        if (selectedMunicipioId) {
            $select.val(selectedMunicipioId);
        }
    } catch (error) {
        console.error('Error cargando municipios', error);
    }
}