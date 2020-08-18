<?php session_start()?>
<?php if ( isset( $_SESSION['UserName'] ) ) {
    // Redirect To Dashboard Page
    header( 'Location: dashboard.php' );
    exit();
}
?>

<?php $noNavbar = '' ?>
<?php $pageTitle = 'Login' ?>
<?php include 'init.php'?>

<?php
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashPassword = sha1( $password );
    // Check If The User Exist In Database
    //statement
    $stmt = $con->prepare( "SELECT UserId,Email,Password,UserName 
                            FROM users 
                            WHERE Email = ? AND Password = ? AND GroupID = 1
                            LIMIT 1 " );
    $stmt->execute( [ $email, $hashPassword ] );
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    // If Count > 0 This Mean The Database Contain Record About This Email
    if ( $count > 0 ) {
        // Register Session UserName
        $_SESSION['UserName'] = $row['UserName'];
        // Register Session Email
        $_SESSION['Email'] = $row['Email'];
        // Register Session UserId
        $_SESSION['UserId'] = $row['UserId'];
        // Redirect To Dashboard Page
        header( 'Location: dashboard.php' );
        exit();
    }
}
?>

<div class='container py-3'>
    <h4 class='text-center display-4'>Admin Login</h4>
    <form class='contact-form login' action="<?php echo $_SERVER['PHP_SELF'] ?>" method='post'>
        <div class='form-group'>
            <label for='email'>Email</label>
            <div class='input-group flex-nowrap'>
                <div class='input-group-prepend'>
                    <span class='input-group-text'><i class='fad fa-user'></i></span>
                </div>
                <input type='email' class='form-control' id='email' name='email' placeholder='email'
                    value='<?php echo (isset($email))?$email:"" ?>' required>
            </div>
        </div>
        <div class='form-group'>
            <label for='username'>Password</label>
            <div class='input-group flex-nowrap'>
                <div class='input-group-prepend'>
                    <span class='input-group-text'><i class='fad fa-key'></i></span>
                </div>
                <input type='password' class='form-control' id='password' name='password' placeholder='password'
                    value='<?php echo (isset($password))?$password:"" ?>' required>
            </div>
        </div>
        <button type='submit' class='btn btn-primary btn-block my-4'> <i class='fad fa-sign-in'></i> Login</button>
    </form>
</div>

<?php include $tpl.'footer.php'?>