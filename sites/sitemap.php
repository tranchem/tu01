<?php
$xp=new XTemplate ("views/sitemap.html");


$xp->assign('baseUrl',$baseUrl);
$xp->parse("SITEMAP");
$acontent=$xp->text("SITEMAP");