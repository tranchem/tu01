<?php
$xp = new XTemplate ("views/aboutus.html");

$xp->parse('ABOUTUS');
$acontent=$xp->text('ABOUTUS');