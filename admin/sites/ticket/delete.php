<?php
	$id = $_GET['id'];
	$sql = "DELETE FROM booking_detail WHERE booking_id='{$id}';
			DELETE FROM booking WHERE booking_id='{$id}'";
	$db->execSQL($sql);
	$f->redir('?m=ticket&a=list');
?>