<?php
	$xp = new XTemplate('views/price/edit.html');
	$id = $_GET['id'];
	$do_save=1;

	$sql = "SELECT zone.zone_id,zone_name,weekend,adult_price,children_price FROM (zone INNER JOIN zone_group ON zone.zone_group_id = zone_group.zone_group_id)
		INNER JOIN price ON zone.zone_id = price.zone_id WHERE zone.zone_id = '{$id}'";
	$rs = $db->fetchOne($sql);
	//print_r($rs);
	
	$xp->assign('zone_id',$rs['zone_id']);
	$xp->assign('zone_name',$rs['zone_name']);
	$xp->assign('adult_price',$rs['adult_price']);
	$xp->assign('children_price',$rs['children_price']);
	$xp->assign('weekend',$rs['weekend']);
	
	if($_POST){
		$aprice=$_POST['adult_price'];
		$cprice=$_POST['children_price'];
		$weekend=$_POST['weekend'];
		$sql="UPDATE price SET adult_price = {$aprice}, children_price = {$cprice}, weekend = {$weekend} WHERE zone_id = '{$id}';";
		$db->execSQL($sql);
		$f->redir('?m=price&a=list');
	}
	$xp->parse('EDIT');
	$acontent = $xp->text('EDIT');
?>
