<?php
$xp= new XTemplate('views/account/changepass.html');
require_once('libs/userstatus.php');

$sql="select * from users where user_name='{$_SESSION['user_name']}'";
$rs=$db->fetchAll($sql);
$do_update=1;

if($_POST)
{
	$curPass=$_POST['txtCurPass'];
	$newPass=$_POST['txtNewPass'];
	$passConfirm=$_POST['txtPassConfirm'];

	if(strlen($curPass)==0)
	{
		$xp->assign('err_curPass','You must enter your current password');
		$do_update=-1;
	}
	if(strlen($newPass)==0)
	{
		$xp->assign('err_newPass','You must enter your new password');
		$do_update=-1;
	}
	if($do_update==1&&strlen($newPass)<6)
	{
		$xp->assign('err_newPass','Your new password must be longer than 6 characters');
		$do_update=-1;
	}
	if(strlen($passConfirm)==0)
	{
		$xp->assign('err_passConfirm','You must confirm your password');
		$do_update=-1;
	}
	if($do_update==1&&$newPass!=$passConfirm)
	{
		$xp->assign('err_passConfirm','Password and password confirm must be the same');
		$do_update=-1;
	}
	if($do_update==1)
	{
		foreach($rs as $r)
		{
			$curPassHash=md5($curPass);
			if($curPassHash!=$r['user_password'])
			{
				$xp->assign('err_curPass','Wrong password');
				$do_update=-1;
			}
			else
			{
				$newPassHash=md5($newPass);
				$sql="update users set user_password='{$newPassHash}' where user_name='{$_SESSION['user_name']}'";
				if($db->execSQL($sql))
				{
					$xp->assign('txt_success','Change password successfully!');
					$f->redir($baseUrl.'/?m=account&a=info');
				}
			}
		}
	}
	$xp->assign('curPass',$curPass);
	$xp->assign('newPass',$newPass);
	$xp->assign('passConfirm',$passConfirm);
}

$xp->assign('baseUrl',$baseUrl);
$xp->parse('CHANGEPASS');
$acontent=$xp->text('CHANGEPASS');