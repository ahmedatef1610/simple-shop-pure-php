<?php
ob_start();
session_start();
$pageTitle = 'Profile' ;
include 'init.php';
if ( isset( $_SESSION['user'] ) ) {
    $getUser = $con->prepare( 'SELECT * FROM users WHERE UserName = ?' );
    $getUser->execute( [$_SESSION['user']] );
    $row = $getUser->fetch();

    $userid = $row['UserId'];
    ?>

<h1 class='text-center display-4 my-3'>My Profile <i class='fad fa-id-card'></i></h1>
<div class='information my-3'>
    <div class='container'>
        <div class='card'>
            <div class='card-header'>
                My Information
            </div>
            <ul class='list-group list-group-flush'>
                <li class='list-group-item'><i class='fad fa-fingerprint'></i> Name : <?php echo $row['UserName'];
    ?>
                </li>
                <li class='list-group-item'><i class='fad fa-at'></i> Email : <?php echo $row['Email'];
    ?></li>
                <li class='list-group-item'><i class='fad fa-calendar-alt'></i> Register Date :
                    <?php echo $row['Date'];
    ?></li>
                <li class='list-group-item'><i class='fad fa-layer-group'></i><span>Fav Category</span> : </li>
                <li class='list-group-item'><a href='#' class='btn btn-primary mt-3 d-inline'>Edit My Information</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class='information my-3' id='myItem'>
    <div class='container'>
        <div class='card'>
            <div class='card-header'>
                My Items
            </div>
            <div class='card-body'>
                <?php
    $myItems = getAllFrom( '*', 'items', "where MemberId={$userid}", '', 'ItemId' );
    if ( ! empty( $myItems ) ) {
        echo '<div class="row">';
        foreach ( $myItems as $item ) {
            echo '<div class="col-sm-6 col-md-3 mb-3">';
            echo '<div class="card mb-3" style="height: 100%;">';
            echo '<img src="';
            echo $img. 'p.jpeg' .'" class="card-img-top" alt="'.$item['Name'].'">';
            echo '<div class="card-body d-flex flex-column justify-content-end">';
            echo '<h5 class="card-title"><a href="items.php?ItemId='.$item['ItemId'].'">'.$item['Name'].'</a></h5>';
            echo '<p class="card-text">';
            echo $item['Description'];
            echo '</p>';
            echo '<p class="card-text item-box">';
            echo '<span class="badge badge-primary mr-1">'.$item['Price'].' $</span>';
            echo '<span class="badge badge-primary mr-1">'.$item['CountryMade'].'</span>';
            echo '<span class="badge badge-primary mr-1">'.$item['AddDate'].'</span>';
            echo '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p class="card-text">Sorry There\' No Ads To Show, Create <a href = "newad.php">New Ad</a></p>';
                    }
                    ?>
            </div>
        </div>
    </div>
</div>
<div class="information my-3">
    <div class='container'>
        <div class="card">
            <div class="card-header">
                Latest Comments
            </div>
            <div class="card-body">
                <?php
                    $myComments=getAllFrom("*","comments","where UserId={$userid}","","CommentId");
                    if ( ! empty( $myComments ) ) {
                        foreach ($myComments as $comment) {
                            echo '<p class = "card-text">'.$comment['Comment'].'</p>';
                        }
                    }else{
                        echo '<p class = "card-text">There\'s No Comments to Show</p>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
} else {
    header( 'Location: login.php' );
    exit();
}
include $tpl.'footer.php';
ob_end_flush();
?>