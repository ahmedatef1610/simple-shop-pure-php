<?php
ob_start();
// Output Buffering Start
session_start();
$pageTitle = 'Dashboard';
if ( isset( $_SESSION['UserName'] ) ) {
    include 'init.php';
    /* Start Dashboard Page */
    $numUsers = 6;
    // Number Of Latest Users
    $latestUsers = getLatest( '*', 'users', 'UserID', $numUsers );
    // Latest Users Array
    $numItems = 6;
    // Number Of Latest Items
    $latestItems = getLatest( '*', 'items', 'ItemId', $numItems );
    // Latest Items Array
    $numComments = 4;
    ?>

<div class='container mt-3'>
    <h4 class='text-center display-4'>Dashboard</h4>
    <p class='text-center lead'>
        <?php echo 'Welcome '.$_SESSION['UserName']?>
    </p>
</div>

<div class='home-stats'>
    <div class='container py-3'>
        <div class='row'>
            <div class='col-md-3'>
                <div class='card stat st-members'>
                    <div class='card-body text-center'>
                        <h5 class='card-title'><i class='fa fa-users'></i> Total Members</h5>
                        <p class='card-text'><a href='members.php'><?php echo countItems( 'UserId', 'users' ) ?></a></p>
                    </div>
                </div>
            </div>
            <div class='col-md-3'>
                <div class='card stat st-pending'>
                    <div class='card-body text-center'>
                        <h5 class='card-title'><i class='fa fa-user-plus'></i> Pending Members</h5>
                        <p class='card-text'>
                            <a
                                href='members.php?do=manage&page=pending'><?php echo checkItem( 'RegStatus', 'users', 0 ) ?></a>
                        </p>
                    </div>
                </div>
            </div>
            <div class='col-md-3'>
                <div class='card stat st-items'>
                    <div class='card-body text-center'>
                        <h5 class='card-title'><i class='fad fa-boxes-alt'></i> Total Items</h5>
                        <p class='card-text'><a href='items.php'><?php echo countItems( 'ItemId', 'items' ) ?></a></p>
                    </div>
                </div>
            </div>
            <div class='col-md-3'>
                <div class='card stat st-comments'>
                    <div class='card-body text-center'>
                        <h5 class='card-title'><i class='fa fa-comments'></i> Total Comments</h5>
                        <p class='card-text'><a href='comments.php'><?php echo countItems( 'CommentId', 'comments' ) ?></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class='latest'>
    <div class='container py-3'>
        <div class='row'>
            <div class='col-sm-6'>
                <div class='card'>
                    <div class='card-header'>
                        <i class='fa fa-users'></i> Latest <?php echo $numUsers ?> Registered Users
                        <span class='toggle-info float-right'>
                            <i class='fa fa-plus fa-lg'></i>
                        </span>
                    </div>
                    <ul class='list-group list-group-flush'>
                        <?php
    if ( ! empty( $latestUsers ) ) {
        foreach ( $latestUsers as $user ) {
            echo "<li class='list-group-item list-group-item-action'>";
            echo $user['UserName'];
            echo '<a class="btn btn-success btn-sm float-right" href="members.php?do=edit&UserId=' . $user['UserId'] . '">';
            echo '<i class="fa fa-edit"></i> Edit';
            echo '</a>';
            if ( $user['RegStatus'] == 0 ) {
                echo "<a 
                                            href='members.php?do=activate&UserId=" . $user['UserId'] . "' 
                                            class='btn btn-info btn-sm float-right mr-1 activate'>
                                            <i class='fa fa-check'></i> Activate</a>";
            }
            echo '</li>';
        }
    }
    ?>
                    </ul>
                </div>
            </div>
            <div class='col-sm-6'>
                <div class='card'>
                    <div class='card-header'>
                        <i class='fad fa-boxes'></i> Latest <?php echo $numItems ?> Items
                        <span class='toggle-info float-right'>
                            <i class='fa fa-plus fa-lg'></i>
                        </span>
                    </div>
                    <ul class='list-group list-group-flush'>
                        <?php
    if ( ! empty( $latestItems ) ) {
        foreach ( $latestItems as $item ) {
            echo "<li class='list-group-item list-group-item-action'>";
            echo $item['Name'];
            echo '<a class="btn btn-success btn-sm float-right" href="items.php?do=edit&ItemId=' . $item['ItemId'] . '">';
            echo '<i class="fa fa-edit"></i> Edit';
            echo '</a>';

            if ( $item['Approve'] == 0 ) {
                echo "<a 
                                    href='items.php?do=approve&ItemId=" . $item['ItemId'] . "' 
                                    class='btn btn-info btn-sm float-right mr-1 activate'>
                                    <i class='fa fa-check'></i> Approve</a>";
            }
        }
    } else {
        echo 'There\'s No Items To Show';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class='latest'>
    <div class='container py-3'>
        <div class='row'>
            <div class='col-sm-6'>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-comments"></i> Latest <?php echo $numComments ?> Comments
                        <span class="toggle-info float-right">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php 
                        // Select All Users Except Admin
                        $stmt = $con->prepare( "SELECT 
                                                comments.*, 
                                                users.UserName AS Member  
                                                FROM 
                                                    comments
                                                INNER JOIN 
                                                    users 
                                                ON 
                                                    users.UserId = comments.UserId
                                                ORDER BY 
                                                    CommentId DESC
                                                LIMIT 
                                                    $numComments ");

                        // Execute The Statement
                        $stmt->execute();
                        // Assign To Variable
                        $comments = $stmt->fetchAll();
                        ?>
                        <?php
                        if (! empty($comments)) {
                            foreach ($comments as $comment) {
                            echo "<li class='list-group-item list-group-item-action'>";
                            echo "<div class='p-1'><a href='members.php?do=edit&UserId=" . $comment['UserId'] . "'>"
                             . $comment['Member'] . "</a></div>";
                            echo "<div class='bg-info p-3'>".$comment['Comment']."</div>";
                            echo "</li>";
                        }
                    }
                    else {
                        echo "<li class='list-group-item list-group-item-action'>";
                        echo 'There\'s No Comments To Show';
                        echo "</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    /* End Dashboard Page */
    include $tpl . 'footer.php';
} else {
    $noNavbar = '';
    include 'init.php';
    echo "<div class = 'container py-3'><p class = 'text-center lead'>you are not Authoriztion to view this page</p></div>";
    header( 'refresh:2;url = index.php' );
        exit();
    }
    ob_end_flush();
    // Release The Output
    ?>