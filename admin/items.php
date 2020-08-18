<?php
ob_start();
// Output Buffering Start
session_start();
$pageTitle = 'Items';
if ( isset( $_SESSION['UserName'] ) ) {
    include 'init.php';

    $do = isset( $_GET['do'] ) ? $_GET['do'] : 'manage';

    if ( $do == 'manage' ) {
        // 11111111111111111111111111111111111
        $stmt = $con->prepare( "SELECT 
										items.*, 
										categories.Name AS category_name, 
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
									ORDER BY 
										ItemId DESC" );

        // Execute The Statement
        $stmt->execute();
        // Assign To Variable
        $items = $stmt->fetchAll();
        if ( ! empty( $items ) ) {
            ?>

<h1 class='text-center display-4 mt-3'>Manage Items <i class='fad fa-boxes-alt'></i></h1>
<div class='categories'>
    <div class='container my-3'>
        <a class='btn btn-primary my-3' href='items.php?do=add'><i class='fa fa-plus'></i> New Item</a>
        <div class='table-responsive'>
            <table class='main-table text-center table table-bordered'>
                <tr>
                    <td>#ID</td>
                    <td>Item Name</td>
                    <td>Description</td>
                    <td>Price</td>
                    <td>Adding Date</td>
                    <td>Country</td>
                    <td>Category</td>
                    <td>Username</td>
                    <td>Control</td>
                </tr>
                <?php
            foreach ( $items as $item ) {
                echo '<tr>';
                echo '<td>' . $item['ItemId'] . '</td>';
                echo '<td>' . $item['Name'] . '</td>';
                echo '<td>' . $item['Description'] . '</td>';
                echo '<td>' . $item['Price'] . '</td>';
                echo '<td>' . $item['AddDate'] .'</td>';
                echo '<td>' . $item['CountryMade'] .'</td>';
                echo '<td>' . $item['category_name'] .'</td>';
                echo '<td>' . $item['UserName'] .'</td>';
                echo "<td>
                        <a href='items.php?do=edit&ItemId=" . $item['ItemId'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                        <a href='items.php?do=delete&ItemId=" . $item['ItemId'] . "' class='btn btn-danger confirm'><i class='fa fa-times'></i> Delete </a>";
                if ( $item['Approve'] == 0 ) {
                    echo "<a href='items.php?do=approve&ItemId=" . $item['ItemId'] . "'class='btn btn-info activate'>
                        <i class='fa fa-check'></i> Approve</a>";
                }
                echo '</td>';
                echo '</tr>';
            }
            ?>
                <tr>
            </table>
        </div>
    </div>
</div>

<?php
        } else {
            echo '<div class="container py-3">';
            echo '<div class="nice-message alert alert-info">There\'s No Items To Show</div>';
            echo "<a href = 'items.php?do=add' class = 'btn btn-sm btn-primary'><i class = 'fa fa-plus'></i> New Item</a>";
            echo '</div>';
        }
    } elseif ( $do == 'add' ) {
        // 222222222222222222222222222222222
?>

<h1 class='text-center display-4 mt-3'>Add Item <i class="fad fa-hand-holding-box"></i></h1>
<div class='container px-5'>
    <form class='form-horizontal' action='?do=insert' method='post'>

        <div class='form-group row'>
            <label for='name' class='col-sm-2 col-form-label'>Name</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' id='name' name='name' required>
            </div>
        </div>

        <div class='form-group row'>
            <label for='description' class='col-sm-2 col-form-label'>Description</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' id='description' name='description' required>
            </div>
        </div>

        <div class='form-group row'>
            <label for='price' class='col-sm-2 col-form-label'>Price</label>
            <div class='col-sm-10'>
                <input type='number' class='form-control' id='price' name='price' required>
            </div>
        </div>

        <div class='form-group row'>
            <label for='country' class='col-sm-2 col-form-label'>Country of Made</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' id='country' name='country' required>
            </div>
        </div>

        <div class='form-group row'>
            <label for='status' class='col-sm-2 col-form-label'>Status</label>
            <div class='col-sm-10'>
                <select class="form-control" id='status' name='status' required>
                    <option value="" hidden>Choose Status from here</option>
                    <option value="1">New</option>
                    <option value="2">Like New</option>
                    <option value="3">Old</option>
                    <option value="4">Very Old</option>
                    <option value="5">Used</option>
                </select>
            </div>
        </div>

        <div class='form-group row'>
            <label for='rating' class='col-sm-2 col-form-label'>Rating</label>
            <div class='col-sm-10'>
                <select class="form-control" id='rating' name='rating' required>
                    <option value="" hidden>Choose Rating from here</option>
                    <option value="0">💩</option>
                    <option value="1">⭐</option>
                    <option value="2">⭐⭐</option>
                    <option value="3">⭐⭐⭐</option>
                    <option value="4">⭐⭐⭐⭐</option>
                    <option value="5">⭐⭐⭐⭐⭐</option>
                </select>
            </div>
        </div>

        <div class='form-group row'>
            <label for='member' class='col-sm-2 col-form-label'>Member</label>
            <div class='col-sm-10'>
                <select class="form-control" id='member' name='member' required>
                    <option value="" hidden>Choose Member from here</option>
                    <?php
                        $allMembers = getAllFrom("*", "users", "", "", "UserId");
                        foreach ($allMembers as $user) {
                            echo "<option value='" . $user['UserId'] . "'>" . $user['UserName'] . "</option>";
                        }
                    ?>
                </select>
            </div>
        </div>


        <div class='form-group row'>
            <label for='category' class='col-sm-2 col-form-label'>Category</label>
            <div class='col-sm-10'>
                <select class="form-control" id='category' name='category' required>
                    <option value="" hidden>Choose Category from here</option>
                    <?php
                        $allCats = getAllFrom("*", "categories", "where parent = 0", "", "Id");
                        foreach ($allCats as $cat) {
                            echo "<option value='" . $cat['Id'] . "'>" . $cat['Name'] . "</option>";
                            $childCats = getAllFrom("*", "categories", "where parent = {$cat['Id']}", "", "Id");
                            foreach ($childCats as $child) {
                                echo "<option value='" . $child['Id'] . "'>--- " . $child['Name'] . "</option>";
                            }
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class='form-group row'>
            <label for='tags' class='col-sm-2 col-form-label'>Tags</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' id='tags' name='tags' placeholder="Separate Tags With Comma ( , )" required>
            </div>
        </div>

        <div class='form-group row'>
            <div class='col-sm-10 offset-sm-2'>
                <button type='submit' class='btn btn-primary'><i class="fad fa-dolly-flatbed-alt"></i> Add Item</button>
            </div>
        </div>

    </form>
</div>

<?php

    } elseif ( $do == 'insert' ) {
        // 33333333333333333333333333333333333333333
        echo "<h1 class='text-center display-4 mt-3'>Insert Item <i class='fad fa-boxes'></i></h1>";
        echo "<div class='container my-3'>";
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $rating = $_POST['rating'];
            $member = $_POST['member'];
            $cat = $_POST['category'];
            $tags = $_POST['tags'];
            
            $formErrors = [];

        if (empty($name)) {
                $formErrors[] = 'Name Can\'t be <strong>Empty</strong>';
        }
        if ( empty( $description ) ) {
            $formErrors[] = 'Description Can\'t be <strong>Empty</strong>';
            }
            if (empty($price)) {
                $formErrors[] = 'Price Can\'t be <strong>Empty</strong>';
        }
        if ( empty( $country ) ) {
            $formErrors[] = 'Country Can\'t be <strong>Empty</strong>';
            }
            if ($status == "") {
                $formErrors[] = 'You Must Choose the <strong>Status</strong>';
            }
            if ($rating == "") {
                $formErrors[] = 'You Must Choose the <strong>Rating</strong>';
            }
            if ($member == "") {
                $formErrors[] = 'You Must Choose the <strong>Member</strong>';
            }
            if ($cat == "") {
                $formErrors[] = 'You Must Choose the <strong>Category</strong>';
            }
            if ($tags == "") {
                $formErrors[] = 'tags Can\'t be <strong>Empty</strong>';
            }

            foreach ( $formErrors as $error ) {
                echo "<div class = 'alert alert-danger'>" . $error . '</div>';
            }

            if ( empty( $formErrors ) ) {
                // Insert Category In Database
                $stmt = $con->prepare("INSERT INTO items(Name,Description,Price,AddDate,CountryMade,Status,Rating,MemberId,CatId,Tags)
                                        VALUES(:name,:description,:price,now(),:country,:status,:rating,:member,:cat,:tags) ");
                $stmt->execute(['name' => $name, 
                                'description' => $description, 
                                'price' => $price, 
                                'country' => $country,
                                'status' => $status,
                                'rating' => $rating,
                                'member' => $member,
                                'cat' => $cat,
                                'tags' => $tags,
                                ]);
                // Echo Success Message
                redirectHome( "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>' ,'back');
            }
        } else {
            redirectHome( "<div class = 'alert alert-danger'>Sorry You Cant Browse This Page Directly</div>", 'back' );
        }
        echo '</div>';
    } elseif ( $do == 'edit' ) {
        // 44444444444444444444444444444444444444
        // Check If Get Request UserId Is Numeric & Get Its Integer Value
        $ItemId = isset( $_GET['ItemId'] ) && is_numeric( $_GET['ItemId'] ) ? intval( $_GET['ItemId'] ) : 0;
        // Select All Data Depend On This ID
        $stmt = $con->prepare( 'SELECT * FROM items WHERE ItemId = ? LIMIT 1' );
        // Execute Query
        $stmt->execute( [$ItemId] );
        // Fetch The Data
        $row = $stmt->fetch();
        // The Row Count
        $count = $stmt->rowCount();
        // If There's Such ID Show The Form
            if ( $count > 0 ) {
                ?>
<h1 class='text-center display-4 mt-3'>Edit Item</h1>
<div class='container  my-5'>
    <form class='form-horizontal' action='?do=update' method='post'>

        <input type='hidden' name='ItemId' value="<?php echo $ItemId ?>">

        <div class='form-group row'>
            <label for='name' class='col-sm-2 col-form-label'>Name</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' id='name' name='name' value="<?php echo $row['Name'] ?>"
                    required>
            </div>
        </div>

        <div class='form-group row'>
            <label for='description' class='col-sm-2 col-form-label'>Description</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' id='description' name='description'
                    value="<?php echo $row['Description'] ?>" required>
            </div>
        </div>

        <div class='form-group row'>
            <label for='price' class='col-sm-2 col-form-label'>Price</label>
            <div class='col-sm-10'>
                <input type='number' class='form-control' id='price' name='price' value="<?php echo $row['Price'] ?>"
                    required>
            </div>
        </div>

        <div class='form-group row'>
            <label for='country' class='col-sm-2 col-form-label'>Country of Made</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' id='country' name='country'
                    value="<?php echo $row['CountryMade'] ?>" required>
            </div>
        </div>

        <div class='form-group row'>
            <label for='status' class='col-sm-2 col-form-label'>Status</label>
            <div class='col-sm-10'>
                <select class='form-control' id='status' name='status' required>
                    <option value='' hidden>Choose Status from here</option>
                    <option value='1' <?php if ( $row['Status'] == 1 ) {
                    echo 'selected';
                }
                ?>>New</option>
                    <option value='2' <?php if ( $row['Status'] == 2 ) {
                    echo 'selected';
                }
                ?>>Like New</option>
                    <option value='3' <?php if ( $row['Status'] == 3 ) {
                    echo 'selected';
                }
                ?>>Old</option>
                    <option value='4' <?php if ( $row['Status'] == 4 ) {
                    echo 'selected';
                }
                ?>>Very Old</option>
                    <option value='5' <?php if ( $row['Status'] == 5 ) {
                    echo 'selected';
                }
                ?>>Used</option>
                </select>
            </div>
        </div>

        <div class='form-group row'>
            <label for='rating' class='col-sm-2 col-form-label'>Rating</label>
            <div class='col-sm-10'>
                <select class='form-control' id='rating' name='rating' required>
                    <option value='' hidden>Choose Rating from here</option>
                    <option value='0' <?php if ( $row['Rating'] == 0 ) {
                    echo 'selected';
                }
                ?>>💩</option>
                    <option value='1' <?php if ( $row['Rating'] == 1 ) {
                    echo 'selected';
                }
                ?>>⭐</option>
                    <option value='2' <?php if ( $row['Rating'] == 2 ) {
                    echo 'selected';
                }
                ?>>⭐⭐</option>
                    <option value='3' <?php if ( $row['Rating'] == 3 ) {
                    echo 'selected';
                }
                ?>>⭐⭐⭐</option>
                    <option value='4' <?php if ( $row['Rating'] == 4 ) {
                    echo 'selected';
                }
                ?>>⭐⭐⭐⭐</option>
                    <option value='5' <?php if ( $row['Rating'] == 5 ) {
                    echo 'selected';
                }
                ?>>⭐⭐⭐⭐⭐</option>
                </select>
            </div>
        </div>

        <div class='form-group row'>
            <label for='member' class='col-sm-2 col-form-label'>Member</label>
            <div class='col-sm-10'>
                <select class='form-control' id='member' name='member' required>
                    <option value='' hidden>Choose Member from here</option>
                    <?php
                $allMembers = getAllFrom( '*', 'users', '', '', 'UserId' );
                foreach ( $allMembers as $user ) {
                    echo "<option value='" . $user['UserId'] . "'";
                    if ( $row['MemberId'] == $user['UserId'] ) {
                        echo 'selected';
                    }
                    echo '>' . $user['UserName'] . '</option>';
                }
                ?>
                </select>
            </div>
        </div>

        <div class='form-group row'>
            <label for='category' class='col-sm-2 col-form-label'>Category</label>
            <div class='col-sm-10'>
                <select class='form-control' id='category' name='category' required>
                    <option value='' hidden>Choose Category from here</option>
                    <?php
                $allCats = getAllFrom( '*', 'categories', '', '', 'Id' );
                foreach ( $allCats as $cat ) {
                    echo "<option value='" . $cat['Id'] . "'";
                    if ( $row['CatId'] == $cat['Id'] ) {
                        echo 'selected';
                    }
                    echo '>' . $cat['Name'] . '</option>';
                }
                ?>
                </select>
            </div>
        </div>


        <div class='form-group row'>
            <label for='tags' class='col-sm-2 col-form-label'>Tags</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' id='tags' name='tags' 
                placeholder="Separate Tags With Comma ( , )" value="<?php echo $row['Tags'] ?>" required>
            </div>
        </div>

        <div class='form-group row'>
            <div class='col-sm-10 offset-sm-2'>
                <button type='submit' class='btn btn-primary'><i class='fad fa-dolly-flatbed-alt'></i> save
                    Item</button>
            </div>
        </div>

    </form>
</div>
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
                                WHERE
                                    ItemId= ?" );

                // Execute The Statement
                $stmt->execute([$ItemId]);
                // Assign To Variable
                $comments = $stmt->fetchAll();
                if ( ! empty( $comments ) ) {
                    ?>
<h1 class='text-center display-4 my-3'>Manage [<?php echo $row['Name'] ?>] Comments <i class='fad fa-comments'></i></h1>
<div class='container'>
    <div class='table-responsive'>
        <table class='main-table text-center table table-bordered'>
            <tr>
                <td>Comment</td>
                <td>User Name</td>
                <td>Added Date</td>
                <td>Control</td>
            </tr>
            <?php
                    foreach ( $comments as $comment ) {
                        echo '<tr>';
                        echo '<td>' . $comment['Comment'] . '</td>';
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

<?php }?>

<?php
                } else {
                    echo "<div class='container my-3'>";
                    redirectHome( "<div class = 'alert alert-danger'>Theres No Such ID</div>", 'back' );
                    echo '</div>';
                }

            } elseif ( $do == 'update' ) {
                // 55555555555555555555555555555555555555
                echo "<h1 class='text-center display-4 mt-3'>Update Item</h1>";
                if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
                    $ItemId 	 = $_POST['ItemId'];
                    $name = $_POST['name'];
                    $description = $_POST['description'];
                    $price = $_POST['price'];
                    $country = $_POST['country'];
                    $status = $_POST['status'];
                    $rating = $_POST['rating'];
                    $member = $_POST['member'];
                    $cat = $_POST['category'];
                    $tags = $_POST['tags'];

                    $formErrors = [];

                    if ( empty( $name ) ) {
                        $formErrors[] = 'Name Can\'t be <strong>Empty</strong>';
                }
                if ( empty( $description ) ) {
                    $formErrors[] = 'Description Can\'t be <strong>Empty</strong>';
                    }
                    if ( empty( $price ) ) {
                        $formErrors[] = 'Price Can\'t be <strong>Empty</strong>';
                }
                if ( empty( $country ) ) {
                    $formErrors[] = 'Country Can\'t be <strong>Empty</strong>';
                    }
                    if ( $status == '' ) {
                        $formErrors[] = 'You Must Choose the <strong>Status</strong>';
                    }
                    if ( $rating == '' ) {
                        $formErrors[] = 'You Must Choose the <strong>Rating</strong>';
                    }
                    if ( $member == '' ) {
                        $formErrors[] = 'You Must Choose the <strong>Member</strong>';
                    }
                    if ( $cat == '' ) {
                        $formErrors[] = 'You Must Choose the <strong>Category</strong>';
                    }

                    foreach ( $formErrors as $error ) {
                        echo "<div class = 'alert alert-danger'>" . $error . '</div>';
                    }

                    // Check If There's No Error Proceed The Update Operation
            if (empty( $formErrors ) ) {
                // Update The Database With This Info
                $stmt = $con->prepare( 'UPDATE items
                                        SET
                                            Name = ?,
                                            Description = ?,
                                            Price = ?,
                                            CountryMade = ?,
                                            Status = ?,
                                            Rating = ?,
                                            MemberId = ?,
                                            CatId = ?,
                                            tags = ?
                                        WHERE
                                            ItemId = ?' );
                $stmt->execute( [$name, $description, $price, $country,$status,$rating,$member,$cat,$tags,$ItemId ] );
                echo "<div class='container my-3'>";
                redirectHome("<div class='alert alert-success'>". $stmt->rowCount() ." Record Updated</div>",1);
                echo '</div>';
            }
        } else {
            echo "<div class='container my-3'>";
            redirectHome("<div class = 'alert alert-danger'>Sorry You Cant Browse This Page Directly</div>");
            echo '</div>';
        }

        } elseif ( $do == 'delete' ) {
            // 666666666666666666666666666666666666
            // Delete Item Page
            echo "<h1 class='text-center display-4 mt-3'>Delete Category</h1>";
            echo "<div class='container my-3'>";
            // Check If Get Request ItemId Is Numeric & Get The Integer Value Of It
            $ItemId = isset( $_GET['ItemId'] ) && is_numeric( $_GET['ItemId'] ) ? intval( $_GET['ItemId'] ) : 0;
            // Select All Data Depend On This ID
            $check = checkItem('ItemId', 'items', $ItemId);
            if ( $check > 0 ) {
            // If There's Such ID Show The Form
                    $stmt = $con->prepare( 'DELETE FROM items WHERE ItemId = :ItemId' );
                    $stmt->bindParam( ':ItemId', $ItemId );
                    $stmt->execute();
                    redirectHome( "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>', 'back' );
                } else {
                    redirectHome( "<div class = 'alert alert-danger'>This ID is Not Exist</div>", 'back' );
                }
                echo '</div>';
            } elseif ( $do == 'approve' ) {
                // 7777777777777777777777777777777777777777777
                echo "<h1 class='text-center'>Approve Item</h1>";
                echo "<div class='container'>";
                // Check If Get Request Item ID Is Numeric & Get The Integer Value Of It
                $ItemId = isset( $_GET['ItemId'] ) && is_numeric( $_GET['ItemId'] ) ? intval( $_GET['ItemId'] ) : 0;
                // Select All Data Depend On This ID
                $check = checkItem( 'ItemId', 'items', $ItemId );
                // If There's Such ID Show The Form
				if ($check > 0) {
					$stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE ItemId = ?");
					$stmt->execute(array($ItemId));
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
					redirectHome($theMsg, 'back');
				} else {
					$theMsg = "<div class = 'alert alert-danger'>This ID is Not Exist</div>";
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