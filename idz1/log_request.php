<?php
function log_request($endpoint, $parameters = []) {
    try {
        $pdo = new PDO('sqlite:logs.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO request_logs (endpoint, parameters) VALUES (:endpoint, :parameters)");
        $stmt->execute([
            ':endpoint' => $endpoint,
            ':parameters' => json_encode($parameters, JSON_UNESCAPED_UNICODE)
        ]);
    } catch (PDOException $e) {
        error_log("Log DB error: " . $e->getMessage());
    }
}
?>
