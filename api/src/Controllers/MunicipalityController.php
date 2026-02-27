<?php
namespace App\Controllers;

use PDO;

class MunicipalityController
{
    private $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function getAll()
    {
        $stmt = $this->pdo->query('SELECT m.*, d.nombre as departamento FROM municipios m LEFT JOIN departamentos d ON m.departamento_id = d.id ORDER BY m.id');
        return $stmt->fetchAll();
    }

    public function getById(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM municipios WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO municipios (departamento_id, nombre) VALUES (?,?)');
        $stmt->execute([$data['departamento_id'] ?? null, $data['nombre'] ?? null]);
        return ['id' => $this->pdo->lastInsertId()];
    }

    public function update(int $id, array $data)
    {
        $stmt = $this->pdo->prepare('UPDATE municipios SET departamento_id = ?, nombre = ? WHERE id = ?');
        $stmt->execute([$data['departamento_id'] ?? null, $data['nombre'] ?? null, $id]);
        return ['updated' => $stmt->rowCount()];
    }

    public function delete(int $id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM municipios WHERE id = ?');
        $stmt->execute([$id]);
        return ['deleted' => $stmt->rowCount()];
    }
}
