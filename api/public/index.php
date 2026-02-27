<?php
// Simple API entrypoint
$require_autoload = require __DIR__ . '/../vendor/autoload.php';

// Centralized CORS file applies headers and handles preflight
require __DIR__ . '/../config/cors.php';

// Load main config
$config = require __DIR__ . '/../config.php';
$dbCfg  = $config['db'];
$dsn    = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $dbCfg['host'], $dbCfg['port'], $dbCfg['name']);
$pdo    = new PDO($dsn, $dbCfg['user'], $dbCfg['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);

use App\Auth;
use App\Controllers\UserController;
use App\Controllers\PatientController;
use App\Controllers\DepartmentController;
use App\Controllers\GenderController;
use App\Controllers\MunicipalityController;
use App\Controllers\DocumentTypeController;

$auth           = new Auth($config['jwt']);
$userCtrl       = new UserController($pdo, $auth);
$patientCtrl    = new PatientController($pdo);
// catalog controllers
$deptCtrl = new DepartmentController($pdo);
$genderCtrl = new GenderController($pdo);
$munCtrl = new MunicipalityController($pdo);
$docTypeCtrl = new DocumentTypeController($pdo);

header('Content-Type: application/json');

$method     = $_SERVER['REQUEST_METHOD'];
$uri        = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$rawPath    = rtrim(str_replace('/index.php','',$uri), '/');
$path       = $rawPath;

// Allow direct access to /uploads/... without requiring the API base prefix
if (strpos($rawPath, '/uploads/') === 0) {
    if (handle_uploads_route($rawPath, $method)) {
        exit;
    }
    // otherwise continue and return 404 below
}

// API base prefix (change to desired base). Requests must start with this prefix.
$base = '/api/v1';
if ($base !== '' && strpos($rawPath, $base) !== 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Ruta no encontrada (se esperaba prefijo: ' . $base . ')']);
    exit;
}

// Remove base prefix so routing below is relative.
$path = substr($rawPath, strlen($base));
$path = $path === '' ? '/' : $path;

// Simple router
// include modular route handlers
require __DIR__ . '/../routes/auth.php';
require __DIR__ . '/../routes/patients.php';
require __DIR__ . '/../routes/catalogs.php';
require __DIR__ . '/../routes/uploads.php';

// auth route (no auth required)
if (handle_auth_route($path, $method, $userCtrl)) {
    exit;
}

// Allow direct access to /uploads/... without requiring the API base prefix
// This makes URLs like /uploads/file.jpg work when the server docroot is public/.
if (strpos($path, '/uploads/') === 0) {
    if (handle_uploads_route($path, $method)) exit;
    // if upload handler didn't serve, continue to base-check which will return 404
}

// auth required
$headers    = getallheaders();
$authHeader = $headers['Authorization'] ?? ($headers['authorization'] ?? null);
if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $m)) {
    http_response_code(401);
    echo json_encode(['error' => 'Token requerido']);
    exit;
}



$token      = $m[1];
$payload    = $auth->validateToken($token);
if (!$payload) {
    http_response_code(401);
    echo json_encode(['error' => 'Token inválido']);
    exit;
}

// Patients endpoints
// protected routes: patients and catalogs
if (handle_patients_route($path, $method, $patientCtrl)) exit;
if (handle_catalogs_route($path, $method, $deptCtrl, $genderCtrl, $munCtrl, $docTypeCtrl)) exit;

// /patients/{id}
if (preg_match('#^/patients/(\d+)$#', $path, $m)) {
    $id = (int)$m[1];
    if ($method === 'GET') {
        $res = $patientCtrl->getById($id);
        echo json_encode($res);
        exit;
    }
    if ($method === 'PUT' || $method === 'PATCH') {
        $data   = json_decode(file_get_contents('php://input'), true) ?? [];
        $res    = $patientCtrl->update($id, $data);
        echo json_encode($res);
        exit;
    }
    if ($method === 'DELETE') {
        $res = $patientCtrl->delete($id);
        echo json_encode($res);
        exit;
    }
}

http_response_code(404);
echo json_encode(['error' => 'Ruta no encontrada']);
