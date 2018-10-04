<?php

include('../libs/bootstrap.php');
$xp = new XTemplate ("login.html");

$email='';
$pwd='';
$do_login=1;

$_SESSION['user_name']='';
$_SESSION['email']='';
$_SESSION['user_type']='';

$sql='select * from users where 1=1';
$rs=$db->fetchAll($sql);

if($_POST)
{
	$email=$_POST['txtEmail'];
	$pwd=$_POST['txtPass'];
	if(strlen($email)==0)
	{
		$xp->assign('email_err','Please enter email!');
		$do_login=-1;
	}
	if($do_login==1&&$f->checkEmail($email)=='NO')
	{
		$xp->assign('email_err','Please enter correct email type');
		$do_login=-1;
	}
	if($do_login==1&&strlen($pwd)==0)
	{
		$xp->assign('pass_err','Please enter password!');
		$do_login=-1;
	}
	if($do_login==1)
	{
		$pwdHash=md5($pwd);
		foreach ($rs as $r)
		{
			$xp->assign('email_err','');
			$xp->assign('pass_err','');
			if($email!=$r['email'])
			{
				$xp->assign('email_err','Unexisted email!');
				$do_login=-1;

			}
			else if($pwdHash!=$r['user_password'])
			{
				$xp->assign('pass_err','Wrong password!');
				$do_login=-1;
				break;
			}
			else if($r['user_type']!='admin'&&$r['user_type']!='owner')
			{
				$xp->assign('email_err','This email does not have the authority to log in!');
				$do_login=-1;
				break;
			}
			else
			{
				$_SESSION['user_name']=$r['user_name'];
				$_SESSION['email']=$r['email'];
				$_SESSION['user_type']=$r['user_type'];
				$f->redir($baseUrl.'/admin/?m=restaurant&a=list');
			}
		}
	}
	
}

$xp->assign('txtEmail',$email);
$xp->assign('txtPass',$pwd);
$xp->assign('baseUrl',$baseUrl);
$xp->parse('ADMINLOGIN');
$xp->out('ADMINLOGIN');