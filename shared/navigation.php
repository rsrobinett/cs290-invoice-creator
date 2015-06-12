<nav class="navbar-default navbar-static-side no-print" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu" style="display: block;">
            <li class="nav-header">
                <p>
                    <strong class="font-bold"><?php echo getCompanyNamebyUsername($_SESSION['username']);?></strong>
                </p>
            </li>
            <li <?php if($pagetitle==='Accounts Receivable') echo 'class="active"';?> >
                <a href="main.php"><i class="fa fa-inbox"></i> <span class="nav-label">Accounts Receivable</span></a>
            </li>
            <li <?php if($pagetitle==='Create Invoice') echo 'class="active"';?>>
                <a href="create.php"><i class="fa fa-pencil"></i> <span class="nav-label">Create Invoice</span></a>
            </li>
            <li <?php if($pagetitle==='Accounts Payable') echo 'class="active"';?>>
                <a href="received.php"><i class="fa fa-dollar"></i> <span class="nav-label">Accounts Payable</span></a>
            </li>
            <li <?php if($pagetitle==='Settings') echo 'class="active"';?>>
                <a href="settings.php"><i class="fa fa-cogs"></i> <span class="nav-label">Settings</span></a>
            </li>
        </ul>
    </div>
</nav>