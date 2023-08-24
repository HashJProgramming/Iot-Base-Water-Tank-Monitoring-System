<?php
include_once 'connection.php';

$stmt = $db->prepare("SELECT * FROM `water_tank` WHERE name = :name AND id != :id");
$stmt->bindParam(':name', $_POST['name']);
$stmt->bindParam(':id', $_POST['data_id']);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    header('location: ../settings.php?type=error&message=Tank name already taken');
    exit();
}

$stmt = $db->prepare("UPDATE `water_tank` SET `name` = :name, `height` = :height, `liters` = :liters WHERE id = :id");
$stmt->bindParam(':name', $_POST['name']);
$stmt->bindParam(':height', $_POST['height']);
$stmt->bindParam(':liters', $_POST['liters']);
$stmt->bindParam(':id', $_POST['data_id']);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

generate_logs('Update Tank','Tank '.$_POST['name'].' updated');
header('location: ../settings.php?type=success&message=Tank successfully updated');
exit();