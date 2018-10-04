<?php
include ('libs/bootstrap.php');
if(!isset($_SESSION['user_name']))
{
	//header('refresh:0');
	$_SESSION['user_name']='';
	$_SESSION['email']='';
	$_SESSION['user_type']='';
}
//$m=$_GET['m'];
$xpt=new XTemplate ('views/layout.html');
include ('sites/login.php');
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




if(strlen($_SESSION['user_name'])==0)
{
	$xpt->assign('signin_status','LOG IN');
	$xpt->assign('baseUrl',$baseUrl);
	$xpt->parse('LAYOUT.LOGIN');
}
else if($_SESSION['user_type']=='user')
{
	$xpt->assign('signin_status','USER');
	$xpt->assign('baseUrl',$baseUrl);
	$xpt->parse('LAYOUT.LOGGEDUSER');
}
else if($_SESSION['user_type']=='admin' || $_SESSION['user_type']=='owner')
{
	$xpt->assign('signin_status','ADMIN');
	$xpt->assign('admin_name',$_SESSION['user_name']);
	$xpt->assign('baseUrl',$baseUrl);
	$xpt->parse('LAYOUT.LOGGEDADMIN');
}

$xpt->assign('baseUrl',$baseUrl);
$xpt->assign('acontent',$acontent);
$xpt->parse('LAYOUT');
$xpt->out('LAYOUT');
?>