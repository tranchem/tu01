<?php
$xp = new XTemplate ("views/user_signup.html");

$sql="select * from users where 1=1";
$rs=$db->fetchAll($sql);

$do_signup=1;
if(isset($_POST['btnSignUp']))
{
	$username=$_POST['txtUsername'];
	$firstName=$_POST['txtFirstName'];
	$lastName=$_POST['txtLastName'];
	$email=$_POST['txtEmail'];
	$pwd=$_POST['txtPass'];
	$pwdConfirm=$_POST['txtPassConfirm'];
	$birthDate=$_POST['txtBirth'];
	if(isset($_POST['radioGender']))
	{
		$gender=$_POST['radioGender'];
	}
	else
	{
		$xp->assign('err_gender','You must choose your gender');
		$do_signup=-1;
	}
	$address=$_POST['txtAddress'];

	if(strlen($firstName)==0)
	{
		$xp->assign('err_fname','You must enter first name');
		$do_signup=-1;
	}
	if(strlen($lastName)==0)
	{
		$xp->assign('err_lname','You must enter last name');
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
	if(strlen($username)==0)
	{
		$xp->assign('err_username','You must enter username');
		$do_signup=-1;
	}
	if($do_signup==1&&strlen($username)<6)
	{
		$xp->assign('err_username','Username must be longer than 6 characters');
		$do_signup=-1;
	}
	if($do_signup==1)
	{
		foreach($rs as $r)
		{
			if($r['email']==$email)
			{
				$xp->assign('err_email','This email is already used');
				$do_signup=-1;
			}
			if($r['user_name']==$username)
			{
				$xp->assign('err_username','This username is already used');
				$do_signup=-1;
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
		$fullname="{$firstName} {$lastName}";
		$pwdHash=md5($pwd);
		$sql="insert into users (user_name,user_password,user_type,email,full_name,birthday,gender,address)
		values ('{$username}','{$pwdHash}','user','{$email}','{$fullname}','{$birthDate}',b'{$gender}','{$address}')";
		if($db->execSQL($sql))
		{
			//echo $sql;
			$_SESSION['user_name']=$username;
			$_SESSION['email']=$email;
			$_SESSION['user_type']='user';
			$f->redir($baseUrl.'/?m=home');		
		}
	}
	$xp->assign('txtFirstName',$firstName);
	$xp->assign('txtLastName',$lastName);
	$xp->assign('txtEmail',$email);
	$xp->assign('txtBirth',$birthDate);
	$xp->assign('txtAddress',$address);
}


$xp->parse('USERSIGNUP');
$acontent=$xp->text('USERSIGNUP');