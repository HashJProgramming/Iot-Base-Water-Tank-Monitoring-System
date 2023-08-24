<?php
include_once 'connection.php';

$stmt = $db->prepare("SELECT * FROM `water_tank` WHERE name = :name");
$stmt->bindParam(':name', $_POST['name']);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    header('location: ../settings.php?type=error&message=Tank name already exists');
    exit();
}

$stmt = $db->prepare("INSERT INTO `water_tank` (`name`, `height`, `liters`) VALUES (:name, :height, :liters)");
$stmt->bindParam(':name', $_POST['name']);
$stmt->bindParam(':height', $_POST['height']);
$stmt->bindParam(':liters', $_POST['liters']);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
generate_logs('Add Tank','Tank '.$_POST['name'].' added');
header('location: ../settings.php?type=success&message=Tank successfully added');
exit();