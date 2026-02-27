<?php
// auth routes
function handle_auth_route(string $path, string $method, $userCtrl): bool {
    if ($method === 'POST' && $path === '/login') {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        header('Content-Type: application/json');
        echo json_encode($userCtrl->login($data['email'] ?? '', $data['password'] ?? ''));
        return true;
    }
    return false;
}
