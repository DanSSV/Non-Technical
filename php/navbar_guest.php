<nav class="navbar navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="guest.php">
            <img src="../img/nyenyeenye.png" alt="Logo" height="50" width="auto" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"
            style="background-color: #2c5746">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-center">
                <li class="nav-item">
                    <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'guest.php') {
                        echo 'active';
                    } ?>" href="guest.php"><i class="fa-solid fa-house fa-lg" style="color: #ffffff;"></i> Home</a>
                </li>
                


                <li class="nav-item dropdown">
                    

                    <!-- Your HTML snippet -->
                    <a class="nav-link dropdown-toggle <?php if (basename($_SERVER['PHP_SELF']) == 'accountmanager.php') {
                        echo 'active';
                    } ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user-secret fa-lg" style="color: #ffffff;"></i>
                        <?php echo "Guest"; ?>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        
                        </li>
                        <li>
                            <a class="dropdown-item" href="../php/logout.php" style="color: red;"><i
                                    class="fa-solid fa-right-from-bracket fa-lg" style="color: #ffffff;"></i> Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>