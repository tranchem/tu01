<?php
	$xp = new XTemplate ("views/attraction.html");
		$sql = "SELECT zone.zone_name,description,avatar_img,adult_price,children_price from zone 
		inner join price on zone.zone_id = price.zone_id
		where zone.zone_group_id='ZG3' ORDER BY zone.zone_id DESC";

		$rs = $db->fetchAll($sql);
		
		$t = count($rs);
		$l = 3;
		$p = (isset($_GET['p']))?$_GET['p']:1;
		$fs = ($p-1)*$l;
		
		$sql = "SELECT zone.zone_name,description,avatar_img,adult_price,children_price from zone 
		inner join price on zone.zone_id = price.zone_id
		where zone.zone_group_id='ZG3' ORDER BY zone.zone_id DESC LIMIT {$fs},{$l}";

		$rs = $db->fetchAll($sql);

		foreach($rs as $row){
			$xp->insert_loop('ATTRACTION.LS',array('LS'=>$row));
		}

	$url = $baseUrl.'/?m=attraction';
	$pagers = $f->getPagers($t,$l,$url);
	$xp->assign('pagers',$pagers);
	$xp->parse('ATTRACTION');
	$acontent=$xp->text('ATTRACTION');
?>