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
    <nav class="navbar navbar-expand-lg border rounded shadow mb-4 navbar-light">
        <div class="container-fluid"><img src="assets/img/reservoir.png" width="45em"><a class="navbar-brand d-flex align-items-center" href="/"><span>&nbsp;RMMFB</span></a><button data-bs-toggle="collapse" data-bs-target="#navcol-1" class="navbar-toggler"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <div class="theme-switcher dropdown"><a class="dropdown-toggle" aria-expanded="false" data-bs-toggle="dropdown" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-sun-fill mb-1">
                            <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"></path>
                        </svg></a>
                    <div class="dropdown-menu"><a class="dropdown-item d-flex align-items-center" href="#" data-bs-theme-value="light"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-sun-fill opacity-50 me-2">
                                <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"></path>
                            </svg>Light</a><a class="dropdown-item d-flex align-items-center" href="#" data-bs-theme-value="dark"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-moon-stars-fill opacity-50 me-2">
                                <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"></path>
                                <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"></path>
                            </svg>Dark</a><a class="dropdown-item d-flex align-items-center" href="#" data-bs-theme-value="auto"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-circle-half opacity-50 me-2">
                                <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"></path>
                            </svg>Auto</a></div>
                </div>
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link link-primary" data-bs-toggle="tooltip" data-bss-tooltip="" data-bs-original-title="Here you can see your Dashboard." data-bs-placement="bottom" href="index.php" style="color:#393939;" title="Here you can see your Dashboard."> <i class="fas fa-home"></i>&nbsp;Home</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tooltip" data-bss-tooltip="" data-bs-original-title="Here you can see your Sales &amp; Transactions." data-bs-placement="bottom" style="color:#393939;" title="Here you can see your water data. (Water Level)" href="water-data.php"><i class="fas fa-chart-pie"></i>&nbsp;Water Data</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tooltip" data-bss-tooltip="" data-bs-original-title="Here you can Monitor the rental transactions." data-bs-placement="bottom" style="color:#393939;" title="Here you can maange you water tank to use." href="tank-management.php"><i class="fas fa-cog"></i>&nbsp;Manage Tank</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tooltip" data-bss-tooltip="" data-bs-original-title="Here you can manage your account." data-bs-placement="bottom" style="color:#393939;" title="Here you can configure your water sensor." href="settings.php"><i class="fas fa-cogs"></i>&nbsp;Settings</a></li>
                </ul><a class="btn btn-light shadow" role="button" data-bs-original-title="Here you can logout your acccount." data-bs-placement="left" data-bs-toggle="tooltip" data-bss-tooltip="" href="login.php">logout</a>
            </div>
        </div>
    </nav>
    <div id="content">
        <div class="container-fluid">
            <div class="d-sm-flex justify-content-between align-items-center mb-4">
                <h3 class="text-dark mb-0">Settings</h3><button class="btn btn-primary btn-sm float-end d-sm-inline-block" data-bs-toggle="modal" data-bss-tooltip="" data-bs-placement="left" type="button" data-bs-target="#change" title="Here you can change your account password."><i class="fas fa-user-check fa-sm text-white-50"></i>&nbsp;Change Password</button>
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
                                    <div class="text-uppercase text-success fw-bold text-xs mb-1"><span>ALERT HIGH (CM)</span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span class="fs-2 rangehigh">100</span></div>
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
                                    <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>ALERT LOW (CM)</span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span class="fs-2 rangelow">10</span></div>
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
                                    <div class="text-uppercase text-danger fw-bold text-xs mb-1"><span>today WATER ALERTS</span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span class="fs-2">0</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-cogs fa-2x text-gray-300"></i></div>
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
                            <form class="text-primary" method="post">
                                <div class="my-1 mb-4"><label class="form-label">Water level high threshold (cm)</label><input class="form-range" type="range" data-bs-toggle="tooltip" data-bss-tooltip="" title="Here you can adjust the water level alert threshold" name="high" onchange="rangehigh(this.value)" value="100"></div>
                                <div class="my-1 mb-4"><label class="form-label">Water level low threshold (cm)</label><input class="form-range" type="range" data-bs-toggle="tooltip" data-bss-tooltip="" title="Here you can adjust the water level alert threshold" name="low" onchange="rangelow(this.value)" value="10"></div>
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
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>CPU</th>
                                            <th>STORAGE</th>
                                            <th>RAM</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>10%</td>
                                            <td>8GB</td>
                                            <td>2048</td>
                                        </tr>
                                        <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                            <p class="text-primary m-0 fw-bold">Server Performance</p>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Response</th>
                                            <th>IP Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>10%</td>
                                            <td>127.0.0.1:5000</td>
                                        </tr>
                                        <tr></tr>
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
                            <div class="my-1"><label class="form-label">Ultrasonic Sensor (<span style="color: rgb(78, 223, 119);">Running</span>)</label></div>
                            <div class="text-center my-1"><button class="btn btn-danger" type="button" data-bs-target="#sensor" data-bs-toggle="modal">Restart</button></div>
                        </div>
                    </div>
                </div>
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
                    <p>Are you sure you want to restart the system sensor?</p>
                </div>
                <div class="modal-footer"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button><button class="btn btn-danger" type="button">Restart</button></div>
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