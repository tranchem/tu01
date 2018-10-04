<?php
	$xp = new XTemplate('views/entertainment/add.html');
	$do_save=1;
	if($_POST){
		$ent_name=$_POST['txtEnt_name'];
		$ent_dcrt=$_POST['txtEnt_dcrt'];

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
		//---->//


		if(strlen($ent_name)==0){
			$do_save=0;
			$xp->assign('err_res_name',"You must insert Entertainment's Name!");
		}
		//All true, do insert
		if($do_save==1){	
			echo $sql="INSERT INTO zone(zone_id,zone_group_id,zone_name,avatar_img,description) 
					VALUES ('$zone_id','ZG2','{$ent_name}','{$ent_img}','{$ent_dcrt}');
					INSERT INTO gallery (gallery_id,zone_id,gallery_url,description) VALUES ('$gallery_id','$zone_id','$ent_img',{$ent_dcrt});
					INSERT INTO price (price_id,zone_id) VALUES ('$price_id','$zone_id');";		
			if($db->execSQL($sql)){
				$xp->assign('success_add',"Entertainment created successfully!");
				$f->redir('?m=entertainment&a=list');
			}
		}
	//sleep(3);	
	}
	$xp->parse('ADD');
	$acontent = $xp->text('ADD'); 
?>