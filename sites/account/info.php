<?php
$xp= new XTemplate('views/account/info.html');
require_once('libs/userstatus.php');

$sql="select * from users where 1=1";
$rs=$db->fetchAll($sql);

foreach($rs as $r)
{
	if($_SESSION['user_name']==$r['user_name'])
	{
		$gender = $r['gender'];
		$r['gender'] = ($gender == 1)?'Male':'Female';
		$xp->assign('user_name',$r['user_name']);
		$xp->assign('user_email',$r['email']);
		$xp->assign('user_fullname',$r['full_name']);
		$xp->assign('user_bday',$r['birthday']);
		$xp->assign('user_gender',$r['gender']);
		$xp->assign('user_address',$r['address']);
		break;
	}
}

$xp->assign('baseUrl',$baseUrl);
$xp->parse('USERINFO');
$acontent=$xp->text('USERINFO');