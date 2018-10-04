<?php
	$xp = new XTemplate('views/restaurant/add.html');
	$do_save=1;

	if($_POST){
		$res_name=$_POST['txtRes_name'];
		$res_dcrt=$_POST['txtRes_dcrt'];

		//Auto create PK
		$sql1 = "SELECT zone_id FROM zone ORDER BY zone_id DESC LIMIT 1";
		$result = $db->fetchOne($sql1);
		$id_zone=substr($result["zone_id"],1,3);
		if($id_zone<9){
			$zone_id = "Z00".($id_zone + 1);
		}else{
			if($id_zone<99)
			{
				$zone_id = "Z0".($id_zone + 1);
			}else
			{
				$zone_id = "Z".($id_zone + 1);
			}
		}

		$sql2 = "SELECT gallery_id FROM gallery ORDER BY gallery_id DESC LIMIT 1";
		$result = $db->fetchOne($sql2);
		$id_img=substr($result["gallery_id"],1,3);
		if($id_img<9){
			$gallery_id = "G00".($id_img + 1);
		}else{
			if($id_img<99)
			{
				$gallery_id = "G0".($id_img + 1);
			}else
			{
				$gallery_id = "G".($id_img + 1);
			}
		}

		$sql3 = "SELECT price_id FROM price ORDER BY price_id DESC LIMIT 1";
		$result = $db->fetchOne($sql3);
		$id_price=substr($result["price_id"],1,3);
		if($id_price<9){
			$price_id = "P00".($id_price + 1);
		}else{
			if($id_price<99)
			{
				$price_id = "P0".($id_price + 1);
			}else
			{
				$price_id = "P".($id_price + 1);
			}
		}

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
			$xp->assign('err_res_name',"You must insert Restaurant's Name!");
		}
		//All true, insert data
		if($do_save==1){		
			$sql="INSERT INTO zone(zone_id,zone_group_id,zone_name,avatar_img,description) VALUES ('$zone_id','ZG1','{$res_name}','{$res_img}','{$res_dcrt}');
					INSERT INTO gallery (gallery_id,zone_id,gallery_url,description) VALUES ('$gallery_id','$zone_id','$res_img',{$ent_dcrt});
					INSERT INTO price (price_id,zone_id) VALUES ('$price_id','$zone_id');";

			if($db->execSQL($sql)){
				$xp->assign('success_add',"Restaurant created successfully!");
				$f->redir('?m=restaurant&a=list');
			}
		}
	}
	$xp->parse('ADD');
	$acontent = $xp->text('ADD'); 
?>