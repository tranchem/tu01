<?php
	$xp = new XTemplate('views/user/add.html');
	$do_signup=1;
	$sql="select * from users where 1=1";
	$rs=$db->fetchAll($sql);

	if($_POST){
		$user_name=$_POST['user_name'];
		$user_type=$_POST['user_type'];
		$pwd=$_POST['password'];
		$pwdConfirm=$_POST['re_password'];
		$email=$_POST['email'];
		$full_name=$_POST['full_name'];
		$birthDate=$_POST['birthday'];
		$gender=$_POST['gender'];
		$address=$_POST['address'];

		//Error message
		if(!empty($_POST['gender']))
		{
			$gender=$_POST['gender'];
		}
		else
		{
			$xp->assign('err_gender','You must choose your gender');
			$do_signup=-1;
		}
		if(strlen($user_name)==0)
		{
			$xp->assign('err_username','You must enter first name');
			$do_signup=-1;
		}
		if(strlen($full_name)==0)
		{
			$xp->assign('err_fullname','You must enter full name');
			$do_signup=-1;
		}
		if(strlen($email)==0)
		{
			$xp->assign('err_email','You must enter email');
			$do_signup=-1;
		}
		if($do_signup==1&&$f->checkEmail($email)=='NO')
		{
			$xp->assign('err_email','Wrong email type');
			$do_signup=-1;
		}
		if($do_signup==1)
		{
			foreach($rs as $r)
			{
				if($r['email']==$email)
				{
					$xp->assign('err_email','This email is already used');
				}
			}
		}
		if(strlen($pwd)==0)
		{
			$xp->assign('err_pass','You must enter password');
			$do_signup=-1;
		}
		if($do_signup==1&&strlen($pwd)<6)
		{
			$xp->assign('err_pass','Password must be longer than 6 characters');
			$do_signup=-1;
		}
		if(strlen($pwdConfirm)==0)
		{
			$xp->assign('err_passConfirm','Please confirm password');
			$do_signup=-1;
		}
		if($do_signup==1&&$pwd!=$pwdConfirm)
		{
			$xp->assign('err_passConfirm','Please reconfirm your password!');
			$do_signup=-1;
		}
		if(strlen($birthDate)==0)
		{
			$xp->assign('err_birth','You must enter birthdate');
			$do_signup=-1;
		}
		if(strlen($address)==0)
		{
			$xp->assign('err_address','You must enter address');
			$do_signup=-1;
		}
		if($do_signup==1)
		{
			$pwdHash=md5($pwd);
			$sql="insert into users (user_name,user_password,user_type,email,full_name,birthday,gender,address)
			values ('{$user_name}','{$pwdHash}','user','{$email}','{$full_name}','{$birthDate}',b'{$gender}','{$address}')";
			if($db->execSQL($sql))
			{
				$f->redir('?m=user&a=list');
			}
		}

	}
	$xp->parse('ADD');
	$acontent = $xp->text('ADD');
?>