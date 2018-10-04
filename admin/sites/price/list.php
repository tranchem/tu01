<?php
	$xp = new XTemplate('views/price/list.html');
	$kw = '';
	$condition = "1=1";
	
	$sql = "SELECT zone_group_id, zone_group_name FROM zone_group WHERE {$condition}";
	$rs = $db->fetchAll($sql);
	foreach($rs as $row){
		$xp->insert_loop('LIST.GROUPLS',array('GROUPLS'=>$row));
	}

	$sql = "SELECT zone.zone_id,zone_name,avatar_img,weekend,adult_price,children_price FROM zone 
			INNER JOIN zone_group ON zone.zone_group_id = zone_group.zone_group_id 
			INNER JOIN price ON zone.zone_id = price.zone_id WHERE {$condition}  ORDER BY zone_id DESC";

	$rs = $db->fetchAll($sql);
	
	$t = count($rs);
	$l = 10;
	$p = (isset($_GET['p']))?$_GET['p']:1;
	$fs = ($p-1)*$l;

	if(isset($_POST['search'])){
		$kw = $_POST['txtKeyword'];
		if (strlen($kw)>0){
			$kw1 = str_replace(' ','%',$kw);
			$condition .= " AND zone_name LIKE '%$kw1%'";
		}
		$gr = $_POST['select_group'];
		if($gr!='0'){
		$condition .= " AND zone_group.zone_group_id = '$gr'";
		}
	}

	$sql = "SELECT zone.zone_id,zone_name,avatar_img,weekend,adult_price,children_price FROM zone 
			INNER JOIN zone_group ON zone.zone_group_id = zone_group.zone_group_id 
			INNER JOIN price ON zone.zone_id = price.zone_id WHERE {$condition}  ORDER BY zone_id DESC LIMIT {$fs},{$l}";

	$rs = $db->fetchAll($sql);
	
	//print_r($rs);
	$i = 1;
	foreach($rs as $row){
		$row['Nmb'] = $i;
		$xp->insert_loop('LIST.LS',array('LS'=>$row));
		$i++;
	}
	
	$url = $baseUrl."/admin/?m=price&a=list";
	$pagers = $f->getPagers($t,$l,$url);
	$xp->assign('pagers',$pagers);
	$xp->assign('keyword',$kw);
	$xp->parse('LIST');
	$acontent = $xp->text('LIST');
?>