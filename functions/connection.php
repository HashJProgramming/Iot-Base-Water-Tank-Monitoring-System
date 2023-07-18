<?php
include_once 'setup.php';
global $database, $username, $password, $host;

try {
    $db = new PDO('mysql:host=' . $host . ';dbname=' . $database, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
