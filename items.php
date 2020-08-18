<?php
ob_start();
session_start();
$pageTitle = 'Show Item' ;
include 'init.php';

$ItemId = isset( $_GET['ItemId'] ) && is_numeric( $_GET['ItemId'] ) ? intval( $_GET['ItemId'] ) : 0;
$stmt = $con->prepare( 'SELECT 
                            items.*, 
                            categories.Name AS categoryName, 
                            users.UserName  
                        FROM 
                            items 
                        INNER JOIN 
                            categories 
                        ON 
                            categories.Id = items.CatId 
                        INNER JOIN 
                            users 
                        ON 
                            users.UserId = items.MemberId
                        WHERE 
                            ItemId = ?
                        AND 
							Approve = 1' );
$stmt->execute( [$ItemId] );
$item = $stmt->fetch();
$count = $stmt->rowCount();
if ( $count > 0 ) {

?>

<h1 class="text-center display-4 my-3"><?php echo $item['Name']?> <i class="fad fa-box"></i></h1>

<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="">
                <img class="img-fluid img-thumbnail" src="<?php echo $img ?>p.jpeg" alt="<?php echo $item['Name']?>">
            </div>
        </div>
        <div class="col-md-9">
            <h2><?php echo $item['Name'] ?></h2>
            <p><?php echo $item['Description'] ?></p>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div><i class="fa fa-building"></i> Made In : <?php echo $item['CountryMade'] ?></div>
                </li>
                <li class="list-group-item">
                    <div><i class="fa fa-money-bill-alt"></i> Price : <?php echo $item['Price'] ?> $</div>
                </li>
                <li class="list-group-item">
                    <div><i class="fa fa-calendar-alt"></i> Date : <?php echo $item['AddDate'] ?></div>
                </li>
                <li class="list-group-item">
                    <div><i class="fa fa-layer-group"></i> Category :<a
                            href="categories.php?PageId=<?php echo $item['CatId']?>&PageName=<?php echo $item['categoryName']?>"><?php echo $item['categoryName'] ?></a>
                    </div>
                </li>
                <li class="list-group-item">
                    <div><i class="fa fa-user"></i> Add By :<a href="user.php?UserId=<?php echo $item['MemberId']?>">
                            <?php echo $item['UserName'] ?></a></div>
                </li>
                <li class="list-group-item">
                    <div>
                        <i class="fad fa-tags"></i> Tags :
                        <?php 
						$allTags = explode(",", $item['Tags']);
						foreach ($allTags as $tag) {
							$tag = str_replace(' ', '', $tag);
							$lowerTag = strtolower($tag);
							if (! empty($tag)) {
								echo "<a class='btn btn-primary btn-sm mr-1' href='tags.php?name={$lowerTag}'>" . $tag . '</a>';
							}
						}
					?>
                    </div>
                </li>
            </ul>

        </div>
    </div>
    <hr class="dropdown-divider">
    <?php if ( isset( $_SESSION['user'] ) ) { ?>
    <div class="row">
        <div class="col-md-9 offset-md-3">
            <h3>Add Your Comment</h3>
            <form action='<?php echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'] ?>' method="post">
                <div class="form-group">
                    <textarea class="form-control" id="comment" rows="3" name="comment" required></textarea>
                </div>
                <div class='form-group'>
                    <button type='submit' class='btn btn-primary'>Add Comment</button>
                </div>
            </form>
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    $comment 	= filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                    $ItemId 	= $item['ItemId'];
                    $userId 	= $_SESSION['userId'];

                    if (! empty($comment)) {

                        $stmt = $con->prepare("INSERT INTO 
                            comments(Comment, Status, CommentDate, ItemId, UserId)
                            VALUES(:comment, 0, NOW(), :ItemId, :userId)");

                        $stmt->execute(array(
                            'comment' => $comment,
                            'ItemId' => $ItemId,
                            'userId' => $userId
                        ));

                        if ($stmt) {
                            echo '<div class="alert alert-success">Comment Added</div>';
                        }
                    } else {
                        echo '<div class="alert alert-danger">You Must Add Comment</div>';
                    }

                }
            ?>
        </div>
    </div>
    <?php }else{
        echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> To Add Comment';
    } ?>
    <hr class="dropdown-divider">
    <?php
        $stmt = $con->prepare( "SELECT 
                                    comments.*, 
                                    users.UserName AS Member  
                                FROM 
                                    comments
                                INNER JOIN 
                                    users 
                                ON 
                                    users.UserId = comments.UserId
                                WHERE
                                    Status = 1
                                AND
                                    ItemId = ?
                                ORDER BY 
                                    CommentId DESC" );

        $stmt->execute([$item['ItemId']]);
        $comments = $stmt->fetchAll();
        if ( ! empty( $comments ) ) {
            foreach ($comments as $comment) {

    ?>

    <div class="row mb-3">
        <div class="col-md-3 text-center">
            <div class="text-center">
                <img class="img-fluid img-thumbnail rounded-circle w-25" src="<?php echo $img ?>icon.png"
                    alt="<?php echo $comment['Member']?>">
            </div>
            <a href="user.php?UserId=<?php echo $comment['UserId']?>">
                <?php echo $comment['Member'] ?>
            </a>
        </div>
        <div class="col-md-9 d-flex flex-column justify-content-center">
            <div class="py-1 px-3 bg-info rounded"><?php echo $comment['Comment']?></div>
            <div class="small text-right"><?php echo $comment['CommentDate']?></div>
        </div>
    </div>


    <?php } 
    }else{
        echo '<div class="alert alert-info">No Comment Here</div>';
    }?>
</div>

<?php
}else {
    echo "<div class='container my-3'>";
    redirectHome( "<div class = 'alert alert-danger'>Theres No Such ID</div>", 'back' );
    echo '</div>';
}

include $tpl.'footer.php';
ob_end_flush();
?>