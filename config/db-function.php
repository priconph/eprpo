<?php
function fetch($result){
	$row = mysql_fetch_object($result);
	return $row;
}

function error(){
	return mysql_error();
}
function errornum(){
	return mysql_errno();
}

function query($query){
	$result = mysql_query($query);
	if(!$result){
		echo error();
	}
	else{
		return $result;
	}
}



function getTotalRows($tablename){
        $query = "select * from $tablename";
        $rs = mysql_query($query);
        $count = mysql_num_rows($rs);
        return $count;
}

function getNumRows($result){
        $count = mysql_num_rows($result);
        return $count;
}

function escapeString($str){
	$str = trim($str);
	$str = str_replace("&#39", "'", $str);
	$str = mysql_real_escape_string($str);
	
	$str = str_replace("\\\n", "''", $str);
	//$str = stripslashes($str);
	//$str = str_replace("'", "''", $str);
	$str = str_replace("\\%", "''", $str);
	$str = str_replace("\\'", "''", $str);
	$str = utf8_decode($str);
	return $str;
}

function strUtfDecode($str){
	$str = trim($str);
	$str = str_replace("&#39", "'", $str);
	$str = mysql_real_escape_string($str);
	$str = str_replace("\\\n", "''", $str);
	$str = str_replace("\\%", "''", $str);
	$str = str_replace("\\'", "''", $str);
	$string = utf8_decode($str);
	return $string;
}

function emailDecode($str){
	$str = trim($str);
	$string = utf8_decode($str);
	return $string;
}

function strUtfEncode($str){ 
	$str = str_replace("'", '&#39', $str);
	$string = utf8_encode($str);
	return $string;
}



function singleQuotesReverse($str){ 
	$string = str_replace("&#39", "'", $str);
	return $string;
}





@define('ROOT', __DIR__); // for PHP >= 5.3
//@define('ROOT', realpath(dirname(__FILE__)));  // for PHP < 5.3


@session_start();
$userid = (isset($_SESSION['eojsjfwoIjeKkdnlEOi']))?$_SESSION['eojsjfwoIjeKkdnlEOi']:'';

@define('USERID', $userid);




?>