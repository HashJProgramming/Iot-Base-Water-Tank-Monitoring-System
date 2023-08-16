<?php
include_once 'setup.php';
global $database, $username, $password, $host;

try {
    $db = new PDO('mysql:host=' . $host . ';dbname=' . $database, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


function generate_logs($type, $logs)
{
    session_start();
    global $db;
    $sql = "INSERT INTO logs (logs, type) VALUES (:logs, :type)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':logs', $logs);
    $stmt->bindParam(':type', $type);
    $stmt->execute();
}
