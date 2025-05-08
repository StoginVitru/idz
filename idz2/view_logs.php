<?php
require 'db_connect.php';

$logs = $db->query("SELECT * FROM request_logs ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Логи запитів</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h1>Логи запитів користувачів</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>URL</th>
                <th>Параметри</th>
                <th>Час</th>
                <th>Браузер</th>
                <th>Широта</th>
                <th>Довгота</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= htmlspecialchars($log['id']) ?></td>
                    <td><?= htmlspecialchars($log['endpoint']) ?></td>
                    <td><?= htmlspecialchars($log['parameters']) ?></td>
                    <td><?= htmlspecialchars($log['timestamp']) ?></td>
                    <td><?= htmlspecialchars($log['browser']) ?></td>
                    <td><?= htmlspecialchars($log['latitude']) ?></td>
                    <td><?= htmlspecialchars($log['longitude']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
