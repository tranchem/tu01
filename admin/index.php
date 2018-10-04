<?php
include ('../libs/bootstrap.php');
require_once('../libs/adminstatus.php');
if(!isset($_SESSION['user_name']))
{
	$_SESSION['user_name']='';
	$_SESSION['email']='';
	$_SESSION['user_type']='';
}
$xpt=new XTemplate ('views/layout.html');
if(isset($_GET['m']))
{
	$m=$_GET['m'];
	if(isset($_GET['a'])){
		$a=$_GET['a'];
		if(file_exists("sites/{$m}/{$a}.php")){
			include ("sites/{$m}/{$a}.php");
		}
		else{
			include ("sites/error/404.php");
		}
	}
	else
	{
		if(file_exists("sites/{$m}.php")){
			include ("sites/{$m}.php");
		}
		else{
			include ("sites/error/404.php");
		}
	}
}
else{
	include ("sites/error/404.php");
}

if(strlen($_SESSION['user_name'])!=0)
{
	$xpt->assign('user_name',$_SESSION['user_name']);
}
$xpt->assign('baseUrl',$baseUrl);
$xpt->assign('acontent',$acontent);
$xpt->parse('LAYOUT');
$xpt->out('LAYOUT');
?>