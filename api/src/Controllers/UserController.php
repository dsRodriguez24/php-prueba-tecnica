<?php
namespace App\Controllers;

use PDO;
use App\Auth;

class UserController
{
    private $pdo;
    private $auth;

    public function __construct(PDO $pdo, Auth $auth)
    {
        $this->pdo = $pdo;
        $this->auth = $auth;
    }

    public function login(string $email, string $password)
    {
        $stmt = $this->pdo->prepare('SELECT id, email, password FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if (!$user) return ['error' => 'Credenciales inválidas'];
        if (!password_verify($password, $user['password'])) return ['error' => 'Credenciales inválidas'];

        $token = $this->auth->issueToken(['sub' => $user['id'], 'email' => $user['email']]);
        return ['token' => $token];
    }

    // For seeding tests or admin creation
    public function createAdmin(string $email, string $password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare('INSERT INTO users (email, password, role) VALUES (?, ?, ?)');
        $stmt->execute([$email, $hash, 'admin']);
        return $this->pdo->lastInsertId();
    }
}
