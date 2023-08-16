<?php
include_once 'connection.php';
try {
    $high = (int) $_POST['high'];
    $low = (int) $_POST['low'];

    $stmt = $db->prepare("UPDATE `settings` SET `high_threshold` = :high, `low_threshold` = :low WHERE id = 1");
    $stmt->bindValue(':high', $high);
    $stmt->bindValue(':low', $low);
    $stmt->execute();
    generate_logs('Settings Update', '| Info was updated');
    header('Location: ../settings.php?type=success&message=Settings save successfully!');
} catch (\Throwable $th) {
    generate_logs('Settings Update'.'| Error: '.$th->getMessage());
}
