<?php
include_once 'connection.php';

function tank_list(){
    global $db;
    $sql = 'SELECT * FROM water_tank ORDER BY name ASC';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll();

    foreach ($results as $row) {
        if($row['status'] == 'Activated'){
            $status = 'success';
        }else{
            $status = 'danger';
        }
        ?>
            <tr>
                <td><img class="rounded-circle me-2" width="30" height="30" src="assets/img/reservoir.png"><?php echo $row['name']; ?></td>
                <td><?php echo $row['height']; ?></td>
                <td><?php echo $row['liters']; ?></td>
                <td class="text-<?php echo $status; ?>"><?php echo $row['status']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td class="text-center">
                    <a data-bs-toggle="tooltip" data-bss-tooltip="" class="mx-1" href="functions/tank-select.php?id=<?php echo $row['id']; ?>" title="Here you  can select tank to use in water monitoring." data-id="<?php echo $row['id']; ?>"><i class="far fa-check-circle text-primary" style="font-size: 20px;" title="Here you  can select Tank to use."></i></a>
                    <a data-bs-toggle="modal" data-bss-tooltip="" class="mx-1" href="#" data-bs-target="#update" title="Here you can update the tank info." data-id="<?php echo $row['id']; ?>" data-name="<?php echo $row['name']; ?>" data-height="<?php echo $row['height']; ?>" data-liters="<?php echo $row['liters']; ?>"><i class="far fa-edit text-warning" data-bs-toggle="tooltip" data-bss-tooltip="" style="font-size: 20px;"></i></a>
                    <a data-bs-toggle="modal" data-bss-tooltip="" class="mx-1" href="#" data-bs-target="#remove" title="Here you can remove the tank." data-id="<?php echo $row['id']; ?>"><i class="far fa-trash-alt text-danger" style="font-size: 20px;"></i></a></td>
            </tr>
        <?php
    }
}

function water_data_list(){
    global $db;
    $sql = 'SELECT * FROM water_data ORDER BY id DESC LIMIT 5000';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll();

    foreach ($results as $row) {
        ?>
            <tr>
                <td><img class="rounded-circle me-2" width="30" height="30" src="assets/img/reservoir.png"><?php echo $row['id']; ?></td>
                <td><?php echo $row['level']; ?></td>
                <td><?php echo $row['liters']; ?></td>
                <td><?php echo $row['distance']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
        <?php
    }
}

function database_stats(){
    global $db;
    $mysqlVersionStmt = $db->query("SELECT VERSION() AS mysql_version");
    $mysqlVersion = $mysqlVersionStmt->fetchColumn();

    $mysqlStatsStmt = $db->query("SHOW GLOBAL STATUS");
    $mysqlStats = $mysqlStatsStmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $databaseName = 'wtms';
    $databaseSizeStmt = $db->query("SELECT table_schema, SUM(data_length + index_length) AS size FROM information_schema.TABLES WHERE table_schema = '$databaseName' GROUP BY table_schema");
    $databaseSize = $databaseSizeStmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <table class="table database-stats">
        <thead>
            <tr>
                <th colspan="3">MySQL Information</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>MySQL Version</td>
                <td>:</td>
                <td><?php echo $mysqlVersion; ?></td>
            </tr>
        </tbody>
    </table>

    <table class="table database-stats">
        <thead>
            <tr>
                <th colspan="3">Database Storage</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Database Name</td>
                <td>:</td>
                <td><?php echo $databaseSize['table_schema']; ?></td>
            </tr>
            <tr>
                <td>Database Size</td>
                <td>:</td>
                <td><?php echo round($databaseSize['size'] / (1024 * 1024), 2); ?> MB</td>
            </tr>
        </tbody>
    </table>
    <?php
}