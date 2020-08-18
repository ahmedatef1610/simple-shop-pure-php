<?php
ob_start();
session_start();
$noNavbar = '';
$pageTitle = 'Login | Signup' ;

if ( isset( $_SESSION['user'] ) ) {
    header( 'Location: index.php' );
    exit();
}

include 'init.php';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

    if ( isset( $_POST['login'] ) ) {
        $user = $_POST['username'];
        $password = $_POST['password'];
        $hashPassword = sha1( $password );

        $stmt = $con->prepare( "SELECT UserId,Email,Password,UserName 
                            FROM users 
                            WHERE UserName = ? AND Password = ?
                            LIMIT 1 " );
        $stmt->execute( [ $user, $hashPassword ] );
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        // If Count > 0 This Mean The Database Contain Record About This Email
        if ( $count > 0 ) {
            // Register Session UserName
            $_SESSION['user'] = $row['UserName'];
            // Register Session Email
            $_SESSION['userEmail'] = $row['Email'];
            // Register Session UserId
            $_SESSION['userId'] = $row['UserId'];
            // Redirect To Dashboard Page
            header( 'Location: index.php' );
            exit();
        }
    } elseif ( isset( $_POST['signup'] ) ) {
        $formErrors = [];

        $username 	 = $_POST['username'];
        $password 	 = $_POST['password'];
        $password2 	 = $_POST['password2'];
        $email 		 = $_POST['email'];

        if ( isset( $username ) ) {
            $filteredUser = filter_var( $username, FILTER_SANITIZE_STRING );
            if ( strlen( $filteredUser ) < 4 ) {
                $formErrors[] = 'Username Must Be Larger Than 4 Characters';
            }
        }

        if ( isset( $password ) && isset( $password2 ) ) {
            if ( empty( $password ) ) {
                $formErrors[] = 'Sorry Password Cant Be Empty';
            }
            if ( sha1( $password ) !== sha1( $password2 ) ) {
                $formErrors[] = 'Sorry Password Is Not Match';
            }
        }

        if ( isset( $email ) ) {
            $filteredEmail = filter_var( $email, FILTER_SANITIZE_EMAIL );
            if ( filter_var( $filteredEmail, FILTER_VALIDATE_EMAIL ) != true ) {
                $formErrors[] = 'This Email Is Not Valid';
            }
        }

        // Check If There's No Error Proceed The User Add
        if (empty($formErrors)) {
            // Check If User Exist in Database
            $check = checkItem("UserName", "users", $username);
            if ($check == 1) {
                $formErrors[] = 'Sorry This User Is Exists';
            } else {
                // Insert User info In Database
                $stmt = $con->prepare("INSERT INTO 
                                        users(UserName, Password, Email, RegStatus, Date)
                                        VALUES(:user, :password, :email, 0, now())");
                $stmt->execute([
                    'user' => $username,
                    'password' => sha1($password),
                    'email' => $email
                ]);
                // Echo Success Message
                $successMsg = 'Congrats You Are Now Registered User';
            }

        }
    }
}

?>

<div class='container my-5 login-page'>

    <h1 class='text-center'> <span class='selected' data-class='login'>Login</span> | <span class='' data-class='signup'>Signup</span> </h1>

    <form action='<?php echo $_SERVER['PHP_SELF'] ?>' method='post' class='w-50 mx-auto mt-3 login'>
        <div class='form-group'>
            <label for='username'>Username</label>
            <div class='input-group flex-nowrap'>
                <div class='input-group-prepend'>
                    <span class='input-group-text'><i class='fad fa-user'></i></span>
                </div>
                <input type='text' class='form-control' id='username' name='username' placeholder='Username'
                    value='<?php echo ( isset( $username ) )?$username:'' ?>' required>
            </div>
        </div>
        <div class='form-group'>
            <label for='password'>Password</label>
            <div class='input-group flex-nowrap'>
                <div class='input-group-prepend'>
                    <span class='input-group-text'><i class='fad fa-key'></i></span>
                </div>
                <input type='password' class='form-control' id='password' name='password' placeholder='Password'
                    value='<?php echo ( isset( $password ) )?$password:'' ?>' required>
            </div>
        </div>
        <button type='submit' name='login' class='btn btn-primary btn-block my-4'> <i class='fad fa-sign-in'></i>
            Login</button>
    </form>

    <form action='<?php echo $_SERVER['PHP_SELF'] ?>' method='post' class='w-50 mx-auto mt-3 signup'>
        <div class='form-group'>
            <label for='username'>Username</label>
            <div class='input-group flex-nowrap'>
                <div class='input-group-prepend'>
                    <span class='input-group-text'><i class='fad fa-user'></i></span>
                </div>
                <input type='text' class='form-control' id='username' name='username' placeholder='Username'
                    value='<?php echo ( isset( $username ) )?$username:'' ?>' pattern=".{4,}" title="Username Must Be Between 4 Chars" required>
            </div>
        </div>
        <div class='form-group'>
            <label for='password'>Password</label>
            <div class='input-group flex-nowrap'>
                <div class='input-group-prepend'>
                    <span class='input-group-text'><i class='fad fa-key'></i></span>
                </div>
                <input type='password' class='form-control' id='password' name='password' placeholder='Password'
                    value='<?php echo ( isset( $password ) )?$password:'' ?>' minlength="4" required>
            </div>
        </div>
        <div class='form-group'>
            <label for='password'>Confirm Password</label>
            <div class='input-group flex-nowrap'>
                <div class='input-group-prepend'>
                    <span class='input-group-text'><i class='fad fa-key-skeleton'></i></span>
                </div>
                <input type='password' class='form-control' id='password' name='password2'
                    placeholder='Confirm Password' value='<?php echo ( isset( $password2 ) )?$password2:'' ?>' minlength="4" required>
            </div>
        </div>
        <div class='form-group'>
            <label for='email'>Email</label>
            <div class='input-group flex-nowrap'>
                <div class='input-group-prepend'>
                    <span class='input-group-text'><i class='fad fa-at'></i></span>
                </div>
                <input type='email' class='form-control' id='email' name='email' placeholder='Email'
                    value='<?php echo ( isset( $email ) )?$email:'' ?>' required>
            </div>
        </div>
        <button type='submit' name='signup' class='btn btn-success btn-block my-4'> <i class='fad fa-user-plus'></i>
            Signup</button>
    </form>

    <div class="the-errors text-center w-50 mx-auto mt-3">
        <?php 

            if (!empty($formErrors)) {
                foreach ($formErrors as $error) {
                    echo '<div class = "alert alert-danger">' . $error . '</div>';
                }
            }

            if (isset($successMsg)) {
                echo '<div class = "alert alert-success">' . $successMsg . '</div>';
            }

            ?>
    </div>
</div>

<?php
include $tpl.'footer.php';
        ob_end_flush();
        ?>