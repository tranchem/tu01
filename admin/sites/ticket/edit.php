<?php
	$xp = new XTemplate('views/ticket/edit.html');
	$id = $_GET['id'];

	$sql = "SELECT booking_id,ticket_date,booking_status FROM booking 
			WHERE booking_id = '{$id}'";
	$rs = $db->fetchOne($sql);
	//print_r($rs);
	
	$xp->assign('booking_id',$rs['booking_id']);
	$xp->assign('ticket_date',$rs['ticket_date']);
	$xp->assign('booking_status',$rs['booking_status']);
	

	if($_POST){
		$ticket_date=$_POST['date_edit'];
		$booking_status=$_POST['status_edit'];

		$sql="UPDATE booking SET ticket_date = '{$ticket_date}' , booking_status = '{$booking_status}' WHERE booking_id = '{$id}';";

		$db->execSQL($sql);
		$f->redir('?m=ticket&a=list');
	}
	$xp->parse('EDIT');
	$acontent = $xp->text('EDIT');
?>