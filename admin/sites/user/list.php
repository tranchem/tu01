<?php
	$xp = new XTemplate('views/user/list.html');
	$kw = '';
	$condition = " 1=1 ";
	
	//Search
	if(isset($_POST['txtKeyword'])){
		$kw = $_POST['txtKeyword'];
		if (strlen($kw)>0){
			$kw1 = str_replace(' ','%',$kw);
			$condition .= " AND user_name LIKE '%$kw1%' ";
		}
	}
	
	//Show list user
	$sql = "SELECT user_name,user_type,email,full_name,birthday,gender,address FROM USERS WHERE {$condition}";

	$rs = $db->fetchAll($sql);
	
	$t = count($rs);
	$l = 10;
	$p = (isset($_GET['p']))?$_GET['p']:1;
	$fs = ($p-1)*$l;
	$sql = "SELECT * FROM USERS WHERE {$condition} LIMIT {$fs},{$l}";

	$rs = $db->fetchAll($sql);
	
	//print_r($rs);
	$i = 1;
	foreach($rs as $row){
		$gender = $row['gender'];
		$row['Nmb'] = $i;
		$row['gender'] = ($gender == 1)?'Male':'Female';
		$xp->insert_loop('LIST.LS',array('LS'=>$row));
		$i++;
	}
	
	$url = $baseUrl.'/admin/?m=user&a=list';
	$pagers = $f->getPagers($t,$l,$url);
	$xp->assign('pagers',$pagers);
	$xp->assign('keyword',$kw);
	$xp->parse('LIST');
	$acontent = $xp->text('LIST');
?>