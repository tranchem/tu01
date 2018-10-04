<?php
	$xp = new XTemplate('views/entertainment/edit.html');
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
		$ent_name=$_POST['txtEnt_name'];
		$ent_dcrt=$_POST['txtEnt_dcrt'];
		
		//<Upload image----//
		$allowType 	= array('png','jpg','gif');
		$maxSize	= 5242880;
		$fileImg	= $_FILES['ent_img']; 
		$urlImg		= "./images/entertainment/";
		$ent_img	= $f->uploadFile($fileImg,$urlImg,$allowType,$maxSize);


		if(strlen($fileImg['name'])>0){
			if($ent_img=='11'){
				$do_save=-1;
				$xp->assign('err_mes_proImg','Image is not formatted correctly (PNG, JPG, GIF)!');
			}
			if($ent_img=='12'){
				$do_save=-1;
				$xp->assign('err_mes_proImg','Image exceeds 5MB!');
			}
			if($ent_img=='-11'){
				$do_save=-1;
				$xp->assign('err_mes_proImg','Cannot upload image!');
			}
		}else{
			$do_save=-1;
			$xp->assign('err_mes_proImg','You must select image to upload!');
		}
		//----->//

		if(strlen($ent_name)==0){
			$do_save=0;
			$xp->assign('err_mess','<br/>You must insert entertainment name!');
		}
		if($do_save==1){
			$sql="UPDATE zone SET zone_name = '{$ent_name}' WHERE zone_id = '{$id}';
					UPDATE zone SET description = '{$ent_dcrt}' WHERE zone_id = '{$id}';
					UPDATE zone SET avatar_img = '{$ent_img}' WHERE zone_id = '{$id}'";

			$db->execSQL($sql);
			$f->redir('?m=entertainment&a=list');
		}
	}
	$xp->parse('EDIT');
	$acontent = $xp->text('EDIT');
?>