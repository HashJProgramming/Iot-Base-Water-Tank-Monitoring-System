<?php
include_once 'connection.php';


function current_usage(){
    global $db; 

    $sql = "SELECT HOUR(created_at) AS hour, MINUTE(created_at) DIV 10 * 10 AS minute_interval, 
        AVG(liters) AS avg_liters, AVG(level) AS avg_level, AVG(distance) AS avg_distance
        FROM water_data
        WHERE DATE(created_at) = CURDATE()
        GROUP BY HOUR(created_at), MINUTE(created_at) DIV 10
        ORDER BY HOUR(created_at), MINUTE(created_at) DIV 10";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    $labels = [];
    $litersData = [];   
    $levelData = [];   
    $distanceData = []; 

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $timeLabel = sprintf("%02d:%02d", $row['hour'], $row['minute_interval']);
        $labels[] = $timeLabel;
        $litersData[] = $row['avg_liters'];
        $levelData[] = $row['avg_level'];
        $distanceData[] = $row['avg_distance'];
    }
    $chartData = [
    'labels' => $labels,
    'datasets' => [
        [
            'label' => 'Avg Liters',
            'fill' => true,
            'data' => $litersData,
            'backgroundColor' => 'rgba(0, 123, 255, 0.1)', 
            'borderColor' => 'rgba(0, 123, 255, 1)',      
        ],
        [
            'label' => 'Avg Level',
            'fill' => true,
            'data' => $levelData,
            'backgroundColor' => 'rgba(40, 167, 69, 0.1)', 
            'borderColor' => 'rgba(40, 167, 69, 1)',      
        ],
        [
            'label' => 'Avg Distance',
            'fill' => true,
            'data' => $distanceData,
            'backgroundColor' => 'rgba(255, 193, 7, 0.1)', 
            'borderColor' => 'rgba(255, 193, 7, 1)',      
        ]
    ]
    ];

    $chartDataJson = json_encode($chartData);
    ?>
    
    <canvas data-bss-chart='{"type":"line","data":<?php echo $chartDataJson; ?>,"options":{"maintainAspectRatio":false,"legend":{"display":false,"labels":{"fontStyle":"normal"}},"title":{"fontStyle":"normal"},"scales":{"xAxes":[{"gridLines":{"color":"rgb(234, 236, 244)","zeroLineColor":"rgb(234, 236, 244)","drawBorder":false,"drawTicks":false,"borderDash":["2"],"zeroLineBorderDash":["2"],"drawOnChartArea":false},"ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}],"yAxes":[{"gridLines":{"color":"rgb(234, 236, 244)","zeroLineColor":"rgb(234, 236, 244)","drawBorder":false,"drawTicks":false,"borderDash":["2"],"zeroLineBorderDash":["2"]},"ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}]}}}'></canvas>
    <?php
}

function today_usage(){
    global $db; 
    $currentDate = date('Y-m-d');
    $sql = "SELECT HOUR(created_at) AS hour, AVG(liters) AS total_liters, AVG(level) AS total_level, AVG(distance) AS avg_distance
            FROM water_data
            WHERE DATE(created_at) = :date
            GROUP BY HOUR(created_at)
            ORDER BY HOUR(created_at)";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':date', $currentDate);
    $stmt->execute();

    $labels = [];
    $litersData = [];   
    $levelData = [];   
    $distanceData = []; 

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $hour = $row['hour'];
        $timeLabel = date('g:iA', strtotime("$hour:00:00"));
        $labels[] = $timeLabel; 
        $litersData[] = $row['total_liters'];
        $levelData[] = $row['total_level'];
        $distanceData[] = $row['avg_distance'];
    }

    $chartData = [
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Liters',
                'fill' => true,
                'data' => $litersData,
                'backgroundColor' => 'rgba(0, 123, 255, 0.1)', 
                'borderColor' => 'rgba(0, 123, 255, 1)',      
            ],
            [
                'label' => 'Level',
                'fill' => true,
                'data' => $levelData,
                'backgroundColor' => 'rgba(40, 167, 69, 0.1)', 
                'borderColor' => 'rgba(40, 167, 69, 1)',      
            ],
            [
                'label' => 'Distance',
                'fill' => true,
                'data' => $distanceData,
                'backgroundColor' => 'rgba(255, 193, 7, 0.1)', 
                'borderColor' => 'rgba(255, 193, 7, 1)',      
            ]
        ]
    ];

    $chartDataJson = json_encode($chartData);
    ?>
    <canvas data-bss-chart='{"type":"line","data":<?php echo $chartDataJson; ?>,"options":{"maintainAspectRatio":false,"legend":{"display":false,"labels":{"fontStyle":"normal"}},"title":{"fontStyle":"normal"},"scales":{"xAxes":[{"gridLines":{"color":"rgb(234, 236, 244)","zeroLineColor":"rgb(234, 236, 244)","drawBorder":false,"drawTicks":false,"borderDash":["2"],"zeroLineBorderDash":["2"],"drawOnChartArea":false},"ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}],"yAxes":[{"gridLines":{"color":"rgb(234, 236, 244)","zeroLineColor":"rgb(234, 236, 244)","drawBorder":false,"drawTicks":false,"borderDash":["2"],"zeroLineBorderDash":["2"]},"ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}]}}}'></canvas>
    <?php
}

