<?php
	$xp = new XTemplate('views/attraction/edit.html');
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
		$att_name=$_POST['txtAtt_name'];
		$att_dcrt=$_POST['txtAtt_dcrt'];
		
		//<Upload image----//
		$allowType 	= array('png','jpg','gif');
		$maxSize	= 5242880;
		$fileImg	= $_FILES['att_img']; 
		$urlImg		= "./images/attraction/";
		$att_img	= $f->uploadFile($fileImg,$urlImg,$allowType,$maxSize);


		if(strlen($fileImg['name'])>0){
			if($att_img=='11'){
				$do_save=-1;
				$xp->assign('err_mes_proImg','Image is not formatted correctly (PNG, JPG, GIF)!');
			}
			if($att_img=='12'){
				$do_save=-1;
				$xp->assign('err_mes_proImg','Image exceeds 5MB!');
			}
			if($att_img=='-11'){
				$do_save=-1;
				$xp->assign('err_mes_proImg','Cannot upload image!');
			}
		}else{
			$do_save=-1;
			$xp->assign('err_mes_proImg','You must select image to upload!');
		}
		//----->//

		if(strlen($att_name)==0){
			$do_save=0;
			$xp->assign('err_mess','<br/>You must insert attraction name!');
		}
		if($do_save==1){
			$sql="UPDATE zone SET zone_name = '{$att_name}' WHERE zone_id = '{$id}';
					UPDATE zone SET description = '{$att_dcrt}' WHERE zone_id = '{$id}';
					UPDATE zone SET avatar_img = '{$att_img}' WHERE zone_id = '{$id}'";

			$db->execSQL($sql);
			$f->redir('?m=attraction&a=list');
		}
	}
	$xp->parse('EDIT');
	$acontent = $xp->text('EDIT');
?>