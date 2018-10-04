<?php
	session_start();
	include ('Model.class.php');
	include ('func.class.php');
	include ('XTemplate.class.php');
	$baseUrl ='http://'.$_SERVER['HTTP_HOST'].'/eProject01';
	$usr="root";
	$pwd="";
	$dbName="amusementpark";
	$db = new Model ($usr,$pwd,$dbName);
	$f = new Func;
?>