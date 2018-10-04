<?php
if(strlen($_SESSION['user_name'])==0)
{
	$email='';
	$pwd='';
	$do_login=1;

	$_SESSION['user_name']='';
	$_SESSION['email']='';
	$_SESSION['user_type']='';

	$sql='select * from users where 1=1';
	$rs=$db->fetchAll($sql);

	if(isset($_POST['login']))
	{
		$email=$_POST['txtEmail'];
		$pwd=$_POST['txtPass'];
		if(strlen($email)==0)
		{
			$xpt->assign('email_err','Please enter email!');
			$do_login=-1;
		}
		if($do_login==1&&$f->checkEmail($email)=='NO')
		{
			$xpt->assign('email_err','Please enter correct email type');
			$do_login=-1;
		}
		if($do_login==1&&strlen($pwd)==0)
		{
			$xpt->assign('pass_err','Please enter password!');
			$do_login=-1;
		}
		if($do_login==1)
		{
			$pwdHash=md5($pwd);
			foreach ($rs as $r)
			{
				$xpt->assign('email_err','');
				$xpt->assign('pass_err','');
				if($email!=$r['email'])
				{
					$xpt->assign('email_err','Unexisted email!');
					$do_login=-1;
				}
				else if($r['user_type']!='user')
				{
					$xpt->assign('email_err','You cannot log in here!');
					$do_login=-1;
					break;
				}
				else if($pwdHash!=$r['user_password'])
				{
					$xpt->assign('pass_err','Wrong password!');
					$do_login=-1;
					break;
				}
				else
				{
					$_SESSION['user_name']=$r['user_name'];
					$_SESSION['email']=$r['email'];
					$_SESSION['user_type']=$r['user_type'];
					$f->redir($baseUrl.'/?m=home');
				}
			}
		}
	}

	$xpt->assign('txtEmail',$email);
	$xpt->assign('txtPass',$pwd);
}
else
{
	$xpt->assign('user_name',$_SESSION['user_name']);
}
