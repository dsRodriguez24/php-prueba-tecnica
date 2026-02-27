<?php
// Simple config loader - uses env vars if present
$env = function($k, $d=null){
    if(getenv($k) !== false) return getenv($k);
    if(isset($_ENV[$k])) return $_ENV[$k];
    return $d;
};

return [
    'db' => [
        'host' => $env('DB_HOST', '127.0.0.1'),
        'port' => $env('DB_PORT', 3306),
        'name' => $env('DB_NAME', 'prueba_tecnica'),
        'user' => $env('DB_USER', 'root'),
        'pass' => $env('DB_PASS', ''),
    ],
    'jwt' => [
        'secret' => $env('JWT_SECRET', 'iamdev_super_secret_key_2026_secure_token'),
        'issuer' => $env('JWT_ISSUER', 'prueba-tecnica'),
        'aud' => $env('JWT_AUDIENCE', 'prueba-tecnica-aud')
    ],
];
// $env = function($k, $d=null){
//     if(getenv($k) !== false) return getenv($k);
//     if(isset($_ENV[$k])) return $_ENV[$k];

//     // Simple config loader - uses env vars if present
// }