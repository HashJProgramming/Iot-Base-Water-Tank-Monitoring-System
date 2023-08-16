<?php
session_start();
if (isset($_SESSION['username'])){
    header('Location: ./index.php');
}
?>
<!DOCTYPE html>
<html data-bs-theme="light" id="bg-animation" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Log in - WTMS</title>
    <meta name="description" content="IoT-Base Water Tank Monitoring System">
    <link rel="icon" type="image/png" sizes="128x128" href="assets/img/reservoir.png">
    <link rel="icon" type="image/png" sizes="128x128" href="assets/img/reservoir.png">
    <link rel="icon" type="image/png" sizes="128x128" href="assets/img/reservoir.png">
    <link rel="icon" type="image/png" sizes="128x128" href="assets/img/reservoir.png">
    <link rel="icon" type="image/png" sizes="128x128" href="assets/img/reservoir.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Nunito.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
</head>

<body id="content-wrapper">
    <nav class="navbar navbar-expand shadow mb-4 topbar static-top navbar-light">
        <div class="container-fluid"><a class="navbar-brand d-flex align-items-center" href="/"><span><img src="assets/img/reservoir.png" width="60em">&nbsp;WTMS</span></a><button class="navbar-toggler" data-bs-toggle="collapse"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button></div>
    </nav>
    <div class="container py-md-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow border-start-success py-2">
                    <div class="card-body">
                        <div class="row align-items-center no-gutters">
                            <div class="col me-2">
                                <div class="text-uppercase text-success fw-bold text-xs mb-1"><span>WATER LEVEL</span></div>
                                <div class="text-dark fw-bold h5 mb-0"><span class="fs-2">0%</span></div>
                            </div>
                            <div class="col-auto"><i class="fas fa-chart-pie fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow border-start-primary py-2">
                    <div class="card-body">
                        <div class="row align-items-center no-gutters">
                            <div class="col me-2">
                                <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>TODAY Liters</span></div>
                                <div class="text-dark fw-bold h5 mb-0"><span class="fs-2">0L</span></div>
                            </div>
                            <div class="col-auto"><i class="fas fa-level-down-alt fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-3">
            <div class="col-md-5 col-xl-4 text-center text-md-start shadow border rounded-4" data-bss-hover-animate="pulse">
                <div class="mb-4"></div>
                <h2 class="display-6 fw-bold text-center mb-5"><span class="text-primary underline pb-1"><strong>Login</strong></span></h2>
                <div class="row">
                    <div class="col text-center"><img class="rounded-circle w-80 shadow mb-4" src="assets/img/water-filter.png" width="100em"></div>
                </div>
                <form action="functions/login.php" method="post" data-bs-theme="light">
                    <div class="mb-3"><input class="form-control shadow" type="text" name="username" placeholder="Username"></div>
                    <div class="mb-3"><input class="shadow form-control" type="password" name="password" placeholder="Password"></div>
                    <div class="text-center mb-5"><button class="btn btn-primary shadow" type="submit">Log in</button></div>
                </form>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap5.min.js"></script>
    <script src="assets/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/jszip.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/three.min.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/buttons.html5.min.js"></script>
    <script src="assets/js/buttons.print.min.js"></script>
    <script src="assets/js/vanta.birds.min.js"></script>
    <script src="assets/js/vanta.waves.min.js"></script>
    <script src="assets/js/sweetalert2.all.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>