<?php
include_once 'functions/connection.php';

function settings_data(){
    global $db;
    $stmt = $db->prepare("SELECT * FROM `settings` WHERE id = 1");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $firstEntryStmt = $db->prepare("SELECT MIN(created_at) AS first_entry_time FROM `water_data` WHERE level < 0 AND DATE(`created_at`) = CURDATE() LIMIT 50");
    $firstEntryStmt->execute();
    $firstEntryTime = $firstEntryStmt->fetchColumn();
    $threeMinutesAgo = strtotime('-3 minutes', strtotime($firstEntryTime)); 

    $alertResultStmt = $db->prepare("SELECT COUNT(id) AS alerts FROM `water_data` WHERE level < 0 AND DATE(`created_at`) = CURDATE() AND created_at >= :three_minutes_ago LIMIT 50");
    $alertResultStmt->bindParam(':three_minutes_ago', $threeMinutesAgo);
    $alertResultStmt->execute();
    $alerts = $alertResultStmt->fetch(PDO::FETCH_ASSOC);
  
    $response = array(
        'low' => $result['low_threshold'],
        'high' => $result['high_threshold'],
        'alerts' => $alerts['alerts']
    );
    return $response;
}

function max_distance(){
    global $db;
    $stmt = $db->prepare("SELECT * FROM `water_tank` WHERE status = 'active'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}
