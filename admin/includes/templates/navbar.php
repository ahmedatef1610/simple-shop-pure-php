<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand" href="../index.php"><img src="<?php echo $img ?>icon.png" class="img-fluid" alt="icon"
                width="40"></a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item  <?php echo ($pageTitle=='Dashboard')?"active":"" ?>">
                    <a class="nav-link" href="dashboard.php"><?php echo lang("HOME_ADMIN")?></a>
                </li>
                <li class="nav-item <?php echo ($pageTitle=='Categories')?"active":"" ?>">
                    <a class="nav-link" href="categories.php"><?php echo lang("CATEGORIES")?></a>
                </li>
                <li class="nav-item <?php echo ($pageTitle=='Items')?"active":"" ?>">
                    <a class="nav-link" href="items.php"><?php echo lang("ITEMS")?></a>
                </li>
                <li class="nav-item <?php echo ($pageTitle=='Members')?"active":"" ?>">
                    <a class="nav-link" href="members.php"><?php echo lang("MEMBERS")?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php echo lang("STATISTICS")?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php echo lang("LOGS")?></a>
                </li>
                <li class="nav-item <?php echo ($pageTitle=='Comments')?"active":"" ?>">
                    <a class="nav-link" href="comments.php"><?php echo lang("COMMENTS")?></a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" data-toggle="dropdown">
                        <?php echo $_SESSION['UserName'] ?>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="../index.php"><i class="fad fa-cart-arrow-down"></i> Visit Shop</a>
                        <a class="dropdown-item" href="members.php?do=edit&UserId=<?php echo $_SESSION['UserId'] ?>"><i class="fad fa-user-edit"></i> Edit Profile</a>
                        <a class="dropdown-item" href="#"><i class="fad fa-cogs"></i> Settings</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php"><i class="fad fa-sign-out"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>


    </div>
</nav>