function monthly_usage(){
    global $db; 

    $sql = "SELECT MONTH(created_at) AS month, YEAR(created_at) AS year, AVG(liters) AS avg_liters, AVG(level) AS avg_level, AVG(distance) AS avg_distance
            FROM water_data
            GROUP BY MONTH(created_at), YEAR(created_at)
            ORDER BY YEAR(created_at), MONTH(created_at)";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    $labels = [];
    $litersData = [];   
    $levelData = [];    
    $distanceData = []; 

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $monthName = date("M", mktime(0, 0, 0, $row['month'], 10));
        $labels[] = $monthName . ' ' . $row['year'];
        $litersData[] = $row['avg_liters'];
        $levelData[] = $row['avg_level'];
        $distanceData[] = $row['avg_distance'];
    }

    $chartData = [
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Avg Liters',
                'fill' => true,
                'data' => $litersData,
                'backgroundColor' => 'rgba(0, 123, 255, 0.1)', 
                'borderColor' => 'rgba(0, 123, 255, 1)',      
            ],
            [
                'label' => 'Avg Level',
                'fill' => true,
                'data' => $levelData,
                'backgroundColor' => 'rgba(40, 167, 69, 0.1)', 
                'borderColor' => 'rgba(40, 167, 69, 1)',      
            ],
            [
                'label' => 'Avg Distance',
                'fill' => true,
                'data' => $distanceData,
                'backgroundColor' => 'rgba(255, 193, 7, 0.1)', 
                'borderColor' => 'rgba(255, 193, 7, 1)',      
            ]
        ]
    ];

    $chartDataJson = json_encode($chartData);
    ?>
    
    <canvas data-bss-chart='{"type":"line","data":<?php echo $chartDataJson; ?>,"options":{"maintainAspectRatio":false,"legend":{"display":false,"labels":{"fontStyle":"normal"}},"title":{"fontStyle":"normal"},"scales":{"xAxes":[{"gridLines":{"color":"rgb(234, 236, 244)","zeroLineColor":"rgb(234, 236, 244)","drawBorder":false,"drawTicks":false,"borderDash":["2"],"zeroLineBorderDash":["2"],"drawOnChartArea":false},"ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}],"yAxes":[{"gridLines":{"color":"rgb(234, 236, 244)","zeroLineColor":"rgb(234, 236, 244)","drawBorder":false,"drawTicks":false,"borderDash":["2"],"zeroLineBorderDash":["2"]},"ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}]}}}'></canvas>
    <?php
}

