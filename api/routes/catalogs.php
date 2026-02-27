<?php
// catalogs routes: departamentos, genero, municipios, tipos_documento
function handle_catalogs_route(string $path, string $method, $deptCtrl, $genderCtrl, $munCtrl, $docTypeCtrl): bool {
    header('Content-Type: application/json');

    // departamentos
    if ($path === '/departamentos' && $method === 'GET') { echo json_encode($deptCtrl->getAll()); return true; }
    if ($path === '/departamentos' && $method === 'POST') { $data = json_decode(file_get_contents('php://input'), true) ?? []; echo json_encode($deptCtrl->create($data)); return true; }
    if (preg_match('#^/departamentos/(\d+)$#', $path, $m)) { $id=(int)$m[1]; if ($method==='GET') { echo json_encode($deptCtrl->getById($id)); return true;} if ($method==='PUT'){ $data=json_decode(file_get_contents('php://input'),true)??[]; echo json_encode($deptCtrl->update($id,$data)); return true;} if ($method==='DELETE'){ echo json_encode($deptCtrl->delete($id)); return true;} }

    // genero
    if ($path === '/genero' && $method === 'GET') { echo json_encode($genderCtrl->getAll()); return true; }
    if ($path === '/genero' && $method === 'POST') { $data = json_decode(file_get_contents('php://input'), true) ?? []; echo json_encode($genderCtrl->create($data)); return true; }
    if (preg_match('#^/genero/(\d+)$#', $path, $m)) { $id=(int)$m[1]; if ($method==='GET'){ echo json_encode($genderCtrl->getById($id)); return true;} if ($method==='PUT'){ $data=json_decode(file_get_contents('php://input'),true)??[]; echo json_encode($genderCtrl->update($id,$data)); return true;} if ($method==='DELETE'){ echo json_encode($genderCtrl->delete($id)); return true;} }

    // municipios
    if ($path === '/municipios' && $method === 'GET') { echo json_encode($munCtrl->getAll()); return true; }
    if ($path === '/municipios' && $method === 'POST') { $data = json_decode(file_get_contents('php://input'), true) ?? []; echo json_encode($munCtrl->create($data)); return true; }
    if (preg_match('#^/municipios/(\d+)$#', $path, $m)) { $id=(int)$m[1]; if ($method==='GET'){ echo json_encode($munCtrl->getById($id)); return true;} if ($method==='PUT'){ $data=json_decode(file_get_contents('php://input'),true)??[]; echo json_encode($munCtrl->update($id,$data)); return true;} if ($method==='DELETE'){ echo json_encode($munCtrl->delete($id)); return true;} }

    // tipos_documento
    if ($path === '/tipos_documento' && $method === 'GET') { echo json_encode($docTypeCtrl->getAll()); return true; }
    if ($path === '/tipos_documento' && $method === 'POST') { $data = json_decode(file_get_contents('php://input'), true) ?? []; echo json_encode($docTypeCtrl->create($data)); return true; }
    if (preg_match('#^/tipos_documento/(\d+)$#', $path, $m)) { $id=(int)$m[1]; if ($method==='GET'){ echo json_encode($docTypeCtrl->getById($id)); return true;} if ($method==='PUT'){ $data=json_decode(file_get_contents('php://input'),true)??[]; echo json_encode($docTypeCtrl->update($id,$data)); return true;} if ($method==='DELETE'){ echo json_encode($docTypeCtrl->delete($id)); return true;} }

    return false;
}
