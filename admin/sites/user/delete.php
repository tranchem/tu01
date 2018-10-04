<?php
	$id = $_GET['id'];
	$query = "SELECT DISTINCT booking.booking_id FROM booking INNER JOIN booking_detail ON booking.booking_id=booking_detail.booking_id WHERE user_name='$id'";
	$rs = $db->execSQL($query);
	$booking_id_arr = array();
	foreach($rs as $row){
		array_push($booking_id_arr, $row['booking_id']);
	}
	$count = count($booking_id_arr);

	for($i=0;$i<$count;$i++){
		$booking_id = $booking_id_arr[$i];
		$sql1 = "DELETE FROM booking_detail WHERE booking_id='{$booking_id}';
		DELETE FROM booking WHERE booking_id='{$booking_id}'";
		$db->execSQL($sql1);
	}
	$sql = "DELETE FROM users WHERE user_name='{$id}';";
	$db->execSQL($sql);
	$f->redir('?m=user&a=list');
?>