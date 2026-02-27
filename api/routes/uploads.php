<?php
// Servir archivos subidos desde api/uploads
function handle_uploads_route(string $path, string $method): bool {
    // Only GET allowed
    if ($method !== 'GET') return false;

    if (!preg_match('#^/uploads/(.+)$#', $path, $m)) return false;
    $name = $m[1];

    // Prevent directory traversal
    $name = basename($name);

    $base = realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR . 'uploads';
    $file = $base . DIRECTORY_SEPARATOR . $name;

    if (!file_exists($file) || !is_file($file)) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Archivo no encontrado']);
        return true;
    }

    $mime = @mime_content_type($file) ?: 'application/octet-stream';
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($file));
    // Allow caching for images
    header('Cache-Control: public, max-age=31536000');
    readfile($file);
    return true;
}
