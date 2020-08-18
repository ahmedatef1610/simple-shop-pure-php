<?php
ob_start();
session_start();
$pageTitle = 'Show Category';
include 'init.php';
?>

<div class='container py-1'>
    <h1 class='text-center display-4 my-3'>Show Items By Tag Name</h1>
    <div class='row'>
        <?php
if (isset($_GET['name'])) {
    $tag = $_GET['name'];
    // foreach ( getItems2( 'CatId', $_GET['PageId'] ) as $item ) {
    $allItems = getAllFrom( '*', 'items', " WHERE Tags like '%$tag%' ", ' AND Approve = 1 ', 'ItemId' );
    foreach ( $allItems as $item ) {
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
                    if ( $item['Approve'] == 0 ) {
                        echo '<span class="badge badge-danger mr-1">NOT APPROVE</span>';
                    }
                echo '</p>';
            echo '</div>';
            echo '</div>';
        echo '</div>';
    }
} else {
    echo 'You Must Add Name';
}
?>
    </div>
</div>

<?php
include $tpl.'footer.php';
ob_end_flush();
?>