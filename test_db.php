<?php
$host = '172.105.37.38';
$db   = 'streamline';
$user = 'remoteuser';
$pass = '@Remote#123!';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     echo "Connected successfully";
} catch (\PDOException $e) {
     echo "Connection failed: " . $e->getMessage();
}