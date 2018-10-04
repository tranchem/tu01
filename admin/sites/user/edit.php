<?php
	$xp = new XTemplate('views/user/edit.html');
	$id = $_GET['id'];
	$do_save=1;

	$sql = "SELECT user_name,user_type,email,full_name,birthday,gender,address 
		FROM users WHERE user_name = '{$id}'";
	$rs = $db->fetchOne($sql);
	//print_r($rs);
	$xp->assign('user_name',$rs['user_name']);
	$xp->assign('user_type',$rs['user_type']);
	$xp->assign('email',$rs['email']);
	$xp->assign('full_name',$rs['full_name']);
	$xp->assign('birthday',$rs['birthday']);
	$xp->assign('gender',$rs['gender']);
	$xp->assign('address',$rs['address']);

	if($_POST){
		$user_type=$_POST['user_type'];
		$email=$_POST['email'];
		$full_name=$_POST['full_name'];
		$birthday=$_POST['birthday'];
		$gender=$_POST['gender'];
		$address=$_POST['address'];

		if($do_save==1){
			$sql="UPDATE `users` SET `user_type` = '$user_type', `email` = '$email', `full_name` = '$full_name', `birthday` = '$birthday', `gender` = b'$gender', `address` = '$address' WHERE `users`.`user_name` = '$id'";
			$db->execSQL($sql);
			$f->redir('?m=user&a=list');
		}
	}
	$xp->parse('EDIT');
	$acontent = $xp->text('EDIT');
?>