<?php
	$xp = new XTemplate('views/attraction/list.html');
	$kw = '';
	$condition = "zone.zone_group_id = 'ZG3'";
	
	if($_POST){
		$kw = $_POST['txtKeyword'];
		if (strlen($kw)>0){
			$kw1 = str_replace(' ','%',$kw);
			$condition .= " AND zone_name LIKE '%$kw1%'";
		}
	}
	
	$sql = "SELECT zone.zone_id,zone_name,avatar_img,description FROM zone 
			INNER JOIN zone_group ON zone.zone_group_id = zone_group.zone_group_id 
			WHERE {$condition}  ORDER BY zone_id DESC";

	$rs = $db->fetchAll($sql);
	
	$t = count($rs);
	$l = 4;
	$p = (isset($_GET['p']))?$_GET['p']:1;
	$fs = ($p-1)*$l;
	$sql = "SELECT zone.zone_id,zone_name,avatar_img,description FROM zone 
			INNER JOIN zone_group ON zone.zone_group_id = zone_group.zone_group_id 
			WHERE {$condition} ORDER BY zone_id DESC LIMIT {$fs},{$l}";

	$rs = $db->fetchAll($sql);
	
	//print_r($rs);
	$i = 1;
	foreach($rs as $row){
		$row['Nmb'] = $i;
		$xp->insert_loop('LIST.LS',array('LS'=>$row));
		$i++;
	}
	
	$url = $baseUrl.'/admin/?m=attraction&a=list';
	$pagers = $f->getPagers($t,$l,$url);
	$xp->assign('pagers',$pagers);
	$xp->assign('keyword',$kw);
	$xp->parse('LIST');
	$acontent = $xp->text('LIST');
?>