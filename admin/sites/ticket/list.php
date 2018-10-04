<?php
	$xp = new XTemplate('views/ticket/list.html');
	$kw = '';
	$condition = "1=1";
	
	$sql = "SELECT DISTINCT booking_status FROM booking WHERE {$condition}";
	$rs = $db->fetchAll($sql);
	foreach($rs as $row){
		$xp->insert_loop('LIST.STATUSLS',array('STATUSLS'=>$row));
	}

	//List booking
	$sql = "SELECT booking_id,user_name,booking_date,ticket_date,total,booking_status from booking where {$condition} ORDER BY booking_id DESC";
	$rs = $db->fetchAll($sql);
	
	$t = count($rs);
	$l = 10;
	$p = (isset($_GET['p']))?$_GET['p']:1;
	$fs = ($p-1)*$l;

	if(isset($_POST['search'])){
		$kw = $_POST['txtKeyword'];
		if (strlen($kw)>0){
			$kw1 = str_replace(' ','%',$kw);
			$condition .= " AND user_name LIKE '%$kw1%'";
		}
		$gr = $_POST['select_status'];
		if($gr!='0'){
		$condition .= " AND booking_status = '$gr'";
		//echo $condition;
		}
	}

	$sql = "SELECT booking_id,user_name,booking_date,ticket_date,total,booking_status from booking where {$condition} ORDER BY booking_id DESC LIMIT {$fs},{$l}";

	$rs = $db->fetchAll($sql);
	
	//print_r($rs);
	$i = 1;
	foreach($rs as $row){
		$row['Nmb'] = $i;
		$xp->insert_loop('LIST.LS',array('LS'=>$row));
		$i++;
	}
	
	$url = $baseUrl."/admin/?m=ticket&a=list";
	$pagers = $f->getPagers($t,$l,$url);
	$xp->assign('pagers',$pagers);
	$xp->assign('keyword',$kw);
	$xp->parse('LIST');
	$acontent = $xp->text('LIST');
?>