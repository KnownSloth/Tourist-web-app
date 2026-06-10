<?php
$host     = 'localhost';
$dbname   = 'turystyka';
$user     = 'postgres';
$password = 'superuser'; 

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {

    throw new Exception("Błąd połączenia z bazą: " . $e->getMessage());
}