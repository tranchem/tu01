<?php
	$id = $_GET['id'];
	$sql = "DELETE FROM price WHERE zone_id='{$id}';
			DELETE FROM gallery WHERE zone_id='{$id}';
			DELETE FROM zone WHERE zone_id='{$id}'";
	$db->execSQL($sql);
	$f->redir('?m=attraction&a=list');