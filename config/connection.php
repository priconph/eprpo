<?php

	$host = "127.0.0.1";
	// $dbname = "db_eprpo_test";
	$dbname = "pricon-prpo-db";
	// $dbname = "pricon-prpo-db_finaltest";	
	// $dbname = "pricon-prpo-db_20200721";	
	$username = "root";
	$password = "newd3v";

	@$conn = mysql_connect($host,$username,$password);
	@$db = mysql_select_db($dbname);
	if(!$conn){
		$errornum = mysql_errno();
		$errordesc = mysql_error();
		die('Database Error '.$errornum.':'.$errordesc);
	}
	else if(!$db){
		die('INVALID DATABASE');
	}

	date_default_timezone_set("asia/manila");


?>