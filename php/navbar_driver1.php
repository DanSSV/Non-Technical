<nav class="navbar navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="driver.php">
            <img src="../img/nyenyeenye.png" alt="Logo" height="50" width="auto" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"
            style="background-color: #3b8875">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-center">
                <li class="nav-item">
                    <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'driver.php') {
                        echo 'active';
                    } ?>" href="driver.php">i class="fa-solid fa-house fa-lg" style="color: #ffffff;"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'Notificaton.php') {
                        echo 'active';
                    } ?>" href="Notificaton.php"><i class="fa-solid fa-envelope fa-lg" style="color: #ffffff;"></i>
                        Notification</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'history.php') {
                        echo 'active';
                    } ?>" href="history.php"><i class="fa-solid fa-clock-rotate-left fa-lg"
                            style="color: #ffffff;"></i> History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'inventory_report.php') {
                        echo 'active';
                    } ?>" href="inventory_report.php">Reports</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php if (basename($_SERVER['PHP_SELF']) == 'accountmanager.php') {
                        echo 'active';
                    } ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user fa-lg" style="color: #ffffff;"></i> Account
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="accountmanager.php"><i class="fa-solid fa-gear fa-lg"
                                    style="color: #ffffff;"></i> Manage Account</a></li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <a class="dropdown-item" href="../php/logout.php">
                                <i class="fa-solid fa-right-from-bracket fa-lg" style="color: #ffffff;"> Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>