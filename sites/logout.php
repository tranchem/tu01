<?php
session_destroy();
if(isset($_SERVER['HTTP_REFERER']))
{
	$f->redir($_SERVER['HTTP_REFERER']); 
}
else
{
	$f->redir($baseUrl.'/?m=home'); 
}

$acontent="bye!";