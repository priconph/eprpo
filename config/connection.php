<?php

	$host = "127.0.0.1";
	// $dbname = "pricon-prpo-db_v3"; // test db
	$dbname = "pricon-prpo-db"; // LIVE
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