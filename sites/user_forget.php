<?php
	$xp=new XTemplate ('views/user_forget.html');
	$do_update = 1;
	$isExists = true;
	$check = false;
	if(isset($_POST['submit']))
	{
		$email=$_POST['email'];
		$sql="select * from users where 1=1";
		$rs=$db->fetchAll($sql);

		if(strlen($email)==0)
		{
			$xp->assign('err_email','You must enter email');
			$do_update=-1;
		}
		if($do_update==1&&$f->checkEmail($email)=='NO')
		{
			$xp->assign('err_email','Wrong email type');
			$do_update=-1;
		}
		if ($do_update==1)
		{
			foreach($rs as $r)
			{
				if($email!=$r['email'])
				{
					$do_update=-1;
					$isExists=false; 
				}
				else
				{
					$isExists=true;
					$check=true;
					break;
				}
			}
		}
		if($isExists===false){
			$xp->assign('err_email','Unexisted email');
		}
		
		// Declare PHPMailer library
		if($check===true)
		{
			require "libs/PHPMailer/src/PHPMailer.php";
			require "libs/PHPMailer/src/Exception.php";
			require "libs/PHPMailer/src/SMTP.php";
			$mail = new PHPMailer\PHPMailer\PHPMailer(true);   
			$randomPassword = $f->randomPassword();

			$emailBody = "<div>Hello " . $email . "!, <br/><p>New password: " . $randomPassword . "</p><br/><br/><p>Use new password to login and change your password !</p><br/></br></p>Regards,<br> Admin Merciado Park.</div>";

			$mail->IsSMTP();
			$mail->SMTPDebug = 0;
			$mail->SMTPAuth = TRUE;
			$mail->SMTPSecure = "tls";
			$mail->Port     = 587;  
			$mail->Username = 'merciadopark@gmail.com';
			$mail->Password = 'aq1sw2de3fr4';
			$mail->Host     = 'smtp.gmail.com';
			$mail->Mailer   = 'smtp';

			$mail->SetFrom('merciadopark@gmail.com', 'Admin');
			$mail->AddReplyTo('merciadopark@gmail.com', 'Admin');
			$mail->ReturnPath='merciadopark@gmail.com';	
			$mail->AddAddress($email);
			$mail->Subject = "Forgot Password Recovery";		
			$mail->MsgHTML($emailBody);
			$mail->IsHTML(true);

			if(!$mail->Send()) {
				$xp->assign('sent_err','Message could not be sent! Mailer Error: '.$mail->ErrorInfo);
			} else {
				$newPassHash = md5($randomPassword);	
				$query = "update users set user_password='{$newPassHash}' where email='{$email}'";
				if($db->execSQL($query)){
					$xp->assign('sent_success','New password has been sent to your email !');
				}
			}	
		}
	}

	$xp->assign('baseUrl',$baseUrl);
	$xp->parse('FORGET');
	$acontent=$xp->text('FORGET');
?>