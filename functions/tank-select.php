<?php
include_once 'connection.php';

$waterTankId = $_GET['id'];
$newStatus = 'Not activated';
$stmt = $db->prepare("UPDATE `water_tank` SET `status` = :status WHERE status = 'Activated'");
$stmt->execute(array(':status' => $newStatus));

$newStatus = 'Activated';
$stmt = $db->prepare("UPDATE `water_tank` SET `status` = :status WHERE id = :id");
$stmt->execute(array(':status' => $newStatus, ':id' => $waterTankId));

generate_logs('Activate Tank','Tank ID'.$_GET['id'].' Activated');
header('location: ../settings.php?type=success&message=Tank successfully activated');
exit();
