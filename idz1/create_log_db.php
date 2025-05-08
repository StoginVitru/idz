<?php
try {
    $db = new PDO('sqlite:logs.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("
        CREATE TABLE IF NOT EXISTS request_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            endpoint TEXT NOT NULL,
            parameters TEXT,
            timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    echo "✅ Таблиця request_logs створена або вже існує.";
} catch (PDOException $e) {
    echo "❌ Помилка: " . $e->getMessage();
}
?>
