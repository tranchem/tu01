<?php
if(strlen($_SESSION['user_name'])==0||$_SESSION['user_type']!='user')
{
	$f->redir($baseUrl.'/?m=home');
}