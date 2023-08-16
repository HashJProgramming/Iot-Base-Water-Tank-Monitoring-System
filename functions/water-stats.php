<?php 
include_once 'connection.php';

global $db;
$stmt = $db->prepare("SELECT * FROM `water_data` ORDER BY id DESC LIMIT 1");
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($result);