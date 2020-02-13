<?php
/*
    Copyright 2020 FWBer.com

    This file is part of FWBer.

    FWBer is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    FWBer is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero Public License for more details.

    You should have received a copy of the GNU Affero Public License
    along with FWBer.  If not, see <https://www.gnu.org/licenses/>.
*/
    session_start();

    include("_init.php");
    include("_debug.php");
    include("_profileVars.php");
    include("_secrets.php");
    include("_globals.php");

    //authorize me
    //first make sure we are a legit user.
    if(validateSessionOrCookiesReturnLoggedIn()==false){header('Location: '.getSiteURL());return;}//full auth for actions

    goHomeIfCookieNotSet();

    //connect to database
    $db = mysqli_connect($dburl,$dbuser,$dbpass);
    if(!$db)exit(mysqli_connect_error());

    $email = mysqli_escape_string($db,$_SESSION["email"]);

    //get my userid
    $dbquerystring = sprintf("SELECT id, publicPics, privatePics FROM ".$dbname.".users WHERE email='%s'",$email);
    $dbquery = mysqli_query($db,$dbquerystring);
    $dbresults = mysqli_fetch_array($dbquery);

    $userid=$dbresults['id'];

    $publicPics=explode(",",trim(trim($dbresults['publicPics']),","));
    $privatePics=explode(",",trim(trim($dbresults['privatePics']),","));

    mysqli_free_result($dbquery);


	//$filename = $_FILES['filedata']['name'];//strip_tags($_REQUEST['filename']);
	$type = strip_tags($_REQUEST['type']);
	$filesize_image = $_FILES['filedata']['size'];
	
	if($type=="public")if(count($publicPics)>20)exit("Too Many Pics.");
	if($type=="private")if(count($privatePics)>20)exit("Too Many Pics.");

	//$errorList="";
	
	if($filesize_image > 0)
	{
		$upload_image = uploadImage('filedata');
		
		if(is_array($upload_image))
		{
			foreach($upload_image as $key => $value)
			{
				if($value == "-ERROR-")
				{
					unset($upload_image[$key]);
				}
			}
			$document = array_values($upload_image);
			for ($x=0; $x<sizeof($document); $x++)
			{
				$errorList[] = $document[$x];
			}
			$imgUploaded = false;
		}
		else
		{
			$imgUploaded = true;
		}
	}
	else
	{
		$imgUploaded = false;
		$errorList[] = "File Size Empty";
	}

	if($imgUploaded)
	{
		//add image filename in type database.
		
		if($type=="public")$publicPics[] = $upload_image;
		if($type=="private")$privatePics[] = $upload_image;
		
		$dbquerystring = 
		sprintf("UPDATE ".$dbname.".users SET publicPics = '%s',privatePics = '%s' WHERE id='%s'",
		trim(trim(implode(",",$publicPics)),","),
		trim(trim(implode(",",$privatePics)),","),
		$userid
		);
		if(!mysqli_query($db,$dbquerystring))exit("didn't work");
		
		//done
		mysqli_close($db);

?>	
		<img src="/images/success.gif" border="0" alt="success"/> 
		Success!<br>
		<img src="<?php echo "/_getImage?id=".$userid."&img=".$upload_image; ?>" border="0" height="100"  alt="user image"/>
<?php
	}
	else
	{
?>	
		<img src="/images/error.gif" border="0" alt="error"/>
		
<?php	

		if(!empty($errorList) && count($errorList)>1)echo "Errors: ";
		if(!empty($errorList) && count($errorList)==1)echo "Error: ";

        if(!empty($errorList)) {
            foreach ($errorList as $value) {
                echo $value . ', ';
            }
        }
	}

	function uploadImage($formName)
	{
		$maxW=1920;
		$maxH=null;

		global $errorList;

		$maxlimit = 50000000; //50mb
		$allowed_ext = "jpg,jpeg,png";
		$match = "";
		$filesize = $_FILES[$formName]['size'];


		if($filesize > 0)
		{	
			$filename = strtolower($_FILES[$formName]['name']);
			$filename = preg_replace('/\s/', '_', $filename);
			
		   	if($filesize < 1)
			{ 
				$errorList[] = "File size is empty.";
			}
			
			if($filesize > $maxlimit)
			{ 
				$errorList[] = "File size is too big.";
			}

			if(!empty($errorList) && count($errorList)<1)
			{

				$file_ext = preg_split("/\./",$filename);
				$allowed_ext = preg_split("/\,/",$allowed_ext);

				foreach($allowed_ext as $ext)
				{
					if($ext==end($file_ext))
					{
						$match = "1"; // File is allowed
						
						//$NUM = time();
						//$front_name = substr($file_ext[0], 0, 15);
						//$imageMD5FileName = $front_name."_".$NUM.".jpg";//.end($file_ext);
						
						$filetype = end($file_ext);

						$imageMD5FileName = md5_file($_FILES[$formName]['tmp_name']);

						$top_offset = 0;

						if(!file_exists("./fwberImageStore/".$imageMD5FileName))
						{
							list($width_orig, $height_orig) = getimagesize($_FILES[$formName]['tmp_name']);
							if($maxH == null)
							{
								if($width_orig < $maxW)
								{
									$fwidth = $width_orig;
								}
								else
								{
									$fwidth = $maxW;
								}
								$ratio_orig = $width_orig/$height_orig;
								$fheight = $fwidth/$ratio_orig;
								
								$blank_height = $fheight;
								$top_offset = 0;
									
							}
							else
							{
								if($width_orig <= $maxW && $height_orig <= $maxH)
								{
									$fheight = $height_orig;
									$fwidth = $width_orig;
								}
								else
								{
									if($width_orig > $maxW)
									{
										$ratio = ($width_orig / $maxW);
										$fwidth = $maxW;
										$fheight = ($height_orig / $ratio);
										if($fheight > $maxH)
										{
											$ratio = ($fheight / $maxH);
											$fheight = $maxH;
											$fwidth = ($fwidth / $ratio);
										}
									}
									if($height_orig > $maxH)
									{
										$ratio = ($height_orig / $maxH);
										$fheight = $maxH;
										$fwidth = ($width_orig / $ratio);
										if($fwidth > $maxW)
										{
											$ratio = ($fwidth / $maxW);
											$fwidth = $maxW;
											$fheight = ($fheight / $ratio);
										}
									}
								}
								if($fheight == 0 || $fwidth == 0 || $height_orig == 0 || $width_orig == 0)
								{
									exit("");
								}
								if($fheight < 45)
								{
									$blank_height = 45;
									$top_offset = round(($blank_height - $fheight)/2);
								}
								else
								{
									$blank_height = $fheight;
								}
							}
							
							
							$image_p = imagecreatetruecolor($fwidth, $blank_height);
							$white = imagecolorallocate($image_p, 255,255,255);
							imagefill($image_p, 0, 0, $white);
							switch($filetype)
							{

								case "jpg":
									$image = imagecreatefromjpeg($_FILES[$formName]['tmp_name']);
								break;
								case "jpeg":
									$image = imagecreatefromjpeg($_FILES[$formName]['tmp_name']);
								break;
								case "png":
									$image = imagecreatefrompng($_FILES[$formName]['tmp_name']);
								break;
							}
							
							if(!$image)
							{
								$errorList[]= "Could not convert image. Original file incorrect type?";
							}
							else
							{
							
								imagecopyresampled($image_p, $image, 0, $top_offset, 0, 0, $fwidth, $fheight, $width_orig, $height_orig);
								
								if(!imagejpeg($image_p, "./fwberImageStore/".$imageMD5FileName, 90))$errorList[]= "Could not save image.";
								

								$bigger = $fwidth;
								if($fwidth<$fheight)$bigger=$fheight;
								
								$pixels = 0.07 * $bigger;
								
								imagefilter($image_p, IMG_FILTER_PIXELATE, $pixels , true);
								
								if(!imagejpeg($image_p, "./fwberImageStore/".$imageMD5FileName."_b", 90))$errorList[]= "Could not save image.";
								

								imagedestroy($image);
								imagedestroy($image_p);
							}
						}
						else
						{
							$errorList[]= "Image hash has been uploaded previously by another user.";
						}	
					}
				}		
			}
		}
		else
		{
			$errorList[]= "No File Selected";
		}
		
		if(!$match)
		{
		   	$errorList[]= "File type isn't allowed: $filename";
		}
		
		if(empty($errorList) || count($errorList) == 0)
		{
			return $imageMD5FileName;
		}
		else
		{
			$eMessage = array();
			
			for ($x=0; $x<count($errorList); $x++)
			{
				$eMessage[] = $errorList[$x];
			}
		   	return $eMessage;
		}
	}
	
	

	
	