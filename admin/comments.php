<?php
ob_start();
// Output Buffering Start
session_start();
$pageTitle = 'Comments';
if ( isset( $_SESSION['UserName'] ) ) {
    include 'init.php';

    $do = isset( $_GET['do'] ) ? $_GET['do'] : 'manage';

    if ( $do == 'manage' ) {
        // 111111111111111111111
        // Select All Users Except Admin
        $stmt = $con->prepare( "SELECT 
                                    comments.*, 
                                    items.Name AS Item_Name, 
                                    users.UserName AS Member  
                                FROM 
                                    comments
                                INNER JOIN 
                                    items 
                                ON 
                                    items.ItemId = comments.ItemId
                                INNER JOIN 
                                    users 
                                ON 
                                    users.UserId = comments.UserId
                                ORDER BY 
                                    CommentId DESC" );

        // Execute The Statement
        $stmt->execute();
        // Assign To Variable
        $comments = $stmt->fetchAll();
        if ( ! empty( $comments ) ) {
            ?>

<h1 class='text-center display-4 my-3'>Manage Comments <i class='fad fa-comments'></i></h1>
<div class='container'>
    <div class='table-responsive'>
        <table class='main-table text-center table table-bordered'>
            <tr>
                <td>ID</td>
                <td>Comment</td>
                <td>Item Name</td>
                <td>User Name</td>
                <td>Added Date</td>
                <td>Control</td>
            </tr>
            <?php
            foreach ( $comments as $comment ) {
                echo '<tr>';
                echo '<td>' . $comment['CommentId'] . '</td>';
                echo '<td>' . $comment['Comment'] . '</td>';
                echo '<td>' . $comment['Item_Name'] . '</td>';
                echo '<td>' . $comment['Member'] . '</td>';
                echo '<td>' . $comment['CommentDate'] .'</td>';
                echo "<td>
                        <a href='comments.php?do=edit&CommentId=" . $comment['CommentId'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                        <a href='comments.php?do=delete&CommentId=" . $comment['CommentId'] . "' class='btn btn-danger confirm'><i class='fad fa-comment-times'></i> Delete </a>";
                if ( $comment['Status'] == 0 ) {
                    echo "<a href='comments.php?do=approve&CommentId=". $comment['CommentId'] . "'class='btn btn-info activate'>
                                    <i class='fa fa-check'></i> Approve
                                </a>";
                }
                echo '</td>';
                echo '</tr>';
            }
            ?>
            <tr>
        </table>
    </div>
</div>

<?php
        } else {
            echo '<div class="container py-3">';
            echo '<div class="alert alert-info">There\'s No Comments To Show</div>';
            echo '</div>';
            } 
?>

<?php
    
    } elseif ( $do == 'add' ) {
        // 222222222222222222222

    } elseif ( $do == 'insert' ) {
        // 333333333333333333333

    } elseif ( $do == 'edit' ) {
        // 44444444444444444444
        // Check If Get Request UserId Is Numeric & Get Its Integer Value
        $CommentId = isset( $_GET['CommentId'] ) && is_numeric( $_GET['CommentId'] ) ? intval( $_GET['CommentId'] ) : 0;
        // Select All Data Depend On This ID
        $stmt = $con->prepare( 'SELECT * FROM comments WHERE CommentId = ? LIMIT 1' );
        // Execute Query
        $stmt->execute( [$CommentId] );
        // Fetch The Data
        $row = $stmt->fetch();
        // The Row Count
        $count = $stmt->rowCount();
        // If There's Such ID Show The Form
            if ( $count > 0 ) {
                ?>
<h1 class='text-center display-4 mt-3'>Edit Comment <i class="fad fa-comment"></i></h1>
<div class='container  my-5'>
    <form class='form-horizontal' action='?do=update' method='post'>

        <input type='hidden' name='CommentId' value="<?php echo $CommentId ?>">

        <div class='form-group row'>
            <label for='comment' class='col-sm-2 col-form-label'>Comment</label>
            <div class='col-sm-10'>
                <textarea class='form-control' name="comment" id="comment" cols="30" rows="10"
                    required><?php echo $row['Comment'] ?></textarea>
            </div>
        </div>

        <div class='form-group row'>
            <div class='col-sm-10 offset-sm-2'>
                <button type='submit' class='btn btn-primary'><i class="fad fa-comment-edit"></i> Save Comment</button>
            </div>
        </div>

    </form>

    <?php
            } else {
                echo "<div class='container my-3'>";
                redirectHome( "<div class = 'alert alert-danger'>Theres No Such ID</div>", 'back' );
                echo '</div>';
            }

        } elseif ( $do == 'update' ) {
            // 55555555555555555555
            echo "<h1 class='text-center display-4 mt-3'>Update Item</h1>";
            if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
                $CommentId 	 = $_POST['CommentId'];
                $Comment = $_POST['comment'];

                // Check If There's No Error Proceed The Update Operation
            
                // Update The Database With This Info
                $stmt = $con->prepare( "UPDATE comments 
                                        SET 
                                            Comment = ? 
                                        WHERE 
                                            CommentId  = ?" );
                $stmt->execute( [$Comment, $CommentId] );
                echo "<div class='container my-3'>";
                redirectHome("<div class='alert alert-success'>". $stmt->rowCount() ." Record Updated</div>",1);
                echo '</div>';
            
        } else {
            echo "<div class='container my-3'>";
            redirectHome("<div class = 'alert alert-danger'>Sorry You Cant Browse This Page Directly</div>");
            echo '</div>';
        }
            

        } elseif ( $do == 'delete' ) {
            // 66666666666666666666
            // Delete Item Page
            echo "<h1 class='text-center display-4 my-3'>Delete Comment <i class='fad fa-comment-times'></i></h1>";
            echo "<div class='container my-3'>";
            // Check If Get Request ItemId Is Numeric & Get The Integer Value Of It
            $CommentId = isset( $_GET['CommentId'] ) && is_numeric( $_GET['CommentId'] ) ? intval( $_GET['CommentId'] ) : 0;
            // Select All Data Depend On This ID
            $check = checkItem('CommentId', 'comments', $CommentId);
            if ( $check > 0 ) {
            // If There's Such ID Show The Form
            $stmt = $con->prepare( 'DELETE FROM comments WHERE CommentId = :CommentId' );
            $stmt->bindParam( ':CommentId', $CommentId );
            $stmt->execute();
            redirectHome("<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>' ,"back");
            } else {
                redirectHome("<div class = 'alert alert-danger'>This ID is Not Exist</div>","back");
            }
            echo '</div>';
        } elseif ( $do == 'approve' ) {
            // 7777777777777777777777
			echo "<h1 class='text-center'>Approve Comment</h1>";
			echo "<div class='container'>";
				// Check If Get Request Item ID Is Numeric & Get The Integer Value Of It
				$CommentId = isset($_GET['CommentId']) && is_numeric($_GET['CommentId']) ? intval($_GET['CommentId']) : 0;
				// Select All Data Depend On This ID
				$check = checkItem('CommentId', 'comments', $CommentId);
				// If There's Such ID Show The Form
				if ($check > 0) {
					$stmt = $con->prepare("UPDATE comments SET Status = 1 WHERE CommentId = ?");
					$stmt->execute(array($CommentId));
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
					redirectHome($theMsg, 'back');
				} else {
					$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';
					redirectHome($theMsg);
				}
			echo '</div>';
        }

        include $tpl . 'footer.php';
    } else {
        header( 'Location: index.php' );
        exit();
    }
    ob_end_flush();
    // Release The Output

    ?>