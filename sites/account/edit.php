<?php
$xp= new XTemplate('views/account/edit.html');
require_once('libs/userstatus.php');

$sql="select * from users where 1=1";
$rs=$db->fetchAll($sql);
$do_update=1;
foreach($rs as $r)
{
	if($_SESSION['user_name']==$r['user_name'])
	{
		$xp->assign('curFullName',$r['full_name']);
		$xp->assign('curBirth',$r['birthday']);
		$xp->assign('curAddress',$r['address']);
		$male = ($r['gender']==1)?'checked':'';
		$female = ($r['gender']==0)?'checked':'';
		$xp->assign('checkmale',$male);
		$xp->assign('checkfemale',$female);
		break;
	}
}

if($_POST)
{
	$newFullName=$_POST['txtFullName'];
	$newBirth=$_POST['txtBirth'];
	$newAddress=$_POST['txtAddress'];

	if(isset($_POST['radioGender']))
	{
		$newGender=$_POST['radioGender'];
	}
	else
	{
		$xp->assign('err_gender','You must choose your gender');
		$do_update=-1;
	}

	if(strlen($newFullName)==0)
	{
		$xp->assign('err_fname','You must enter full name');
		$do_update=-1;
	}
	if(strlen($newBirth)==0)
	{
		$xp->assign('err_birth','You must enter birthdate');
		$do_update=-1;
	}
	if(strlen($newAddress)==0)
	{
		$xp->assign('err_address','You must enter address');
		$do_update=-1;
	}
	if($do_update==1)
	{
		$sql="update users set full_name='{$newFullName}', birthday='{$newBirth}', gender={$newGender}, address='{$newAddress}' where user_name='{$_SESSION['user_name']}'";
		if($db->execSQL($sql))
		{
			$f->redir($baseUrl.'/?m=account&a=info');		
		}
	}


	$xp->assign('curFullName',$newFullName);
	$xp->assign('curBirth',$newBirth);
	$xp->assign('curAddress',$newAddress);

}
$xp->assign('curName',$_SESSION['user_name']);
$xp->assign('baseUrl',$baseUrl);
$xp->parse('USEREDIT');
$acontent=$xp->text('USEREDIT');