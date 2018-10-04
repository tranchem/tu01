<?php
$xp = new XTemplate ("views/home.html");
	
	//RESTAURANT
	$condition = "zone.zone_group_id = 'ZG1'";
	$sql = "SELECT avatar_img 
			FROM zone INNER JOIN zone_group ON zone.zone_group_id = zone_group.zone_group_id 
			WHERE {$condition} ORDER BY zone.zone_id DESC LIMIT 3";
	$rs = $db->fetchAll($sql);
	foreach($rs as $row){
		$xp->insert_loop('HOME.RES_IMG',array('RES_IMG'=>$row));
	}
	$sql = "SELECT zone_name 
			FROM zone INNER JOIN zone_group ON zone.zone_group_id = zone_group.zone_group_id 
			WHERE {$condition} ORDER BY zone.zone_id DESC LIMIT 3";
	$rs = $db->fetchAll($sql);
	foreach($rs as $row){
		$xp->insert_loop('HOME.RES_NAME',array('RES_NAME'=>$row));
	}
	$sql = "SELECT description 
			FROM zone INNER JOIN zone_group ON zone.zone_group_id = zone_group.zone_group_id 
			WHERE {$condition} ORDER BY zone.zone_id DESC LIMIT 3";
	$rs = $db->fetchAll($sql);
	foreach($rs as $row){
		$xp->insert_loop('HOME.RES_DCRT',array('RES_DCRT'=>$row));
	}

	//ENTERTAINMENT
	$condition = "zone.zone_group_id = 'ZG2'";
	$sql = "SELECT avatar_img 
			FROM zone INNER JOIN zone_group ON zone.zone_group_id = zone_group.zone_group_id 
			WHERE {$condition} ORDER BY zone.zone_id DESC LIMIT 3";
	$rs = $db->fetchAll($sql);
	foreach($rs as $row){
		$xp->insert_loop('HOME.ENT_IMG',array('ENT_IMG'=>$row));
	}
	$sql = "SELECT zone_name 
			FROM zone INNER JOIN zone_group ON zone.zone_group_id = zone_group.zone_group_id 
			WHERE {$condition} ORDER BY zone.zone_id DESC LIMIT 3";
	$rs = $db->fetchAll($sql);
	foreach($rs as $row){
		$xp->insert_loop('HOME.ENT_NAME',array('ENT_NAME'=>$row));
	}
	$sql = "SELECT description 
			FROM zone INNER JOIN zone_group ON zone.zone_group_id = zone_group.zone_group_id 
			WHERE {$condition} ORDER BY zone.zone_id DESC LIMIT 3";
	$rs = $db->fetchAll($sql);
	foreach($rs as $row){
		$xp->insert_loop('HOME.ENT_DCRT',array('ENT_DCRT'=>$row));
	}

	//ATTRACTION
	$condition = "zone.zone_group_id = 'ZG3'";
	$sql = "SELECT avatar_img 
			FROM zone INNER JOIN zone_group ON zone.zone_group_id = zone_group.zone_group_id 
			WHERE {$condition} ORDER BY zone.zone_id DESC LIMIT 3";
	$rs = $db->fetchAll($sql);
	foreach($rs as $row){
		$xp->insert_loop('HOME.ATT_IMG',array('ATT_IMG'=>$row));
	}
	$sql = "SELECT zone_name 
			FROM zone INNER JOIN zone_group ON zone.zone_group_id = zone_group.zone_group_id 
			WHERE {$condition} ORDER BY zone.zone_id DESC LIMIT 3";
	$rs = $db->fetchAll($sql);
	foreach($rs as $row){
		$xp->insert_loop('HOME.ATT_NAME',array('ATT_NAME'=>$row));
	}
	$sql = "SELECT description 
			FROM zone INNER JOIN zone_group ON zone.zone_group_id = zone_group.zone_group_id 
			WHERE {$condition} ORDER BY zone.zone_id DESC LIMIT 3";
	$rs = $db->fetchAll($sql);
	foreach($rs as $row){
		$xp->insert_loop('HOME.ATT_DCRT',array('ATT_DCRT'=>$row));
	}

$xp->parse('HOME');
$acontent=$xp->text('HOME');