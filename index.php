<?php

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
		if (!endsWith(strtolower($file), ".png")) return false;
		return true;
	}
	
	define("PROFILES", [
		"R" => "#FA8EC3",
		"L" => "#FAA771",
		"M" => "#D590F5",
		"J" => "#540004",
		"C" => "#FFEDAA",
		"B" => "#99B9C2"
	]);
	

			
	$dir = 'faces';
	$files = scandir($dir);
	
	$facesPerID = [];
	
	
	define("RANDOM_FACE", $dir."/".array_rand(array_filter($files, "isValid")));
	define("TITLE", "US MAGNIFICENT BEASTS");
	define("DESCRIPTION", "No one quite stands out like us shapeshifters");
	
	foreach($files as $file){
		
		if (!isValid($file)) continue;
		
		$parts = explode("-", $file);
		
		if (count($parts) != 2) continue;
		
		$initial = $parts[1][0]; // First char of second part
		$number = intval($parts[0]);
		
		if (!isset($facesPerID[$number]))
		{
			$facesPerID[$number] = [];
		}
		
		$facesPerID[$number][$initial] = $dir."/".$file;
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
				zoomedPictureBox.style.backgroundImage = "url("+url+")";
				zoomedContainer.style.display = "";
				
				const matches = /faces\/([0-9]*)\-[A-Z]\.png/gi.exec(url);
				
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
				foreach($facesPerID as $faceRow)
				{
					?>
					
					<div class="imageRow">
					
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
				<div id="zoomedPictureBox">
				
				</div>
			</a>
		</div>
	</body>
</html>

