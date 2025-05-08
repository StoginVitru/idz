<?php
require 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $stmt = $db->prepare("INSERT INTO request_logs (endpoint, parameters, timestamp, browser, latitude, longitude)
                          VALUES (:endpoint, :parameters, :timestamp, :browser, :latitude, :longitude)");
    $stmt->execute([
        ':endpoint' => $data['endpoint'],
        ':parameters' => $data['parameters'],
        ':timestamp' => $data['timestamp'],
        ':browser' => $data['browser'],
        ':latitude' => $data['latitude'],
        ':longitude' => $data['longitude']
    ]);
}
