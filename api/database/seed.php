<?php
// Script para ejecutar migraciones y seeders desde PHP
// Uso: php database/seed.php

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config.php';
$dbCfg = $config['db'];

$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $dbCfg['host'], $dbCfg['port'], $dbCfg['name']);
$pdo = new PDO($dsn, $dbCfg['user'], $dbCfg['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

echo "Ejecutando migraciones...\n";
$sql = file_get_contents(__DIR__ . '/migrations.sql');
$pdo->exec($sql);

echo "Insertando seed data...\n";
$pdo->beginTransaction();
try {
    $departamentos = ['Departamento A','Departamento B','Departamento C','Departamento D','Departamento E'];
    $stmt = $pdo->prepare('INSERT INTO departamentos (nombre) VALUES (?)');
    foreach ($departamentos as $d) $stmt->execute([$d]);

    $municipios = [
        [1,'Municipio A1'],[1,'Municipio A2'],
        [2,'Municipio B1'],[2,'Municipio B2'],
        [3,'Municipio C1'],[3,'Municipio C2'],
        [4,'Municipio D1'],[4,'Municipio D2'],
        [5,'Municipio E1'],[5,'Municipio E2']
    ];
    $stmt = $pdo->prepare('INSERT INTO municipios (departamento_id, nombre) VALUES (?,?)');
    foreach ($municipios as $m) $stmt->execute($m);

    $stmt = $pdo->prepare('INSERT INTO tipos_documento (nombre) VALUES (?)');
    $stmt->execute(['CC']); $stmt->execute(['TI']);

    $stmt = $pdo->prepare('INSERT INTO genero (nombre) VALUES (?)');
    $stmt->execute(['Masculino']); $stmt->execute(['Femenino']);

    // admin user
    $password  = password_hash('1234567890', PASSWORD_DEFAULT);
    $stmt      = $pdo->prepare('INSERT INTO users (email, password, role) VALUES (?,?,?)');
    $stmt->execute(['admin@example.com', $password, 'admin']);

    // Insertar 5 pacientes de prueba
    $patients = [
        ['1','1001','Juan','Carlos','Perez','Gomez',1,1,1,'juan.perez@example.com', null],
        ['2','1002','Ana',null,'Lopez',null,2,2,3,'ana.lopez@example.com', null],
        ['1','1003','Luis',null,'Martinez',null,1,3,5,'luis.m@example.com', null],
        ['2','1004','Maria','Luisa','Rodriguez',null,2,4,7,'maria.r@example.com', null],
        ['1','1005','Pedro',null,'Gonzalez',null,1,5,9,'pedro.g@example.com', null]
    ];
    $ins = $pdo->prepare('INSERT INTO paciente (tipo_documento_id, numero_documento, nombre1, nombre2, apellido1, apellido2, genero_id, departamento_id, municipio_id, correo, foto) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
    foreach ($patients as $p) $ins->execute($p);

    $pdo->commit();
    echo "Seeds aplicados correctamente.\n";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error en seeds: " . $e->getMessage() . "\n";
}