function yearly_usage(){
    global $db; 

    $sql = "SELECT YEAR(created_at) AS year, AVG(liters) AS avg_liters, AVG(level) AS avg_level, AVG(distance) AS avg_distance
            FROM water_data
            GROUP BY YEAR(created_at)
            ORDER BY YEAR(created_at)";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    $labels = [];
    $litersData = [];   
    $levelData = [];   
    $distanceData = []; 

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $year = $row['year'];
        $labels[] = $year;
        $litersData[] = $row['avg_liters'];
        $levelData[] = $row['avg_level'];
        $distanceData[] = $row['avg_distance'];
    }

    $chartData = [
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Avg Liters',
                'fill' => true,
                'data' => $litersData,
                'backgroundColor' => 'rgba(0, 123, 255, 0.1)', // Change to blue color
                'borderColor' => 'rgba(0, 123, 255, 1)',      // Change to blue color
            ],
            [
                'label' => 'Avg Level',
                'fill' => true,
                'data' => $levelData,
                'backgroundColor' => 'rgba(40, 167, 69, 0.1)', // Change to green color
                'borderColor' => 'rgba(40, 167, 69, 1)',      // Change to green color
            ],
            [
                'label' => 'Avg Distance',
                'fill' => true,
                'data' => $distanceData,
                'backgroundColor' => 'rgba(255, 193, 7, 0.1)', // Change to yellow color
                'borderColor' => 'rgba(255, 193, 7, 1)',      // Change to yellow color
            ]
        ]
    ];

    $chartDataJson = json_encode($chartData);
    ?>
    <canvas data-bss-chart='{"type":"line","data":<?php echo $chartDataJson; ?>,"options":{"maintainAspectRatio":false,"legend":{"display":false,"labels":{"fontStyle":"normal"}},"title":{"fontStyle":"normal"},"scales":{"xAxes":[{"gridLines":{"color":"rgb(234, 236, 244)","zeroLineColor":"rgb(234, 236, 244)","drawBorder":false,"drawTicks":false,"borderDash":["2"],"zeroLineBorderDash":["2"],"drawOnChartArea":false},"ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}],"yAxes":[{"gridLines":{"color":"rgb(234, 236, 244)","zeroLineColor":"rgb(234, 236, 244)","drawBorder":false,"drawTicks":false,"borderDash":["2"],"zeroLineBorderDash":["2"]},"ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}]}}}'></canvas>
    <?php
}

function current_usage_polar(){
    global $db; 

    $sql = "SELECT HOUR(created_at) AS hour, MINUTE(created_at) DIV 10 * 10 AS minute_interval, 
        AVG(liters) AS avg_liters, AVG(level) AS avg_level, AVG(distance) AS avg_distance
        FROM water_data
        WHERE DATE(created_at) = CURDATE()
        GROUP BY HOUR(created_at), MINUTE(created_at) DIV 10
        ORDER BY HOUR(created_at), MINUTE(created_at) DIV 10";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    $labels = [];
    $litersData = [];   
    $levelData = [];   
    $distanceData = []; 

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $timeLabel = sprintf("%02d:%02d", $row['hour'], $row['minute_interval']);
        $labels[] = $timeLabel;
        $litersData[] = $row['avg_liters'];
        $levelData[] = $row['avg_level'];
        $distanceData[] = $row['avg_distance'];
    }
    $chartData = [
    'labels' => $labels,
    'datasets' => [
        [
            'label' => 'Avg Liters',
            'fill' => true,
            'data' => $litersData,
            'backgroundColor' => 'rgba(0, 123, 255, 0.1)', 
            'borderColor' => 'rgba(0, 123, 255, 1)',      
        ],
        [
            'label' => 'Avg Level',
            'fill' => true,
            'data' => $levelData,
            'backgroundColor' => 'rgba(40, 167, 69, 0.1)', 
            'borderColor' => 'rgba(40, 167, 69, 1)',      
        ],
        [
            'label' => 'Avg Distance',
            'fill' => true,
            'data' => $distanceData,
            'backgroundColor' => 'rgba(255, 193, 7, 0.1)', 
            'borderColor' => 'rgba(255, 193, 7, 1)',      
        ]
    ]
    ];

    $chartDataJson = json_encode($chartData);
    ?>
    
    <canvas data-bss-chart='{"type":"radar","data":<?php echo $chartDataJson; ?>,"options":{"maintainAspectRatio":false,"legend":{"display":false,"labels":{"fontStyle":"normal"}},"title":{"fontStyle":"normal"},"scales":{"xAxes":[{"gridLines":{"color":"rgb(234, 236, 244)","zeroLineColor":"rgb(234, 236, 244)","drawBorder":false,"drawTicks":false,"borderDash":["2"],"zeroLineBorderDash":["2"],"drawOnChartArea":false},"ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}],"yAxes":[{"gridLines":{"color":"rgb(234, 236, 244)","zeroLineColor":"rgb(234, 236, 244)","drawBorder":false,"drawTicks":false,"borderDash":["2"],"zeroLineBorderDash":["2"]},"ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}]}}}'></canvas>
    <?php
}

