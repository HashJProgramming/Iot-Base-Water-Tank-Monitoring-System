<?php
include_once 'functions/connection.php';

function settings_data(){
    global $db;
    $stmt = $db->prepare("SELECT * FROM `settings` WHERE id = 1");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);


    $stmt = $db->prepare("SELECT COUNT(id) AS alerts FROM `water_data` WHERE level < 0 AND created_at >= NOW() - INTERVAL 1 HOUR");
    $stmt->execute();
    $alerts = $stmt->fetch(PDO::FETCH_ASSOC);
  
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

function count_water_data(){
    global $db;
    $stmt = $db->prepare("SELECT COUNT(id) as water_data_count FROM `water_data`");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['water_data_count'];
}