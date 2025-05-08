<?php
$host = 'localhost';
$dbname = 'lb_pdo_lessons';
$username = 'root';
$password = '';
$db = new PDO('sqlite:logs.db'); // Підключення до SQLite
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Таблиця для логів
$db->exec("CREATE TABLE IF NOT EXISTS request_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    endpoint TEXT NOT NULL,
    parameters TEXT,
    timestamp TEXT,
    browser TEXT,
    latitude REAL,
    longitude REAL
)");



try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Помилка підключення до БД: " . $e->getMessage());
}
?>