function today_usage_polar(){
    global $db; 
    $currentDate = date('Y-m-d');
    $sql = "SELECT HOUR(created_at) AS hour, AVG(liters) AS total_liters, AVG(level) AS total_level, AVG(distance) AS avg_distance
            FROM water_data
            WHERE DATE(created_at) = :date
            GROUP BY HOUR(created_at)
            ORDER BY HOUR(created_at)";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':date', $currentDate);
    $stmt->execute();

    $labels = [];
    $litersData = [];   
    $levelData = [];   
    $distanceData = []; 

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $hour = $row['hour'];
        $timeLabel = date('g:iA', strtotime("$hour:00:00"));
        $labels[] = $timeLabel; 
        $litersData[] = $row['total_liters'];
        $levelData[] = $row['total_level'];
        $distanceData[] = $row['avg_distance'];
    }

    $chartData = [
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Liters',
                'fill' => true,
                'data' => $litersData,
                'backgroundColor' => 'rgba(0, 123, 255, 0.1)', 
                'borderColor' => 'rgba(0, 123, 255, 1)',      
            ],
            [
                'label' => 'Level',
                'fill' => true,
                'data' => $levelData,
                'backgroundColor' => 'rgba(40, 167, 69, 0.1)', 
                'borderColor' => 'rgba(40, 167, 69, 1)',      
            ],
            [
                'label' => 'Distance',
                'fill' => true,
                'data' => $distanceData,
                'backgroundColor' => 'rgba(255, 193, 7, 0.1)', 
                'borderColor' => 'rgba(255, 193, 7, 1)',      
            ]
        ]
    ];

    $chartDataJson = json_encode($chartData);
    ?>
    <canvas data-bss-chart='{"type":"polarArea","data":<?php echo $chartDataJson; ?>,"options":{"maintainAspectRatio":false,"legend":{"display":false,"labels":{"fontStyle":"normal"}},"title":{"fontStyle":"normal"},"scales":{"xAxes":[{"gridLines":{"color":"rgb(234, 236, 244)","zeroLineColor":"rgb(234, 236, 244)","drawBorder":false,"drawTicks":false,"borderDash":["2"],"zeroLineBorderDash":["2"],"drawOnChartArea":false},"ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}],"yAxes":[{"gridLines":{"color":"rgb(234, 236, 244)","zeroLineColor":"rgb(234, 236, 244)","drawBorder":false,"drawTicks":false,"borderDash":["2"],"zeroLineBorderDash":["2"]},"ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}]}}}'></canvas>
    <?php
}

