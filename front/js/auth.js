const loginForm = document.getElementById('loginForm');

if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const email    = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // Validación básica del lado del cliente (Desafío 3.4)
        if (!email || !password) {
            Swal.fire('Campos vacíos', 'Por favor rellena todos los datos', 'warning');
            return;
        }

        try {
            const response = await fetch(`${URL_BASE_API}login`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });


            
            const data = await response.json();
            
            if (data.token) {
                // Guardamos el token (ajusta 'access_token' según como lo devuelva tu API)
                localStorage.setItem('token', data.token);
                
                Swal.fire({
                    icon: 'success',
                    title: '¡Bienvenido!',
                    text: 'Redirigiendo al panel...',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'dashboard.php';
                });
            } else {
                Swal.fire('Error', data.error || 'Credenciales incorrectas', 'error');
            }
        } catch (error) {
            console.error("Error " , error.message);
            Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
        }
    });
}

// Función de utilidad para verificar si el usuario está logueado al cargar la página
function checkAuth() {
    const token = localStorage.getItem('token');
    const path  = window.location.pathname;

    // Si ya hay token y estamos en el login, ir al dashboard
    if (token && path.includes('index.php')) {
        window.location.href = 'dashboard.php';
        return;
    }

    // Si NO hay token y estamos en el dashboard, volver al login
    if (!token && path.includes('dashboard.php')) {
        window.location.href = 'index.php';
    }
}

checkAuth();

function logout() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Cerrar sesión?',
            text: 'Se cerrará tu sesión actual.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, cerrar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.removeItem('token');
                Swal.fire('Sesión cerrada', 'Vuelve a iniciar sesión cuando lo necesites', 'success')
                    .then(() => {
                        window.location.href = 'index.php';
                    });
            }
        });
    } else {
        localStorage.removeItem('token');
        window.location.href = 'index.php';
    }
}