<?php

	$dsn = 'mysql:host=localhost;dbname=shop';
	$userdb = 'root';
	$passdb = '12345678';
	$option = array(
		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
	);

	try {
		$con = new PDO($dsn, $userdb, $passdb, $option);
		$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
		echo 'Failed To Connect' . $e->getMessage();
	}
	?>