function monthly_usage_polar(){
    global $db; 

    $sql = "SELECT MONTH(created_at) AS month, YEAR(created_at) AS year, AVG(liters) AS avg_liters, AVG(level) AS avg_level, AVG(distance) AS avg_distance
            FROM water_data
            GROUP BY MONTH(created_at), YEAR(created_at)
            ORDER BY YEAR(created_at), MONTH(created_at)";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    $labels = [];
    $litersData = [];   
    $levelData = [];    
    $distanceData = []; 

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $monthName = date("M", mktime(0, 0, 0, $row['month'], 10));
        $labels[] = $monthName . ' ' . $row['year'];
        $litersData[] = $row['avg_liters'];
        $levelData[] = $row['avg_level'];
        $distanceData[] = $row['avg_distance'];
    }

    $chartData = [
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Avg Liters',
                'fill' => true,
                'data' => $litersData,
                'backgroundColor' => 'rgba(0, 123, 255, 0.1)', 
                'borderColor' => 'rgba(0, 123, 255, 1)',      
            ],
            [
                'label' => 'Avg Level',
                'fill' => true,
                'data' => $levelData,
                'backgroundColor' => 'rgba(40, 167, 69, 0.1)', 
                'borderColor' => 'rgba(40, 167, 69, 1)',      
            ],
            [
                'label' => 'Avg Distance',
                'fill' => true,
                'data' => $distanceData,
                'backgroundColor' => 'rgba(255, 193, 7, 0.1)', 
                'borderColor' => 'rgba(255, 193, 7, 1)',      
            ]
        ]
    ];

    $chartDataJson = json_encode($chartData);
    ?>
    
    <canvas data-bss-chart='{"type":"bar","data":<?php echo $chartDataJson; ?>,"options":{"maintainAspectRatio":false,"legend":{"display":false,"labels":{"fontStyle":"normal"}},"title":{"fontStyle":"normal"},"scales":{"xAxes":[{"gridLines":{"color":"rgb(234, 236, 244)","zeroLineColor":"rgb(234, 236, 244)","drawBorder":false,"drawTicks":false,"borderDash":["2"],"zeroLineBorderDash":["2"],"drawOnChartArea":false},"ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}],"yAxes":[{"gridLines":{"color":"rgb(234, 236, 244)","zeroLineColor":"rgb(234, 236, 244)","drawBorder":false,"drawTicks":false,"borderDash":["2"],"zeroLineBorderDash":["2"]},"ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}]}}}'></canvas>
    <?php
}

function yearly_usage_polar(){
    global $db; 

    $sql = "SELECT YEAR(created_at) AS year, AVG(liters) AS avg_liters, AVG(level) AS avg_level, AVG(distance) AS avg_distance
            FROM water_data
            GROUP BY YEAR(created_at)
            ORDER BY YEAR(created_at)";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    $labels = [];
    $litersData = [];   
    $levelData = [];   
    $distanceData = []; 

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $year = $row['year'];
        $labels[] = $year;
        $litersData[] = $row['avg_liters'];
        $levelData[] = $row['avg_level'];
        $distanceData[] = $row['avg_distance'];
    }

    $chartData = [
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Avg Liters',
                'fill' => true,
                'data' => $litersData,
                'backgroundColor' => 'rgba(0, 123, 255, 0.1)', 
                'borderColor' => 'rgba(0, 123, 255, 1)',      
            ],
            [
                'label' => 'Avg Level',
                'fill' => true,
                'data' => $levelData,
                'backgroundColor' => 'rgba(40, 167, 69, 0.1)', 
                'borderColor' => 'rgba(40, 167, 69, 1)',      
            ],
            [
                'label' => 'Avg Distance',
                'fill' => true,
                'data' => $distanceData,
                'backgroundColor' => 'rgba(255, 193, 7, 0.1)', 
                'borderColor' => 'rgba(255, 193, 7, 1)',      
            ]
        ]
    ];

    $chartDataJson = json_encode($chartData);
    ?>
    <canvas data-bss-chart='{"type":"bar","data":<?php echo $chartDataJson; ?>,"options":{"maintainAspectRatio":false,"legend":{"display":false,"labels":{"fontStyle":"normal"}},"title":{"fontStyle":"normal"},"scales":{"xAxes":[{"gridLines":{"color":"rgb(234, 236, 244)","zeroLineColor":"rgb(234, 236, 244)","drawBorder":false,"drawTicks":false,"borderDash":["2"],"zeroLineBorderDash":["2"],"drawOnChartArea":false},"ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}],"yAxes":[{"gridLines":{"color":"rgb(234, 236, 244)","zeroLineColor":"rgb(234, 236, 244)","drawBorder":false,"drawTicks":false,"borderDash":["2"],"zeroLineBorderDash":["2"]},"ticks":{"fontColor":"#858796","fontStyle":"normal","padding":20}}]}}}'></canvas>
    <?php
}
