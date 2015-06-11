<nav class="navbar-default navbar-static-side no-print" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu" style="display: block;">
            <li class="nav-header">
                <p>
                    <strong class="font-bold"><?php echo getCompanyNamebyUsername($_SESSION['username']);?></strong>
                </p>
            </li>
            <li class="active">
                <a href="main.php"><i class="fa fa-inbox"></i> <span class="nav-label">Overview</span></a>
            </li>
            <li>
                <a href="create.php"><i class="fa fa-pencil"></i> <span class="nav-label">Create</span></a>
            </li>
            <li>
                <a href="received.php"><i class="fa fa-dollar"></i> <span class="nav-label">Received</span></a>
            </li>
            <li>
                <a href="settings.php"><i class="fa fa-cogs"></i> <span class="nav-label">Settings</span></a>
            </li>
        </ul>
    </div>
</nav>