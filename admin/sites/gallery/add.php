<?php
	$xp = new XTemplate('views/gallery/add.html');
	$do_save = 1;
	$condition = "1=1";

	$sql = "SELECT zone_id,zone_name FROM zone WHERE {$condition}";
	$rs = $db->fetchAll($sql);
	foreach($rs as $row){
		$xp->insert_loop('ADD.ZONELS',array('ZONELS'=>$row));
	}

	if($_POST){

		$zone_id = $_POST['select_zone'];
		$description = $_POST['description'];	

		//Select url save img
		$sql= "SELECT zone_group_name from zone_group
				INNER JOIN zone on zone.zone_group_id=zone_group.zone_group_id where zone_id='{$zone_id}'";
		$rs = $db->fetchAll($sql);
		$url = '';
		foreach($rs as $row){
			$url = $row['zone_group_name'];
		}

		//<Upload image----//
		$allowType = array('png','jpg','gif');
		$maxSize = 5242880;
		$total = count($_FILES['gallery_img']['name']);
		$fileImg = $_FILES['gallery_img'];
		$urlImg = "./images/{$url}/";
		$gallery_img = $f->uploadMultipleFile($fileImg,$urlImg,$allowType,$maxSize,$total);

		if(count($fileImg['name'])>0){
			if($gallery_img=='11'){
				$do_save=-1;
				$xp->assign('err_mes_proImg','Image is not formatted correctly (PNG, JPG, GIF)!');
			}
			if($gallery_img=='12'){
				$do_save=-1;
				$xp->assign('err_mes_proImg','Image exceeds 5MB!');
			}
			if($gallery_img=='-11'){
				$do_save=-1;
				$xp->assign('err_mes_proImg','Cannot upload image!');
			}
			if(empty(trim($fileImg['name'][0])))
			{
				$do_save=-1;
				$xp->assign('err_mes_proImg','You must select image to upload!');
			}
		}
		//---->//

		//All true, do insert
		if($do_save==1){
			$img_arr = explode('|',$gallery_img);

			for($i=0;$i<$total;$i++){

				//Auto create PK
				$sql1 = "SELECT gallery_id FROM gallery ORDER BY gallery_id DESC LIMIT 1";
				$result = $db->fetchOne($sql1);
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

				$gallery_url = $img_arr[$i];	
				echo $sql="INSERT INTO gallery(gallery_id,zone_id,gallery_url,description) 
						VALUES ('$gallery_id','{$zone_id}','{$gallery_url}','{$description}');";		
				$db->execSQL($sql);		
			}
			$xp->assign('success_add',"Gallery created successfully!");
			$f->redir('?m=gallery&a=list');
		}
	}

	$xp->parse('ADD');
	$acontent = $xp->text('ADD'); 
?>