<!DOCTYPE html>
<html lang="en-gb" dir="ltr">
<head>
	<meta charset="utf-8">
</head>
<body>
<?php

// Here you can adjust the directory, one line below. Currently set to the blog directory
$directory = "/images/video/blog";
$playbutton = "/images/icons/mediaplayer/playbutton.png";
$foldericon = "/images/icons/foldericon.jpg";
$category = $_GET["categ"];
$filepath = realpath('.') . $directory . $category;
$photogallerypath = $filepath . "/photo";
$scanned_directory = array_diff(scandir($filepath), array('..', '.'));
arsort($scanned_directory);
$fileCountRow = 0;
$videofilecount = -1;
$totaldirectories  = count( glob(substr($directory, 1) . $category . "/*", GLOB_ONLYDIR) );
$totalvideofiles  = count( glob(substr($directory, 1) . $category . "/*.mp4") );
$totalphotofiles  = count( glob(substr($directory, 1) . $category . "/photo/*.jpg") );

//Video File Detection First
if ($totalvideofiles >= 1) {
asort($scanned_directory);
  echo ("<div class = 'bartkavideo'><video controls id='k1videoplayer'><source id='k1videos' type='video/mp4' src='videofile.mp4'/></video></div>");}
if ($totalvideofiles > 1) {echo '<div class = "bartkaplaylist">';}
foreach( $scanned_directory as $files ) {
	$extension = substr($files, -4);
  	$filewithoutextension = substr($files, 0, -4);
    $filenoleadingnumbers = substr($filewithoutextension, 0, 2);
  	if (is_numeric($filenoleadingnumbers)) {
      $filenoleadingnumbers = substr($filewithoutextension, 2);
    } else {$filenoleadingnumbers = $filewithoutextension;}
    $videoFileNameCaps = preg_replace('/(?<!\ )[A-Z]/', ' $0', $filenoleadingnumbers);
	if ($extension == ".mp4") {
      
      //If only one video file is detected then there will only be an option to make a photo gallery which is highly recommended instead of a video gallery
      if ($totalvideofiles == 1) {
        $jsfiles[0] = $filewithoutextension;
        	if(is_dir($photogallerypath)) {
				$photogalleryfile = array_diff(scandir($photogallerypath), array('..', '.'));
      			echo("<div class = 'bartkaphotogallery'>");
      			foreach($photogalleryfile as $photogalleryfiles) {
        			$photogalleryfilesandpath = $directory . $category . "/photo/" . $photogalleryfiles;
      				echo '<a target="_blank" href="' . $photogalleryfilesandpath . '">
                    <img src="' . $photogalleryfilesandpath . '" alt="' . $photogalleryfiles . '" height="150"></a>';
				}
                echo("</div>");
            }
      //If multiple video files are detected then a video scroll gallery will be automatically created with an option for a photo gallery as well
      } elseif ($totalvideofiles > 1) {
        $videofilecount++;
        $jsfiles[$videofilecount] = $filewithoutextension;
        $videothumbnail = $filepath . "/" . $filewithoutextension . ".jpg";
        if(is_file($videothumbnail)) {
        	$picturethumb = 'poster="' . $directory . $category . '/' . $filewithoutextension . '.jpg"';
          	$videothumb = "";
        } else {
            $picturethumb = "";
          	$videothumb = "#t=5";
        }
        echo("<div class = 'bartkaplayerone'>");
        echo("<video $picturethumb id='lengthvideoplayer$videofilecount' onloadedmetadata='myFunction$videofilecount()'>
      	<source id='k1lengthvideo$videofilecount' type='video/mp4' src='$directory$category/$filewithoutextension.mp4$videothumb'/>
      	</video>");
		echo("<a href='#' id='$filewithoutextension'>
        <img src='$playbutton' alt='$filewithoutextension'>$videoFileNameCaps");
		echo("</a>");
        echo("<p id='k1video$videofilecount'></p>");
		echo("</div>");
      }
      
        	//list of excluded files and folders
	} elseif (strpos($files, '.'  )       !== FALSE) {} 
	elseif   (strpos($files, 'Blacklist') !== FALSE) {$totaldirectories = $totaldirectories - 1;} 
  	elseif   (strpos($files, 'Featured')  !== FALSE) {$totaldirectories = $totaldirectories - 1;} 
    elseif   (strpos($files, 'Rodzinne')  !== FALSE) {$totaldirectories = $totaldirectories - 1;} 
	elseif   (strpos($files, 'misc'  )    !== FALSE) {} 
  	elseif   (strpos($files, 'photo'  )   !== FALSE) {} 
	else {
      	
        //Folder Detection Second
        if ($fileCountRow == 0) { echo("<div class = 'bartkarow'>"); }
      	$foldericon = $filepath . "/" . $files . "/thumbnail.jpg";
      	if(is_file($foldericon)) {$foldericon = $directory . $category . "/" . $files . "/" . "thumbnail.jpg";} 
      	else {$foldericon = "/images/icons/foldericon.jpg";}
		echo("<div class = 'bartkacolumn'>");
		echo("<a href='?categ=$category/$files'><img src='$foldericon' alt='$files'>");
        echo("</a>");
		echo("</div>");
      	$fileCountRow++;
        $dirNoUnderscore = str_replace('_', ' ', $files);
      	$dirNoUnderscore = str_replace("And", '&', $dirNoUnderscore);
      	$dirNoUnderscore = preg_replace('/(?<!\ )[A-Z&]/', ' $0', $dirNoUnderscore);
    	$videoLink[$fileCountRow] = $files;
  		$videoLinkNoDigits[$fileCountRow] = substr($dirNoUnderscore, 0, 6);
      	if (is_numeric($videoLinkNoDigits[$fileCountRow])) {
          $videoLinkNoDigits[$fileCountRow] = substr($dirNoUnderscore, 6);
        } else {
          $videoLinkNoDigits[$fileCountRow] = $dirNoUnderscore;
        }
		if ($fileCountRow == 3 || $fileCountRow == $totaldirectories){
            $totaldirectories = $totaldirectories - 3;
			echo("</div>
			<div class = 'bartkalinkrow'>");
			for ($tempNumber=1; $tempNumber<=$fileCountRow; $tempNumber++) {
        		echo("<div class='bartkalinkcolumn'>
                <a href='?categ=$category/$videoLink[$tempNumber]'>$videoLinkNoDigits[$tempNumber]</a>
            	</div>");
        	}
    	$fileCountRow = 0; 
		echo("</div>");
        }
	}	
}

// If multiple video files exist along with a photo gallery, these functions will build both galleries
if ($totalvideofiles > 1) {
  if(is_dir($photogallerypath)) {
	$photogalleryfile = array_diff(scandir($photogallerypath), array('..', '.'));
  	echo("<div class = 'bartkavideophotogallery'>");
	foreach($photogalleryfile as $photogalleryfiles) {
		$photogalleryfilesandpath = $directory . $category . "/photo/" . $photogalleryfiles;
		echo '<a target="_blank" href="' . $photogalleryfilesandpath . '">
    	<img src="' . $photogalleryfilesandpath . '" alt="' . $photogalleryfiles . '" height="150"></a>';
	}
  	echo("</div>");
  }
  $blog = $filepath . "/blog.txt";
  if(is_file($blog)) {
	echo ("<div class='blog'><h3>Total Videos: " . $totalvideofiles . " </h3>");
    echo ("<h3>Total Photos: " . $totalphotofiles . " </h3>");
  	require $blog;
    echo("</div>");
  }
  echo("</div>");
}
?>

<script>
	var i = 0;
	var videosAutoPlay = new Array();
<?php
for($j = 0; $j<$totalvideofiles; $j++ ) {echo("	videosAutoPlay[$j]='$directory$category/$jsfiles[$j].mp4';\n");}
	?>
	var videoCount = videosAutoPlay.length;
	document.getElementById("k1videoplayer").setAttribute("src",videosAutoPlay[0]);
  	document.getElementById('k1videoplayer').addEventListener('ended',myHandler,false);

	function videoPlay(videoNum) {
		document.getElementById("k1videoplayer").setAttribute("src",videosAutoPlay[videoNum]);
		document.getElementById("k1videoplayer").load();
		document.getElementById("k1videoplayer").play();
	}
	videoPlay(0);
  
	function myHandler() {
		i++;
		if(i == videoCount){ i = 0; videoPlay(i);} 
		else { videoPlay(i); }
	} 

<?php
for($j = 0; $j<$totalvideofiles; $j++ ) {echo("	var videobutton$j = document.getElementById('$jsfiles[$j]');\n");}
	for($j = 0; $j<$totalvideofiles; $j++ ) {
      echo("	videobutton$j.addEventListener('click', function(event) { i = $j; videoPlay(i); }, false);\n");}
for($j = 0; $j<$totalvideofiles; $j++ ) {$jsfiles[$j] = substr($jsfiles[$j], 2); 
      echo("	function myFunction$j() {\n	  var videolength$j = document.getElementById('lengthvideoplayer$j').duration;
      var minutes$j = Math.floor(videolength$j/60);
      var seconds$j = Math.floor(((videolength$j/60)-minutes$j)*60);
      if (seconds$j < 10) {seconds$j = '0'+seconds$j;}
    document.getElementById('k1video$j').innerHTML = 'Length: '+minutes$j+':'+seconds$j };\n");}
	?>
</script>
</body>
</html>