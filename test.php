<?php
echo $_SERVER['SERVER_NAME'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
<script src="assets/js/jquery.min.js"></script>
<script>
        var x = true;
        setInterval(function() {
            fetch('http://<?php echo $_SERVER['SERVER_NAME']; ?>:5000/WTMS/check_sensor')
                .then(response => response.json()) 
                .then(data => {
                    $(".sensor-status").html(data.status);
                    if(data.status == "Running"){
                        $(".restart-btn").html("Restart");
                        console.log(data.status);
                    }else{
                        if(x){
                            fetch('http://<?php echo $_SERVER['SERVER_NAME']; ?>:5000/WTMS/start')
                            .then(response => response.json()) 
                            .then(data => {
                                $(".sensor-status").html(data.status);
                                console.log(data.message);
                        })
                        x = false;
                        }
                    }
                    
                })
                .catch(error => console.error('Error:', error));
        }, 1000);
        
</script>

<script>
    function formatBytes(bytes) {
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (bytes === 0) return '0 Byte';
        const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }
    
    if (currentPath.includes("/WTMS/settings.php")) {
            setInterval(function() {
                fetch('http://<?php echo $_SERVER['SERVER_NAME']; ?>:5000/WTMS/get_system_stats')
                    .then(response => response.json())
                    .then(data => {
                        $(".cpu-percent").html(data.cpu_percent + "%");
                        $(".disk-percent").html(data.disk_percent + "%");
                        $(".disk-usage").html(formatBytes(data.disk_used) + " / " + formatBytes(data.disk_total));
                        $(".memory-percent").html(data.memory_percent + "%");
                        $(".memory-usage").html(formatBytes(data.memory_used) + " / " + formatBytes(data.memory_total));
                    })
                    .catch(error => console.error('Error:', error));
            }, 1000);
        
            document.getElementById('restart').addEventListener('click', function() {
                // Perform the fetch request to the Flask API
                fetch('http://<?php echo $_SERVER['SERVER_NAME']; ?>:5000/WTMS/restart')
                    .then(response => response.json())
                    .then(data => {
                        // Display a message if needed
                        console.log(data.message);
                        // Perform the redirect
                        window.location.href = 'settings.php?type=success&message=System Sensor Restarted successfully!';
                    })
                    .catch(error => console.error('Error:', error));
            });
            
    }
</script>
</html>
