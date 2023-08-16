<?php
include_once 'functions/authentication.php';
include_once 'functions/header.php';
include_once 'functions/get-data.php';
?>
<!DOCTYPE html>
<html data-bs-theme="light" id="bg-animation" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Settings - WTMS</title>
    <meta name="description" content="IoT-Base Water Tank Monitoring System">
    <script>
        (function() {

            // JavaScript snippet handling Dark/Light mode switching

            const getStoredTheme = () => localStorage.getItem('theme');
            const setStoredTheme = theme => localStorage.setItem('theme', theme);
            const forcedTheme = document.documentElement.getAttribute('data-bss-forced-theme');

            const getPreferredTheme = () => {

                if (forcedTheme) return forcedTheme;

                const storedTheme = getStoredTheme();
                if (storedTheme) {
                    return storedTheme;
                }

                const pageTheme = document.documentElement.getAttribute('data-bs-theme');

                if (pageTheme) {
                    return pageTheme;
                }

                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            const setTheme = theme => {
                if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.setAttribute('data-bs-theme', 'dark');
                } else {
                    document.documentElement.setAttribute('data-bs-theme', theme);
                }
            }

            setTheme(getPreferredTheme());

            const showActiveTheme = (theme, focus = false) => {
                const themeSwitchers = [].slice.call(document.querySelectorAll('.theme-switcher'));

                if (!themeSwitchers.length) return;

                document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
                    element.classList.remove('active');
                    element.setAttribute('aria-pressed', 'false');
                });

                for (const themeSwitcher of themeSwitchers) {

                    const btnToActivate = themeSwitcher.querySelector('[data-bs-theme-value="' + theme + '"]');

                    if (btnToActivate) {
                        btnToActivate.classList.add('active');
                        btnToActivate.setAttribute('aria-pressed', 'true');
                    }
                }
            }

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                const storedTheme = getStoredTheme();
                if (storedTheme !== 'light' && storedTheme !== 'dark') {
                    setTheme(getPreferredTheme());
                }
            });

            window.addEventListener('DOMContentLoaded', () => {
                showActiveTheme(getPreferredTheme());

                document.querySelectorAll('[data-bs-theme-value]')
                    .forEach(toggle => {
                        toggle.addEventListener('click', (e) => {
                            e.preventDefault();
                            const theme = toggle.getAttribute('data-bs-theme-value');
                            setStoredTheme(theme);
                            setTheme(theme);
                            showActiveTheme(theme);
                        })
                    })
            });
        })();
    </script>
    <link rel="icon" type="image/png" sizes="128x128" href="assets/img/reservoir.png">
    <link rel="icon" type="image/png" sizes="128x128" href="assets/img/reservoir.png">
    <link rel="icon" type="image/png" sizes="128x128" href="assets/img/reservoir.png">
    <link rel="icon" type="image/png" sizes="128x128" href="assets/img/reservoir.png">
    <link rel="icon" type="image/png" sizes="128x128" href="assets/img/reservoir.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Nunito.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
</head>

