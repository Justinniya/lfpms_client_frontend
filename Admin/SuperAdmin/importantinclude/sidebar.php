<style>
    .sidebar .nav-link {
        display: flex !important;
        align-items: center !important;
        padding: 10px 15px !important; /* Adjust padding for consistency */
        text-decoration: none !important;
    }

    .sidebar .menu-icon {
        margin-right: 15px !important; /* Add spacing between icon and text */
        font-size: 18px !important; /* Ensure consistent icon size */
    }

    .sidebar .menu-title {
        flex-grow: 1 !important; /* Ensures text takes up remaining space */
        text-align: left !important; /* Aligns text properly */
    }

    .nav-item {
        text-align: left !important; /* Aligns list items to the left */
    }
</style>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-category">Governance</li>
        <li class="nav-item">
            <a class="nav-link" href="superadmin_index.php">
                <i class="fa fa-tachometer menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="approveusers.php">
                <i class="mdi mdi-account-check-outline menu-icon"></i>
                <span class="menu-title">Verify Users</span>
            </a>
        </li>

        <li class="nav-item nav-category">Administration</li>
        <li class="nav-item">
            <a class="nav-link" href="productdevelopment.php">
                <i class="mdi mdi-progress-check menu-icon"></i>
                <span class="menu-title">Product Development</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="inbox.php">
                <i class="mdi mdi mdi-email-outline menu-icon"></i>
                <span class="menu-title">Inbox</span>
            </a>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="usersdropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-account-group-outline menu-icon"></i>
                <span class="menu-title">Manage Users</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="usersdropdown">
                <li><a class="dropdown-item" href="userz.php">All Users</a></li>
                <li><a class="dropdown-item" href="consultant.php">Consultant</a></li>
                <li><a class="dropdown-item" href="msme.php">Msme's</a></li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="msmefeedback.php">
                <i class="mdi mdi-star-circle menu-icon"></i>
                <span class="menu-title">Monitor Feedback</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="scanlog.php">
                <i class="mdi mdi-history menu-icon"></i>
                <span class="menu-title">QR Scan Logs</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="viewevaluation.php">
                <i class="mdi mdi-comment-quote-outline menu-icon"></i>
                <span class="menu-title">Evaluation Sheet</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="viewsystem.php">
                <i class="mdi mdi-comment-quote-outline menu-icon"></i>
                <span class="menu-title">System Feedback</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="importantinclude/logout.php" onclick="return confirm('Are you sure you want to log out?');">
                <i class="fa fa-sign-out fa-fw menu-icon"></i>
                <span class="menu-title">Log-Out</span>
            </a>
        </li>
    </ul>
</nav>
