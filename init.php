<?php

//database
include "admin/connect.php";
$sessionUser="";
if ( isset( $_SESSION['user'] ) ) {
$sessionUser = $_SESSION['user'];
}

//routes
$css = 'layout/css/';          //css directory
$js = 'layout/js/';            //js directory
$img = 'layout/images/';       //images directory
$lang = 'includes/languages/'; //language directory
$tpl = 'includes/templates/';  //template directory
$func = 'includes/functions/';  //functions directory

//include the important files
include $func.'functions.php';
include $lang.'english.php';
include $tpl.'header.php';

// Include Navbar On All Pages Expect The One With $noNavbar Variable
if (!isset($noNavbar)) { include $tpl . 'navbar.php'; }


?>