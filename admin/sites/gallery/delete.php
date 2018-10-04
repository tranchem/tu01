<?php
	$id = $_GET['id'];
	$sql = "DELETE FROM gallery WHERE gallery_id='{$id}';";
	$db->execSQL($sql);
	$f->redir('?m=gallery&a=list');
?>