<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	function startsWith ($string, $startString)
	{
		$len = strlen($startString);
		return (substr($string, 0, $len) === $startString);
	}
	
	function endsWith($string, $endString)
	{
		$len = strlen($endString);
		if ($len == 0) {
			return true;
		}
		return (substr($string, -$len) === $endString);
	}

	function isValid($file){
		if (startsWith($file, ".")) return false;
		if (!endsWith(strtolower($file), ".png") || !endsWith(strtolower($file), ".gif")) return false;
		return true;
	}
	
	define("PROFILES", [
		"R" => "#ffdaf5",
		"L" => "#ffeeda",
		"M" => "#f3daff",
		// "J" => "#db9393",
		"C" => "#ffffda",
		"B" => "#dafffe",
		"N" => "#acabff",
		"A" => "#c2caed"
	]);
	

			
	$dir = 'faces';
	
	if (!is_dir($dir))
	{
		mkdir($dir);
	}
	
	$files = scandir($dir);
	
	shuffle($files);
	
	$facesPerID = [];
	
	// Remove invalid faces
	$validFaces = array_filter($files, "isValid");
	
	define("RANDOM_FACE", count($validFaces) == 0 ? "" : $dir."/".$validFaces[array_rand($validFaces)]);
	define("TITLE", "US MAGNIFICENT BEASTS");
	define("DESCRIPTION", "No one quite stands out like us shapeshifters");
	
	
	foreach($validFaces as $file){
		
		$parts = explode("-", $file);
		
		if (count($parts) != 2) continue;
		
		$initial = $parts[1][0]; // First char of second part
		$number = intval($parts[0]);
		
		if (!isset($facesPerID[$number]))
		{
			$facesPerID[$number] = [];
		}
		
		if (in_array($initial, array_keys(PROFILES)))
		{		
			$facesPerID[$number][$initial] = $dir."/".$file;
		}
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="style.css?version=<?php echo time();?>"> 
		<link rel="icon" type="image/png" href="<?php echo RANDOM_FACE; ?>">
		
		          
        <meta property="og:title" content="<?php echo TITLE;?>">
        <meta property="og:description" content="<?php echo DESCRIPTION; ?>">
        <meta name="Description" content="<?php echo DESCRIPTION; ?>">
        <meta property="og:image" content="<?php echo RANDOM_FACE; ?>">
      
        <meta name="twitter:card" content="summary">
        <meta name="twitter:site" content="@Rackover">
        <meta name="twitter:title" content="<?php echo TITLE; ?>">
        <meta name="twitter:description" content="<?php echo DESCRIPTION; ?>">
        <meta name="twitter:image" content="<?php echo RANDOM_FACE; ?>">
        <meta name="twitter:image:alt" content="A beast unlike any other.">
		
		<title><?php echo TITLE;?></title>
		
		<script>
		
			let zoomedContainer;
			let zoomedPictureBox;
			let linkable;
			
			function unFocus()
			{
				zoomedContainer.style.display = "none";
				console.log("unfocus");
			}
			
			function focusOnImage(url)
			{
				// zoomedPictureBox.style.backgroundImage = "url("+url+")";
				zoomedPictureBox.src = url;
				zoomedContainer.style.display = "";
				
				const matches = /faces\/([0-9]*)\-[A-Z]\.[a-z]{3}/gi.exec(url);
				
				const id = matches[1];
				
				linkable.href = "https://picrew.me/image_maker/"+id;
				console.log("focus on "+url+" background image is "+zoomedPictureBox.style.backgroundImage );
			}
			
			window.onload = ()=>
			{
				zoomedContainer = document.getElementById("zoomedPictureContainer");
				zoomedPictureBox = document.getElementById("zoomedPictureBox");
				linkable = zoomedContainer.getElementsByTagName("a")[0];
			};
			
		</script>
	</head>
	<body>

		<h1><?php echo TITLE;?></h1>
		
		<div class="imageList">
			<?php
				foreach($facesPerID as $id=>$faceRow)
				{
					?>
					
					<div class="imageRow" id="row-<?php echo $id; ?>">
					
					<?php
					foreach(PROFILES as $profileInitial => $color)
					{
						?>
						
						<div class="picrewColumnAtom" style="background-color:<?php echo $color; ?>;">
							<div class="picrewContainer">
						
						<?php
						if (isset($faceRow[$profileInitial]))
						{
							?>
							
								<img 
									src="<?php echo $faceRow[$profileInitial]; ?>" 
									onmousedown="focusOnImage('<?php echo $faceRow[$profileInitial]; ?>')"
									loading="lazy"
								>
							
							<?php
						}
						else
						{
							?>
							
								<div class="missingPicrew"></div>
							
							<?php
						}
						
						?>
							</div>
						</div>
						
						<?php
					}
					?>
					
					</div>
					
					<?php
				}
			?>
		</div>
		
		<div id="zoomedPictureContainer" onclick="unFocus()" style="display:none">
			<a href="">
				<img id="zoomedPictureBox">
				
				</img>
			</a>
		</div>
	</body>
</html>

