<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Database;
use App\Controllers\PatientController;

class PatientControllerTest extends TestCase
{
    public function testPatientCrud()
    {
        $pdo = Database::fromDsn('sqlite::memory:');
        $pdo->exec("CREATE TABLE paciente (id INTEGER PRIMARY KEY AUTOINCREMENT, tipo_documento_id INTEGER, numero_documento TEXT, nombre1 TEXT, nombre2 TEXT, apellido1 TEXT, apellido2 TEXT, genero_id INTEGER, departamento_id INTEGER, municipio_id INTEGER, correo TEXT, foto TEXT);");
        $pc = new PatientController($pdo);
        $res = $pc->create(['tipo_documento_id'=>1,'numero_documento'=>'900','nombre1'=>'Test','apellido1'=>'User','genero_id'=>1,'departamento_id'=>1,'municipio_id'=>1,'correo'=>'t@example.com']);
        $this->assertArrayHasKey('id', $res);
        $id = $res['id'];
        $found = $pc->getById($id);
        $this->assertEquals('Test', $found['nombre1']);
        $pc->update($id, ['nombre1'=>'Updated']);
        $found2 = $pc->getById($id);
        $this->assertEquals('Updated', $found2['nombre1']);
        $del = $pc->delete($id);
        $this->assertEquals(1, $del['deleted']);
    }
}
