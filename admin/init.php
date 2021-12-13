<?php
include('connect.php');
//routes

//admin index
$css = 'layout/css/';
$js = 'layout/js/';
$templates = 'includes/templates/';
$lang = 'includes/languages/';
$func = 'includes/functions/';

include($lang . 'en.php');
include($func . 'redirect_function.php');

include($func . 'functions.php');
include($templates . "header.php");

//include navigation bar
if (!isset($no_nav)) {
    include $templates . 'nav_bar.php';
}
?>