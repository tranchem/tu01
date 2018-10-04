<?php
$xp = new XTemplate ("views/error/404.html");

$xp->parse('E404');
$acontent=$xp->text('E404');