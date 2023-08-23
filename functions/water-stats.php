<?php 
include_once 'connection.php';

global $db;
$stmt = $db->prepare("SELECT * FROM `water_data` ORDER BY id DESC LIMIT 1");
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$settingsStmt = $db->prepare("SELECT low_threshold, high_threshold FROM `settings` WHERE id = 1");
$settingsStmt->execute();
$settings = $settingsStmt->fetch(PDO::FETCH_ASSOC);

$response = array(
    'distance' => $result['distance'],
    'level' => $result['level'],
    'liters' => $result['liters'],
    'low' => $settings['low_threshold'],
    'high' => $settings['high_threshold']
);

echo json_encode($response);