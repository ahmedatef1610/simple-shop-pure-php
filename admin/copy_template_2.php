<?php
ob_start();
// Output Buffering Start
session_start();
$pageTitle = 'PageName';
if ( isset( $_SESSION['UserName'] ) ) {
    include 'init.php';

    $do = isset( $_GET['do'] ) ? $_GET['do'] : 'manage';

    if ( $do == 'manage' ) {
        // 11111111111111111111111111111111111

    } elseif ( $do == 'add' ) {
        // 222222222222222222222222222222222

    } elseif ( $do == 'insert' ) {
        // 33333333333333333333333333333333333333333

    } elseif ( $do == 'edit' ) {
        // 44444444444444444444444444444444444444
        echo "<h1 class='text-center display-4 mt-3'>Delete Member</h1>";
        echo "<div class='container my-3'>";

        echo '</div>';

    } elseif ( $do == 'update' ) {
        // 55555555555555555555555555555555555555

    } elseif ( $do == 'delete' ) {
        // 666666666666666666666666666666666666

    } elseif ( $do == 'activate' ) {
        // 7777777777777777777777777777777777777777777

    }

    include $tpl . 'footer.php';
} else {
    header( 'Location: index.php' );
    exit();
}
ob_end_flush();
// Release The Output

?>

<?php

foreach ($comments as $comment) {
    echo '<pre>';
    print_r($comment);
    echo '</pre>';
}

?>