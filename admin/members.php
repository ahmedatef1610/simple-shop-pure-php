<?php

/*
===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===
== Manage Members Page
== You Can Add | Edit | Delete Members From Here
===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===
*/
session_start();
$pageTitle = 'Members';

if ( isset( $_SESSION['UserName'] ) ) {

    include 'init.php';

    $do = isset( $_GET['do'] ) ? $_GET['do'] : 'manage';

    // 11111111111111111111111111111111111
    // Start Manage Page
    if ( $do == 'manage' ) {
        // Manage Members Page
        $query = '';
        if ( isset( $_GET['page'] ) && $_GET['page'] == 'pending' ) {
            $query = 'AND RegStatus = 0';
        }
        // Select All Users Except Admin
        $stmt = $con->prepare( "SELECT * FROM users WHERE GroupId != 1 $query ORDER BY UserId DESC" );
        // Execute The Statement
        $stmt->execute();
        // Assign To Variable
        $rows = $stmt->fetchAll();
        if ( ! empty( $rows ) ) {
            ?>

<h1 class='text-center display-4 mt-3'>Manage Members</h1>
<div class='container my-3'>
    <div class='table-responsive'>
        <table class='main-table text-center table table-bordered shadow-sm'>
            <thead class='thead-dark'>
                <tr>
                    <th>#ID</th>
                    <th>Avatar</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Full Name</th>
                    <th>Registered Date</th>
                    <th>Control</th>
                </tr>
            </thead>
            <tbody>

                <?php
            foreach ( $rows as $row ) {
                echo '<tr>';
                echo '<td>' . $row['UserId'] . '</td>';
                echo '<td class="w-25">';
                if(empty($row['avatar'])){
                    echo "no image";
                }else{

                    echo '<img src="uploads/avatars/'.$row['avatar'].'" class="img-fluid">';
                }
                echo '</td>';

                echo '<td>' . $row['UserName'] . '</td>';
                echo '<td>' . $row['Email'] . '</td>';
                echo '<td>' . $row['FullName'] . '</td>';
                echo '<td>'.$row['Date'] .'</td>';
                echo "<td>
                        <a href='members.php?do=edit&UserId=" . $row['UserId'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                        <a href='members.php?do=delete&UserId=" . $row['UserId'] . "' class='btn btn-danger confirm'><i class='fad fa-user-minus'></i> Delete</a>";
                if ( $row['RegStatus'] == 0 ) {
                    echo "<a href='members.php?do=activate&UserId=" . $row['UserId'] . "' class='btn btn-info activate ml-1'><i class='fa fa-check'></i> Activate</a>";
                }
                echo '</td>';
                echo '</tr>';
            }
            ?>
            </tbody>

        </table>
    </div>
    <a class='btn btn-primary' href='members.php?do=add'><i class='fa fa-plus'></i> Add New Member</a>
</div>

<?php } else {

                echo '<div class="container py-3">';
                echo '<div class="alert alert-info">There\'s No Members To Show</div>';
        echo "<a href = 'members.php?do=add' class = 'btn btn-primary'><i class = 'fa fa-plus'></i> New Member</a>";
        echo '</div>';

} ?>
<?php
    // 222222222222222222222222222222222
    } elseif ( $do == 'add' ) {
        // Add Page?>
<h1 class='text-center display-4 mt-3'>Add Member</h1>
<div class='container px-5'>
    <form class='form-horizontal' action='?do=insert' method='post' enctype="multipart/form-data">
        <div class='form-group row'>
            <label for='email' class='col-sm-2 col-form-label'>Email</label>
            <div class='col-sm-10'>
                <input type='email' class='form-control' id='email' name='email' >
            </div>
        </div>

        <div class='form-group row'>
            <label for='password' class='col-sm-2 col-form-label'>password</label>
            <div class='col-sm-10'>
                <input type='password' class='form-control' id='password' name='newpassword' >
                <i class='fad fa-eye fa-2x show-pass'></i>
            </div>
        </div>

        <div class='form-group row'>
            <label for='username' class='col-sm-2 col-form-label'>Username</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' id='username' name='username' >
            </div>
        </div>

        <div class='form-group row'>
            <label for='fullname' class='col-sm-2 col-form-label'>fullname</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' id='fullname' name='fullname' >
            </div>
        </div>

        <div class='form-group row'>
            <label for='avatar' class='col-sm-2 col-form-label'>User Avatar</label>
            <div class='col-sm-10'>
                <input type='file' class='form-control-file' id='avatar' name='avatar' >
            </div>
        </div>

        <div class='form-group row'>
            <div class='col-sm-10 offset-sm-2'>
                <button type='submit' class='btn btn-primary'><i class='fad fa-user-plus'></i> Add Member</button>
            </div>
        </div>

    </form>
</div>

<?php
    // 33333333333333333333333333333333333333333
    } elseif ( $do == 'insert' ) {
        echo "<h1 class='text-center display-4 mt-3'>Insert Member</h1>";
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
            $email 	 = $_POST['email'];
            $password 	 = $_POST['newpassword'];
            $user 	 = $_POST['username'];
            $name 	 = $_POST['fullname'];
            $hashPassword = sha1( $password );

            // Validate The Form
            $formErrors = array();

            $avatar = $_FILES['avatar'];
            // echo '<br>';
            // echo $avatar['name'] . '<br>';
            // echo $avatar['tmp_name'] . '<br>';
            // echo $avatar['type'] . '<br>';
            // echo $avatar['size'] . '<br>';

            $avatarAllowedExtensions = ['jpg', 'png', 'gif', 'jpeg'];
            $avatarExtensions = @strtolower( end( explode( '/', $avatar['type'] ) ) );
            




            if ( strlen( $user ) < 4 ) {
                $formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
            }
            if ( strlen( $user ) > 20 ) {
                $formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
            }
            if ( empty( $user ) ) {
                $formErrors[] = 'Username Cant Be <strong>Empty</strong>';
            }
            if ( empty( $password ) ) {
                $formErrors[] = 'Password Cant Be <strong>Empty</strong>';
            }
            if ( empty( $name ) ) {
                $formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
            }
            if ( empty( $email ) ) {
                $formErrors[] = 'Email Cant Be <strong>Empty</strong>';
            }

            if ( $avatar['error'] == 4 ) {
                $formErrors[]= '<div>no file uploaded</div>';
            }
            if ( !empty($avatar['name']) && !in_array( $avatarExtensions, $avatarAllowedExtensions ) ) {
                $formErrors[] = '<div>file is not valid</div>';
            }
            if ( $avatar['size'] >= 10000000 ) {
                $formErrors[]= '<div>file cant be more than 10MB</div>';
            }

            // Loop Into Errors Array And Echo It
            echo "<div class='container my-3'>";
            foreach ( $formErrors as $error ) {
                echo "<div class = 'alert alert-danger'>" . $error . '</div>';
            }
            echo '</div>';

            // Check If There's No Error Proceed The Update Operation
                if ( empty( $formErrors ) ) {

                    $avatarName = rand(0,100000)."_".$avatar['name'];
                    move_uploaded_file($avatar['tmp_name'],"uploads/avatars/".$avatarName);

                    // Check If User Exist in Database
                    $check = checkItem( 'UserName', 'users', $user );
                    if ( $check == 1 ) {
                        echo "<div class='container my-3'>";
                        redirectHome( "<div class = 'alert alert-danger'>Sorry This User Is Exist</div>", 1 );
                        echo '</div>';
                    } else {
                        // Insert UserInfo In Database
                        $stmt = $con->prepare( "INSERT INTO users(UserName, Password, Email, FullName,RegStatus , Date,avatar)
                                        VALUES(:user, :hashPassword, :email, :name,1, now(),:avatar ) " );

                        $stmt->execute( ['user' => $user, 'hashPassword' => $hashPassword, 'email' => $email, 'name' => $name,'avatar' => $avatarName] );
                        // Echo Success Message
                        echo "<div class='container my-3'>";
                        redirectHome( "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>' );
                        echo '</div>';
                    }
                }
            } else {
                echo "<div class='container my-3'>";
                redirectHome( "<div class = 'alert alert-danger'>Sorry You Cant Browse This Page Directly</div>", 1 );
                echo '</div>';
            }
            // 44444444444444444444444444444444444444
        } elseif ( $do == 'edit' ) {

            // Check If Get Request UserId Is Numeric & Get Its Integer Value
            $UserId = isset( $_GET['UserId'] ) && is_numeric( $_GET['UserId'] ) ? intval( $_GET['UserId'] ) : 0;
            // Select All Data Depend On This ID
            $stmt = $con->prepare( 'SELECT * FROM users WHERE UserId = ? LIMIT 1' );
            // Execute Query
            $stmt->execute( [$UserId] );
            // Fetch The Data
            $row = $stmt->fetch();
            // The Row Count
            $count = $stmt->rowCount();
            // If There's Such ID Show The Form
            if ( $count > 0 ) {
                ?>

<h1 class='text-center display-4 mt-3'>Edit Member</h1>
<div class='container px-5'>
    <form class='form-horizontal' action='?do=update' method='post'>

        <input type='hidden' name='userid' value="<?php echo $UserId ?>">

        <div class='form-group row'>
            <label for='email' class='col-sm-2 col-form-label'>Email</label>
            <div class='col-sm-10'>
                <input type='email' class='form-control' id='email' name='email' value="<?php echo $row['Email'] ?>"
                    required>
            </div>
        </div>

        <div class='form-group row'>
            <label for='password' class='col-sm-2 col-form-label'>password</label>
            <div class='col-sm-10'>
                <input type='hidden' name='oldpassword' value="<?php echo $row['Password'] ?>">
                <input type='password' class='form-control' id='password' name='newpassword'
                    placeholder='Leave Blank If You don t Want To Change'>
            </div>
        </div>

        <div class='form-group row'>
            <label for='username' class='col-sm-2 col-form-label'>Username</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' id='username' name='username'
                    value="<?php echo $row['UserName'] ?>" required>
            </div>
        </div>

        <div class='form-group row'>
            <label for='fullname' class='col-sm-2 col-form-label'>fullname</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' id='fullname' name='fullname'
                    value="<?php echo $row['FullName'] ?>" required>
            </div>
        </div>

        <div class='form-group row'>
            <div class='col-sm-10 offset-sm-2'>
                <button type='submit' class='btn btn-primary'><i class='fad fa-save'></i> Save</button>
            </div>
        </div>

    </form>
</div>

<?php
            } else {
                echo "<div class='container my-3'>";
                redirectHome("<div class = 'alert alert-danger'>Theres No Such ID</div>");
                echo '</div>';
            }
            // 55555555555555555555555555555555555555
        } elseif ( $do == 'update' ) {
            echo "<h1 class='text-center display-4 mt-3'>Update Member</h1>";
            if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
                $id 	 = $_POST['userid'];
                $user 	 = $_POST['username'];
                $email 	 = $_POST['email'];
                $name 	 = $_POST['fullname'];
                // Password Trick
                $password = empty( $_POST['newpassword'] ) ? $_POST['oldpassword'] : sha1( $_POST['newpassword'] );
                // Validate The Form
                $formErrors = [];
                if ( strlen( $user ) < 4 ) {
                    $formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
                }
                if ( strlen( $user ) > 20 ) {
                    $formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
                }
                if ( empty( $user ) ) {
                    $formErrors[] = 'Username Cant Be <strong>Empty</strong>';
                }
                if ( empty( $name ) ) {
                    $formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
                }
                if ( empty( $email ) ) {
                    $formErrors[] = 'Email Cant Be <strong>Empty</strong>';
                }
                // Loop Into Errors Array And Echo It
                echo "<div class='container my-3'>";
                foreach ( $formErrors as $error ) {
                    echo "<div class = 'alert alert-danger my-2'>" . $error . '</div>';
                }
                echo '</div>';
                // Check If There's No Error Proceed The Update Operation
            if ( empty( $formErrors ) ) {

                $stmt2 = $con->prepare( "SELECT 
                                            *
                                        FROM 
                                            users
                                        WHERE
                                            Username = ?
                                        AND 
                                            UserID != ?" );

                $stmt2->execute( array( $user, $id ) );

                $count = $stmt2->rowCount();
                if ($count == 1) {

                    $theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';
                    echo "<div class='container my-3'>";
                    redirectHome($theMsg, 'back');
                    echo '</div>';

                } else {

                // Update The Database With This Info
                $stmt = $con->prepare( 'UPDATE users SET UserName = ?, Email = ?, FullName = ?, Password = ? WHERE UserId = ?' );
                $stmt->execute( [$user, $email, $name, $password, $id] );

                echo "<div class='container my-3'>";
                redirectHome( "<div class='alert alert-success'>". $stmt->rowCount() .' Record Updated</div>', 1 );
                echo '</div>';
                }
            }
        } else {
            echo "<div class='container my-3'>";
            redirectHome( "<div class = 'alert alert-danger'>Sorry You Cant Browse This Page Directly</div>" );
            echo '</div>';
        }
    }
    // 666666666666666666666666666666666666
    elseif ( $do == 'delete' ) {
        // Delete Member Page
        echo "<h1 class='text-center display-4 mt-3'>Delete Member</h1>";
        echo "<div class='container my-3'>";
        // Check If Get Request userid Is Numeric & Get The Integer Value Of It
        $UserId = isset( $_GET['UserId'] ) && is_numeric( $_GET['UserId'] ) ? intval( $_GET['UserId'] ) : 0;
        // Select All Data Depend On This ID
        $check = checkItem( 'UserId', 'users', $UserId );
        if ( $check > 0 ) {
            // If There's Such ID Show The Form
        $stmt = $con->prepare( 'DELETE FROM users WHERE UserId = :user' );
        $stmt->bindParam( ':user', $UserId );
        $stmt->execute();
        redirectHome("<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>');
        } else {
            redirectHome("<div class = 'alert alert-danger'>This ID is Not Exist</div>");
        }
        echo '</div>';
    // 7777777777777777777777777777777777777777777
    }elseif ($do == 'activate') {
        echo "<h1 class='text-center display-4 mt-3'>Activate Member</h1>";
        echo "<div class='container my-3'>";
            // Check If Get Request userid Is Numeric & Get The Integer Value Of It
            $UserId = isset($_GET['UserId']) && is_numeric($_GET['UserId']) ? intval($_GET['UserId']) : 0;
            // Select All Data Depend On This ID
            $check = checkItem('UserId', 'users', $UserId);
            // If There's Such ID Show The Form
            if ( $check > 0 ) {
                $stmt = $con->prepare( 'UPDATE users SET RegStatus = 1 WHERE UserId = ?' );
                $stmt->execute( [$UserId] );
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
                redirectHome( $theMsg );
            } else {
                $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';
                redirectHome( $theMsg );
            }
            echo '</div>';
        }
        include $tpl . 'footer.php';
    } else {
        header( 'Location: index.php' );
        exit();
    }
    ?>