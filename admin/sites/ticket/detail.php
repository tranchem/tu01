<?php
	$xp = new XTemplate('views/ticket/detail.html');
	$id = $_GET['id'];

	//List booking detail
	$sql = "SELECT number_of_adults,number_of_child,zone_name,sub_total from booking_detail
	INNER JOIN price on price.price_id=booking_detail.price_id
	INNER JOIN zone on zone.zone_id=price.zone_id
	INNER JOIN booking on booking.booking_id=booking_detail.booking_id
	where booking_detail.booking_id='{$id}'";

	$rs = $db->fetchAll($sql);
	
	$t = count($rs);
	$l = 10;
	$p = (isset($_GET['p']))?$_GET['p']:1;
	$fs = ($p-1)*$l;

	$sql = "SELECT number_of_adults,number_of_child,zone_name,sub_total from booking_detail
	INNER JOIN price on price.price_id=booking_detail.price_id
	INNER JOIN zone on zone.zone_id=price.zone_id
	INNER JOIN booking on booking.booking_id=booking_detail.booking_id
	where booking_detail.booking_id='{$id}' LIMIT {$fs},{$l}";

	$rs = $db->fetchAll($sql);
	//$r = mysql_fetch_array($rs);
	$number_of_child=0;
	$number_of_adults=0;

	$i = 1;
	foreach($rs as $row){
		$row['Nmb'] = $i;
		$number_of_adults=$row['number_of_adults'];
		$number_of_child=$row['number_of_child'];
		$xp->insert_loop('DETAIL.LS',array('LS'=>$row));
		$i++;
	}
	
	$url = $baseUrl."/admin/?m=ticket&a=detail";
	$pagers = $f->getPagers($t,$l,$url);
	$xp->assign('pagers',$pagers);
	$xp->assign('booking_id',$id);
	$xp->assign('number_of_adults',$number_of_adults);
	$xp->assign('number_of_children',$number_of_child);
	$xp->parse('DETAIL');
	$acontent = $xp->text('DETAIL');
?>