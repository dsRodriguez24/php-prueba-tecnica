<?php
namespace App\Controllers;

use PDO;

class GenderController
{
    private $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function getAll()
    {
        $stmt = $this->pdo->query('SELECT * FROM genero ORDER BY id');
        return $stmt->fetchAll();
    }

    public function getById(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM genero WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO genero (nombre) VALUES (?)');
        $stmt->execute([$data['nombre'] ?? null]);
        return ['id' => $this->pdo->lastInsertId()];
    }

    public function update(int $id, array $data)
    {
        $stmt = $this->pdo->prepare('UPDATE genero SET nombre = ? WHERE id = ?');
        $stmt->execute([$data['nombre'] ?? null, $id]);
        return ['updated' => $stmt->rowCount()];
    }

    public function delete(int $id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM genero WHERE id = ?');
        $stmt->execute([$id]);
        return ['deleted' => $stmt->rowCount()];
    }
}
