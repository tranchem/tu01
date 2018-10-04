<?php
if(strlen($_SESSION['user_name'])==0)
{
	$f->redir($baseUrl.'/admin/login.php');
}
else if($_SESSION['user_type']!='admin' && $_SESSION['user_type']!='owner')
{
	$f->redir($baseUrl.'/?m=home');
}