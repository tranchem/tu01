<?php
	$xp = new XTemplate('views/restaurant/edit.html');
	$id = $_GET['id'];
	$do_save=1;

	$sql = "SELECT zone.zone_id,zone_name,avatar_img,description FROM zone 
			INNER JOIN zone_group ON zone.zone_group_id = zone_group.zone_group_id
			WHERE zone.zone_id = '{$id}'";
	$rs = $db->fetchOne($sql);
	//print_r($rs);
	
	$xp->assign('zone_id',$rs['zone_id']);
	$xp->assign('zone_name',$rs['zone_name']);
	$xp->assign('curr_name',$rs['zone_name']);
	$xp->assign('avatar_img',$rs['avatar_img']);
	$xp->assign('description',$rs['description']);
	$xp->assign('curr_dcrt',$rs['description']);	

	if($_POST){
		$res_name=$_POST['txtRes_name'];
		$res_dcrt=$_POST['txtRes_dcrt'];
		
		//<Upload image----//
		$allowType 	= array('png','jpg','gif');
		$maxSize	= 5242880;
		$fileImg	= $_FILES['res_img']; 
		$urlImg		= "./images/restaurant/";
		$res_img	= $f->uploadFile($fileImg,$urlImg,$allowType,$maxSize);


		if(strlen($fileImg['name'])>0){
			if($res_img=='11'){
				$do_save=-1;
				$xp->assign('err_mes_proImg','Image is not formatted correctly (PNG, JPG, GIF)!');
			}
			if($res_img=='12'){
				$do_save=-1;
				$xp->assign('err_mes_proImg','Image exceeds 5MB!');
			}
			if($res_img=='-11'){
				$do_save=-1;
				$xp->assign('err_mes_proImg','Cannot upload image!');
			}
		}else{
			$do_save=-1;
			$xp->assign('err_mes_proImg','You must select image to upload!');
		}
		//----->//

		if(strlen($res_name)==0){
			$do_save=0;
			$xp->assign('err_mess','<br/>You must insert restaurant name!');
		}
		if($do_save==1){
			$sql="UPDATE zone SET zone_name = '{$res_name}' WHERE zone_id = '{$id}';
					UPDATE zone SET description = '{$res_dcrt}' WHERE zone_id = '{$id}';
					UPDATE zone SET avatar_img = '{$res_img}' WHERE zone_id = '{$id}'";

			$db->execSQL($sql);
			$f->redir('?m=restaurant&a=list');
		}
	}
	$xp->parse('EDIT');
	$acontent = $xp->text('EDIT');
?>