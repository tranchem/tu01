<?php
	$xp = new XTemplate('views/gallery/edit.html');
	$id = $_GET['id'];
	$do_save=1;

	$sql = "SELECT gallery_id,gallery_url,description FROM gallery
			WHERE gallery_id = '{$id}'";

	$rs = $db->fetchOne($sql);
	//print_r($rs);
	
	$xp->assign('gallery_id',$rs['gallery_id']);
	$xp->assign('gallery_url',$rs['gallery_url']);
	$xp->assign('description',$rs['description']);
	$xp->assign('curr_dcrt',$rs['description']);

	if($_POST){
		$description=$_POST['description'];
		
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