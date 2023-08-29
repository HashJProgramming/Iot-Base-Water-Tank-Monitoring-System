<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $database = 'wtms';
    $username = 'root';
    $password = 'hash';
    $host = '127.0.0.1';
    $db = new PDO('mysql:host=localhost', $username, $password);
    $query = "CREATE DATABASE IF NOT EXISTS $database";

    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec($query);
        $db->exec("USE $database");

        $db->exec("
            CREATE TABLE IF NOT EXISTS users (
              id INT PRIMARY KEY AUTO_INCREMENT,
              username VARCHAR(255),
              password VARCHAR(255),
              level VARCHAR(255),
              created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $db->exec("
            CREATE TABLE IF NOT EXISTS water_data (
              id INT PRIMARY KEY AUTO_INCREMENT,
              distance DOUBLE,
              level DOUBLE,
              liters DOUBLE,
              created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $db->exec("
            CREATE TABLE IF NOT EXISTS water_tank (
              id INT PRIMARY KEY AUTO_INCREMENT,
              name VARCHAR(255),
              height DOUBLE,
              liters DOUBLE,
              status VARCHAR(255),
              created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $db->exec("
            CREATE TABLE IF NOT EXISTS settings (
              id INT PRIMARY KEY AUTO_INCREMENT,
              high_threshold INT,
              low_threshold INT
            )
        ");
        
        $db->exec("
          CREATE TABLE IF NOT EXISTS logs (
            id INT PRIMARY KEY AUTO_INCREMENT,
            logs TEXT,
            type TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
        ");

        $db->exec("
          CREATE TABLE IF NOT EXISTS water_stats (
            id INT PRIMARY KEY AUTO_INCREMENT,
            distance DOUBLE,
            level DOUBLE,
            liters DOUBLE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
        ");

        $db->beginTransaction();

        $stmt = $db->prepare("SELECT COUNT(*) FROM `users` WHERE `username` = 'admin'");
        $stmt->execute();
        $userExists = $stmt->fetchColumn();
        
        if (!$userExists) {
            $stmt = $db->prepare("INSERT INTO `users` (`username`, `password`) VALUES (:username, :password)");
            $stmt->bindValue(':username', 'admin');
            $stmt->bindValue(':password', '$2y$10$WgL2d2fzi6IiGiTfXvdBluTLlMroU8zBtIcRut7SzOB6j9i/LbA4K');
            $stmt->execute();

            $stmt = $db->prepare("INSERT INTO `settings` (`high_threshold`, `low_threshold`) VALUES (:high, :low)");
            $stmt->bindValue(':high', 0);
            $stmt->bindValue(':low', 0);
            $stmt->execute();

            $stmt = $db->prepare("INSERT INTO `water_stats` (`distance`, `level`, `liters`) VALUES (:distance, :level, :liters)");
            $stmt->bindValue(':distance', 0);
            $stmt->bindValue(':level', 0);
            $stmt->bindValue(':liters', 0);
            $stmt->execute();
        }
        
        $db->commit();

    } catch(PDOException $e) {
        die("Error creating database: " . $e->getMessage());
    }
    
    $db = null;
?>