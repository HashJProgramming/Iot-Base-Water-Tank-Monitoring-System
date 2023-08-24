<?php
include_once 'connection.php';

$stmt = $db->prepare("DELETE FROM `water_tank` WHERE id = :id");
$stmt->bindParam(':id', $_POST['data_id']);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

generate_logs('Remove Tank','Tank ID'.$_POST['data_id'].' removed');
header('location: ../settings.php?type=success&message=Tank has been removed');