<?php
ob_start(); // Output Buffering Start
session_start();
$pageTitle = 'PageName';
if ( isset( $_SESSION['UserName'] ) ) {
    include 'init.php';
    /* Start PageName Page */
?>

<div class='container mt-3'>
    <h4 class='text-center display-4'>PageName</h4>
    <p class='text-center lead'>
        <?php echo 'Welcome '.$_SESSION['UserName']?>
    </p>
</div>


<?php
    /* End PageName Page */
    include $tpl . 'footer.php';
} else {
    $noNavbar = '';
    include 'init.php';
    echo "<div class = 'container py-3'><p class = 'text-center lead'>you are not Authoriztion to view this page</p></div>";
    header( 'refresh:3;url = index.php' );
    exit();
}
ob_end_flush(); // Release The Output
?>