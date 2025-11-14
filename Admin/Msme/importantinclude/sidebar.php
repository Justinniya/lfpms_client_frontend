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
            <a class="nav-link" href="msme_index.php">
                <i style="margin-left:10px;" class="fa fa-tachometer menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item" style="margin-left:10px;">
            <a class="nav-link" href="account.php">
                <i class="menu-icon mdi mdi-account-cog-outline"></i>
                <span class="menu-title">Account Settings</span>
            </a>
        </li>
        <li class="nav-item nav-category">Administration</li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="transactiondropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i style="margin-left:10px;" class="fa fa-list-alt fa-fw menu-icon"></i>
                <span class="menu-title">Transaction</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="transactiondropdown">
                <li><a class="dropdown-item" href="transaction.php">Point of Sales (POS)</a></li>
                <li><a class="dropdown-item" href="transactionhistory.php">Sales History</a></li>
            </ul>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="productDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-product-hunt fa-fw menu-icon" style="margin-left:10px;"></i>
                <span class="menu-title">Product</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="productDropdown">
                <li><a class="dropdown-item" href="viewproduct.php">All Products</a></li>
                <li><a class="dropdown-item" href="addproduct.php">Add Product</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="message.php">
                <i style="margin-left:10px;" class="fa fa-envelope fa-fw menu-icon"></i>
                <span class="menu-title ">Inbox</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="ProductRating.php">
                <i style="margin-left:10px;" class="fa fa-star fa-fw menu-icon"></i>
                <span class="menu-title">Product Ratings</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="assessment.php">
                <i style="margin-left:10px;" class="fa fa-pencil fa-fw menu-icon"></i>
                <span class="menu-title">Assessment</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="importantinclude/logout.php" onclick="return confirm('Are you sure you want to log out?');">
                <i style="margin-left:10px;" class="fa fa-sign-out fa-fw menu-icon"></i>
                <span class="menu-title">Log-Out</span>
            </a>
        </li>
    </ul>
</nav>