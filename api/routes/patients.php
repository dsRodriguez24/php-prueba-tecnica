<?php
// patient routes (assumes auth already verified)
function handle_patients_route(string $path, string $method, $patientCtrl): bool {
    header('Content-Type: application/json');
    if ($path === '/patients' && $method === 'GET') {
        echo json_encode($patientCtrl->getAll());
        return true;
    }
    if ($path === '/patients' && $method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        echo json_encode($patientCtrl->create($data));
        return true;
    }
    if (preg_match('#^/patients/(\d+)$#', $path, $m)) {
        $id = (int)$m[1];
        if ($method === 'GET') { echo json_encode($patientCtrl->getById($id)); return true; }
        if ($method === 'PUT' || $method === 'PATCH') { $data = json_decode(file_get_contents('php://input'), true) ?? []; echo json_encode($patientCtrl->update($id, $data)); return true; }
        if ($method === 'DELETE') { echo json_encode($patientCtrl->delete($id)); return true; }
    }
    return false;
}
