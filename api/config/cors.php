<?php
// Full CORS implementation centralized here.
// This file applies headers and handles preflight. Configure via env vars or edit defaults below.

// Defaults
$defaults = [
    'allow_origin' => '*',
    'allow_methods' => 'GET,POST,PUT,PATCH,DELETE,OPTIONS',
    'allow_headers' => 'Content-Type, Authorization, X-Requested-With',
    'allow_credentials' => true,
    'allow_max_age' => 3600,
];

// Load from env if present
$cfg = [
    'allow_origin' => getenv('CORS_ALLOW_ORIGIN') !== false ? getenv('CORS_ALLOW_ORIGIN') : $defaults['allow_origin'],
    'allow_methods' => getenv('CORS_ALLOW_METHODS') !== false ? getenv('CORS_ALLOW_METHODS') : $defaults['allow_methods'],
    'allow_headers' => getenv('CORS_ALLOW_HEADERS') !== false ? getenv('CORS_ALLOW_HEADERS') : $defaults['allow_headers'],
    'allow_credentials' => getenv('CORS_ALLOW_CREDENTIALS') !== false ? filter_var(getenv('CORS_ALLOW_CREDENTIALS'), FILTER_VALIDATE_BOOLEAN) : $defaults['allow_credentials'],
    'allow_max_age' => getenv('CORS_ALLOW_MAX_AGE') !== false ? (int)getenv('CORS_ALLOW_MAX_AGE') : $defaults['allow_max_age'],
];

// If a main config exists and has a 'cors' entry, allow it to override env/defaults
$mainConfigFile = __DIR__ . '/../config.php';
if (file_exists($mainConfigFile)) {
    $main = require $mainConfigFile;
    if (is_array($main) && !empty($main['cors']) && is_array($main['cors'])) {
        $mainCors = $main['cors'];
        $cfg = array_merge($cfg, array_intersect_key($mainCors, $cfg));
    }
}

// Apply headers
header('Access-Control-Allow-Origin: ' . $cfg['allow_origin']);
header('Access-Control-Allow-Methods: ' . $cfg['allow_methods']);
header('Access-Control-Allow-Headers: ' . $cfg['allow_headers']);
header('Access-Control-Max-Age: ' . (int)$cfg['allow_max_age']);
header('Access-Control-Allow-Credentials: ' . ($cfg['allow_credentials'] ? 'true' : 'false'));

// Preflight: terminate here with 204
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
