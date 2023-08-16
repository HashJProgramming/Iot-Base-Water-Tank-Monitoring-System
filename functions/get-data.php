<?php
include_once 'functions/connection.php';

function settings_data(){
    global $db;
    $stmt = $db->prepare("SELECT * FROM `settings` WHERE id = 1");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function max_distance(){
    global $db;
    $stmt = $db->prepare("SELECT * FROM `water_tank` WHERE status = 'active'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}