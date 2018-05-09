<?php
	// Error Reporting

	ini_set('display_errors', 'On');
	error_reporting(E_ALL);

	include 'admin/connect.php';

	$sessionUser = '';
	if (isset($_SESSION['user'])) {
		$sessionUser = $_SESSION['user'];
	}

	// Routes
	$tpl 	= 'includes/templates/'; // Template Directory
	$lang 	= 'includes/languages/'; // Language Directory
	$func	= 'includes/functions/'; // Functions Directory
	$css 	= 'layout/css/'; // Css Directory
	$js 	= 'layout/js/'; // Js Directory

//this way to change language in template  if (isset($opEng)){include $lang . "english.php";}elseif (isset($opFrn)){include $lang . "francais.php";}
	// Include The Important Files

	include $func . 'functions.php';
//    include $lang . "english.php";
// coondition for change language by cookie php
if (empty($_COOKIE["chooseLang"]) || !isset($_COOKIE["chooseLang"])){
    $cookie_time_Onset = 60 * 60 * 24 * 30 + time();
    setcookie("chooseLang", "eng", $cookie_time_Onset);
	include $lang . "english.php";
}else{
    if ($_COOKIE["chooseLang"] === "eng"){
        include $lang . "english.php";
    }
    if ($_COOKIE["chooseLang"] === "arb"){
        include $lang . "arabic.php";
    }
}
		include $tpl . "header.php";

?>
	

	