<body id="page-top">
    <?php
        nav();
    ?>
    <div id="content">
        <div class="container-fluid">
            <div class="d-sm-flex justify-content-between align-items-center mb-4">
                <h3 class="text-dark mb-0">Settings</h3>
                <button class="btn btn-primary btn-sm float-end d-sm-inline-block" data-bs-toggle="modal" data-bss-tooltip="" data-bs-placement="left" type="button" data-bs-target="#change" title="Here you can change your account password."><i class="fas fa-user-check fa-sm text-white-50"></i>&nbsp;Change Password</button>
            </div>
            <div class="row">
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-success py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-success fw-bold text-xs mb-1"><span>Time</span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span class="fs-2 hours">00</span><span class="fs-2">:</span><span class="fs-2 min">00</span><span class="fs-2">:</span><span class="fs-2 sec">00</span><span class="fs-2 ampm">0</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-water fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-success py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-success fw-bold text-xs mb-1"><span>ALERT HIGH (cm)</span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span class="fs-2 rangehigh"><?php echo settings_data()['high_threshold']?></span><span class="fs-2">cm</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-level-up-alt fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-primary py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>ALERT LOW (cm)</span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span class="fs-2 rangelow"><?php echo settings_data()['low_threshold']?></span><span class="fs-2">cm</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-level-down-alt fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-warning py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-danger fw-bold text-xs mb-1"><span>Sensor Status</span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span class="fs-2 sensor-status">0</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-cogs fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-primary py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>WATER LEVEL</span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span class="fs-2 water-level">0%</span></div>
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
                                    <div class="text-dark fw-bold h5 mb-0"><span class="fs-2 water-liters">0L</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-chart-pie fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row row-cols-sm-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-2 row-cols-xxl-2">
                <div class="col">
                    <div class="card shadow my-5">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 fw-bold">Alert Settings</p>
                        </div>
                        <div class="card-body">
                            <form class="text-primary" method="post" action="functions/save_settings.php">
                                <div class="my-1 mb-4"><label class="form-label">Water level high threshold (cm)</label><input class="form-range" type="range" data-bs-toggle="tooltip" data-bss-tooltip="" title="Here you can adjust the water level alert threshold" name="high" onchange="rangehigh(this.value)" value="<?php echo settings_data()['high_threshold']?>" min="<?php echo settings_data()['low_threshold']?>" max="<?php echo max_distance()['height']?>"></div>
                                <div class="my-1 mb-4"><label class="form-label">Water level low threshold (cm)</label><input class="form-range" type="range" data-bs-toggle="tooltip" data-bss-tooltip="" title="Here you can adjust the water level alert threshold" name="low" onchange="rangelow(this.value)" value="<?php echo settings_data()['low_threshold']?>"></div>
                                <div class="text-center my-1"><button class="btn btn-primary" type="submit">Save</button></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow my-5">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 fw-bold">Raspberry Pi Performance</p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table system-stats">
                                <thead>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>CPU</td>
                                        <td class="cpu-percent"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Disk</td>
                                        <td class="disk-percent"></td>
                                        <td class="disk-usage"></td>
                                    </tr>
                                    <tr>
                                        <td>Memory</td>
                                        <td class="memory-percent"></td>
                                        <td class="memory-usage"></td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow my-5">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 fw-bold">Data Settings</p>
                        </div>
                        <div class="card-body">
                            <div class="my-1"><label class="form-label">System Rest - This action will result in the removal of all data, including water-data information from the system.</label></div>
                            <div class="text-center my-1"><button class="btn btn-danger" type="button" data-bs-target="#reset" data-bs-toggle="modal">System Reset</button></div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow my-5">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 fw-bold">Sensor Status</p>
                        </div>
                        <div class="card-body">
                            <div class="my-1"><label class="form-label">Ultrasonic Sensor (<span class="sensor-status">Waiting. . .</span>)</label></div>
                            <div class="text-center my-1"><button class="btn btn-danger restart-btn" type="button" data-bs-target="#sensor" data-bs-toggle="modal">Restart</button></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-sm-flex justify-content-between align-items-center mb-4">
                <h3 class="text-dark mb-0">User Activity Logs</h3>
                <a class="btn btn-warning btn-sm float-end d-sm-inline-block" href="/phpmyadmin"><i class="fas fa-user-check fa-sm text-white-50"></i>&nbsp;phpMyAdmin</a>
            </div>
            <div class="card shadow my-5">
                <div class="card-header py-3">
                    <p class="text-primary m-0 fw-bold">User Logs</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
                        <table class="table table-hover my-0" id="dataTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Process</th>
                                    <th>Status</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>0</td>
                                    <td>Login</td>
                                    <td>Success</td>
                                    <td>Granted</td>
                                    <td>2008/11/28</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Add Tank</td>
                                    <td>Success</td>
                                    <td>Tank Saved</td>
                                    <td>2009/10/09</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    <div class="modal fade" role="dialog" tabindex="-1" id="change">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change Password</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="my-1"><label class="form-label">Current Password</label><input class="form-control" type="text" required="" pattern="^(?!\s).*$"></div>
                        <div class="my-1"><label class="form-label">New Password</label><input class="form-control" type="text" pattern="^(?!\s).*$" required=""></div>
                    </form>
                </div>
                <div class="modal-footer"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button><button class="btn btn-primary" type="button">Save</button></div>
            </div>
        </div>
    </div>
    <div class="modal fade" role="dialog" tabindex="-1" id="sensor">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Sensor Restart</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to interrupt the system sensor?</p>
                </div>
                <div class="modal-footer"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button><button id="restart" class="btn btn-danger restart-btn" type="button">Restart</button></div>
            </div>
        </div>
    </div>
    <div class="modal fade" role="dialog" tabindex="-1" id="reset">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Data Settings</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to system reset?</p>
                </div>
                <div class="modal-footer"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button><button class="btn btn-danger" type="button">Reset</button></div>
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