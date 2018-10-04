<?php
class Func
{
	public function redir($url)
	{
		header("Location:{$url}");
	}
	
	public function getPagers($totalRecord,$limit,$url)
	{
		$pager='';
		$totalPage=ceil($totalRecord/$limit);
		for($pi=1;$pi<=$totalPage;$pi++)
		{
			$pager.="
			<a href='{$url}&p={$pi}' class='btn btn-default'>{$pi}</a>
			 ";
		}
		return $pager;
	}

	public function checkEmail($str)
	{
		$status='NO';
		//$reg='/^[a-zA-Z0-9\.\_\-]+\@[0-9a-z]+\.[a-z]{2,4}(\.[a-z]{2,4}).$/';
		$reg='/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i';
		$check=preg_match($reg, $str);
		if($check)
		{
			$status='YES';
		}
		return $status;
	}

	public function getExt($fileName){
		$arrFile = explode('.',$fileName);
		return strtolower(end($arrFile));			
	}

	public function uploadMultipleFile($file,$url,$allowType,$maxSize,$total){
		$urlTotal = '';
		for($i=0; $i<$total; $i++)
		{
			$fName = rtrim($file['name'][$i],' ');
			$fName 	= str_replace(" ","-",$file['name'][$i]);
			$fSize 	= $file["size"][$i];
			$_tmpName  = $file["tmp_name"][$i];
			$ext 	  =  $this->getExt($fName);	
			if($fName!=''){
				$urlFile = $url.date('Y-m-d_His')."_{$fName}";
				if(!in_array(strtolower($ext),$allowType)){
					return "11";
				}else if($fSize > $maxSize){
					return "12";
				}else{
					if(move_uploaded_file($_tmpName,"$urlFile")){
						@chmod($urlFile,0775);
						$urlTotal .= $urlFile.'|';  
						//echo $urlFile; 
					}else{
							return "-11";
					}
				}
			}
		}
		return $urlTotal;
	}

	public function uploadFile($file,$url,$allowType,$maxSize){
		$fName = rtrim($file['name'],' ');
		$fName 	= str_replace(" ","-",$file['name']);
		$fSize 	= $file["size"];
		$_tmpName  = $file["tmp_name"];
		$ext 	  =  $this->getExt($fName);
		if($fName!=''){
			$urlFile = $url.date('Y-m-d_His')."_{$fName}";
			if(!in_array(strtolower($ext),$allowType)){
				return "11";
			}else if($fSize > $maxSize){
				return "12";
			}else{
				if(move_uploaded_file($_tmpName,"$urlFile")){
					@chmod($urlFile,0775);
					return $urlFile;
				}else{
						return "-11";
				}
			}
		}
	}

	function randomPassword() {
	    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass); //turn the array into a string
	}
	
}