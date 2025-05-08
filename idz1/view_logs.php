<?php
try {
    $db = new PDO('sqlite:logs.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $logs = $db->query("SELECT * FROM request_logs ORDER BY timestamp DESC")->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Логи запитів</h2><pre>";
    foreach ($logs as $log) {
        echo "ID: {$log['id']}\n";
        echo "Скрипт: {$log['endpoint']}\n";
        echo "Параметри: {$log['parameters']}\n";
        echo "Час: {$log['timestamp']}\n";
        echo "-------------------------\n";
    }
    echo "</pre>";
} catch (PDOException $e) {
    echo "❌ Помилка: " . $e->getMessage();
}
?>
