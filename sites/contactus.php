<?php
$xp = new XTemplate ("views/contactus.html");
if($_POST){
	// Declare PHPMailer library
	require "libs/PHPMailer/src/PHPMailer.php";
	require "libs/PHPMailer/src/Exception.php";
	require "libs/PHPMailer/src/SMTP.php";
	$mail = new PHPMailer\PHPMailer\PHPMailer(true);                              // Passing `true` enables exceptions
	try {
	    //Server settings
	    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
	    $mail->isSMTP();                                      // Set mailer to use SMTP
	    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
	    $mail->SMTPAuth = true;                               // Enable SMTP authentication
	    $mail->Username = 'merciadopark@gmail.com';                 // SMTP username
	    $mail->Password = 'aq1sw2de3fr4';                           // SMTP password
	    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	    $mail->Port = 587;                                    // TCP port to connect to

	    //Recipients
	    $mail->setFrom("{$_POST['email']}", "{$_POST['name']}");
	    $mail->addAddress("merciadopark@gmail.com", "MerciadoPark");     // Add a recipient
        $mail->addReplyTo("{$_POST['email']}", "{$_POST['name']}");
	    //Content
	    $mail->isHTML(true);                                  // Set email format to HTML
	    $mail->Subject = "Contact Us Message";
	    $mail->Body    = "{$_POST['message']}<br/><br/>From {$_POST['name']} - {$_POST['email']} - {$_POST['age']} years old, {$_POST['country']}";
	    $mail->AltBody = "{$_POST['message']}";

	    $mail->send();
	    $xp->assign('mess_sent_success','Message has been sent successfully!');
	} catch (Exception $e) {
	    $xp->assign('mess_sent_err','Message could not be sent! Mailer Error: '.$mail->ErrorInfo);
	}
}
$xp->parse('CONTACTUS');
$acontent=$xp->text('CONTACTUS');