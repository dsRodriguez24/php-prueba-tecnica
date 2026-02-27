<?php
namespace App\Controllers;

use PDO;

class PatientController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function validate(array $data, $isUpdate = false)
    {
        $errors = [];
        $required = ['tipo_documento_id','numero_documento','nombre1','apellido1','genero_id','departamento_id','municipio_id','correo'];
        foreach ($required as $f) {
            if (!$isUpdate && empty($data[$f])) $errors[] = "$f es requerido";
        }
        if (!empty($data['correo']) && !filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Correo inválido';
        return $errors;
    }

    public function create(array $data){
        $errs = $this->validate($data);
        if ($errs) return ['errors' => $errs];

        $nombreFoto = $this->guardarImagenBase64($data['foto'] ?? null);

        $sql = "INSERT INTO paciente 
            (tipo_documento_id, numero_documento, nombre1, nombre2, apellido1, apellido2, genero_id, departamento_id, municipio_id, correo, foto) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $data['tipo_documento_id'] ?? null,
            $data['numero_documento'] ?? null,
            $data['nombre1'] ?? null,
            $data['nombre2'] ?? null,
            $data['apellido1'] ?? null,
            $data['apellido2'] ?? null,
            $data['genero_id'] ?? null,
            $data['departamento_id'] ?? null,
            $data['municipio_id'] ?? null,
            $data['correo'] ?? null,
            $nombreFoto
        ]);

        return ['id' => $this->pdo->lastInsertId()];
    }

    public function getAll(int $limit = 100, int $offset = 0)
    {
        $stmt = $this->pdo->prepare('SELECT p.* FROM paciente p WHERE activo = 1 ORDER BY p.id DESC LIMIT ? OFFSET ?');
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM paciente WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function update(int $id, array $data)
    {
        $errs = $this->validate($data, true);
        if ($errs) return ['errors' => $errs];
        
        $fields = [];
        $values = [];
        $allowed = ['tipo_documento_id','numero_documento','nombre1','nombre2','apellido1','apellido2','genero_id','departamento_id','municipio_id','correo','foto'];
        foreach ($allowed as $f) {
            if (array_key_exists($f, $data)) {
                $fields[] = "$f = ?";
                $values[] = $data[$f];
            }
        }

        if (!$fields) return ['error' => 'Nada para actualizar'];
        $values[] = $id;
        $sql = 'UPDATE paciente SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);
        return ['updated' => $stmt->rowCount()];
    }

    public function delete(int $id)
    {
        // $stmt = $this->pdo->prepare('DELETE FROM paciente WHERE id = ?');
        $stmt = $this->pdo->prepare('UPDATE paciente SET activo = 0 WHERE id = ?');
        $stmt->execute([$id]);
        return ['deleted' => $stmt->rowCount()];
    }

    private function guardarImagenBase64($imagenBase64){
        if (empty($imagenBase64)) {
            return null;
        }

        if (!preg_match('/^data:image\/(\w+);base64,/', $imagenBase64, $type)) {
            return null;
        }

        $imagenBase64 = substr($imagenBase64, strpos($imagenBase64, ',') + 1);
        $imagenBase64 = base64_decode($imagenBase64);

        if ($imagenBase64 === false) {
            return null;
        }

        $extension = strtolower($type[1]);

        // Validar extensiones permitidas
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            return null;
        }

        // Save into api/public/uploads so the built-in PHP server (or Apache) can serve it directly
        $baseDir = realpath(__DIR__ . '/../../'); // points to api/
        $directorio = $baseDir . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombreArchivo = uniqid('paciente_', true) . '.' . $extension;

        file_put_contents($directorio . $nombreArchivo, $imagenBase64);

        return $nombreArchivo;
    }
}
