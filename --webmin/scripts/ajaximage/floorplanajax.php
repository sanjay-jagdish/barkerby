<?php session_start();

$path = "../../floorplanuploads/";

	$valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");
	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
		{
			$name = $_FILES['floorplanimg']['name'];
			$size = $_FILES['floorplanimg']['size'];
			
			if(strlen($name))
				{
					list($txt, $ext) = explode(".", $name);
					if(in_array(strtolower($ext),$valid_formats))
					{
					if($size<(3000*3000))
						{
							$actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
							$tmp = $_FILES['floorplanimg']['tmp_name'];
							if(move_uploaded_file($tmp, $path.$actual_image_name))
								{
								
									echo "<img src='floorplanuploads/".$actual_image_name."'  class='preview' title='".$actual_image_name."'>";
								}
							else
								echo "failed";
						}
						else
						echo "Image file size max 1 MB";					
						}
						else
						echo "Invalid file format..";	
				}
				
			else
				echo "Please select image..!";
				
			exit;
		}
?>