<div class="upper-bar bg-secondary">
    <div class="container py-1 d-flex align-items-center">

        <?php if ( isset( $_SESSION['user'] ) ) {?>
        <div class="mr-auto">
            <div class="d-inline-block mr-2" style="width:15%;">
                <img src="<?php echo $img?>person.png" alt="" class="img-fluid rounded-circle">
            </div>
            <div class="btn-group">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                    <?php echo $_SESSION['user']?>
                </button>
                <div class="dropdown-menu ">
                    <a class="dropdown-item" href="profile.php"><i class="fad fa-user-circle"></i> My Profile</a>
                    <a class="dropdown-item" href="profile.php#myItem"><i class="fad fa-boxes-alt"></i> My Item</a>
                    <a class="dropdown-item" href="newad.php"><i class="fad fa-box-open"></i> New Item</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php"><i class="fad fa-sign-out"></i> Logout</a>
                </div>
            </div>
        </div>
        <?php
         }else { 
             echo '<a href="login.php" class="btn btn-primary btn-sm mr-auto">Login | Signup</a>';
         }
        ?>

        <span class="badge badge-info ml-1 d-inline-block" id="time"></span>

        <?php
            // if ( isset( $_SESSION['user'] ) ) {
            //     echo '<a href="profile.php"><span class="badge badge-success mr-1">'.$_SESSION['user'].'</span></a>';
            //     if(checkUserStatus($_SESSION['user'])){
            //         echo '<span class="badge badge-danger mr-1">your membership need to activiate by admin</span>';
            //     }
            //     echo '<a href="newad.php"><span class="badge badge-warning mr-1">New Ad</span></a>';
            //     echo '<a href="logout.php"><span class="float-right">Logout</span></a>';
            // }
            // else {
            //     echo '<a href="login.php"><span class="float-right">Login | Signup</span></a>';
            // }
        ?>

    </div>
</div>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand" href="index.php"><img src="<?php echo $img ?>icon.png" class="img-fluid" alt="icon"
                width="40"></a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <?php
                     $myCats=getAllFrom("*","categories","WHERE Parent=0","","Id","ASC");

                    foreach ( $myCats as $cat ) {
                        echo  "<li class='nav-item "; echo ($_GET['PageId']==$cat['Id'])?"active":""; echo "'>";
                        echo  "<a class='nav-link' href='categories.php?PageId=".$cat['Id'] ."&PageName=".str_replace(" ","-",$cat["Name"])."'>"; 
                        echo   $cat['Name']."</a>";
                        echo "</li>";
                    }
                ?>
            </ul>
        </div>
    </div>
</nav>