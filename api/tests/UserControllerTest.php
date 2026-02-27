<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Database;
use App\Auth;
use App\Controllers\UserController;

class UserControllerTest extends TestCase
{
    public function testAdminLogin()
    {
        $pdo = Database::fromDsn('sqlite::memory:');
        // create tables
        $pdo->exec("CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, email TEXT UNIQUE, password TEXT, role TEXT);");
        $auth = new Auth(['secret'=>'test','issuer'=>'test','aud'=>'test']);
        $uc = new UserController($pdo, $auth);
        $id = $uc->createAdmin('adm@test', '1234567890');
        $this->assertNotEmpty($id);
        $res = $uc->login('adm@test', '1234567890');
        $this->assertArrayHasKey('token', $res);
    }
}
