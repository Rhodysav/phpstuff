
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <META HTTP-EQUIV="refresh" CONTENT="10">
<title>East Ferry Cam</title>

<?php
// **************************************************************************************
// variables
// **************************************************************************************
// $basedir is the current directory, will be scanned for old image directories to delete
// define directory path ($dir), set it to today's date - ex 20090327 for March 27th 2009
$basedir = ".";
$dir = date('Ymd');
$narray=array();
$i=0;


// **************************************************************************************
// cleanup, delete old image directories
// **************************************************************************************
if (is_dir($basedir)) {
	if ($dh = opendir($basedir)) {
		while (($file = readdir($dh)) !== false) {
			if(is_dir($file) && is_numeric($file)) {
				if($file !== $dir) {
					recursive_remove_directory($file);
					echo $file;
				}
			}
		}
		closedir($dh);
	}
}

// **************************************************************************************
// iterate through today's image directory looking for JPGs, add to array
// **************************************************************************************
if (is_dir($dir)) {
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			if (preg_match("/.jpg/", $file)) {
				//add filename to array
				$narray[$i]=$file;
				$i++;
			}
		}
		closedir($dh);
	}
}

// **************************************************************************************
// sort file list in reverse order - latest pictures will be shown first
// **************************************************************************************
rsort($narray);


// **************************************************************************************
// layout html page, display images
// **************************************************************************************
?>
 <table style="width: 640px; height: 480px; float: none" class="style1" align="left">
	<tr>
		<td>
<?

	// **************************************************************************************
	// last 12 images are displayed, any older ones are deleted.
	// **************************************************************************************
	for($i=0; $i<sizeof($narray); $i++)
	{
		if ($i < 1) {
			// print <img> tag to screen
			echo "<a href=" . $dir . '/' . $narray[$i] . "><img src=". $dir . '/' . $narray[$i] . " width=640 height=480 border=0></a>";
		}
		else {
			// delete file
			// unlink($dir . '/' . $narray[$i]);
		}
	}
?>
 </td>
	</tr>
</table>

</body>

</html>




<?
// **************************************************************************************
// function to delete directories
// **************************************************************************************
	function recursive_remove_directory($directory, $empty=FALSE) {
		// if the path has a slash at the end we remove it here
		if(substr($directory,-1) == '/') {
			$directory = substr($directory,0,-1);
		}

		// if the path is not valid or is not a directory ...
		if(!file_exists($directory) || !is_dir($directory)) {
			// ... we return false and exit the function
			return FALSE;

		// ... if the path is not readable
		}elseif(!is_readable($directory)) {
			// ... we return false and exit the function
			return FALSE;

		// ... else if the path is readable
		}else {
			// we open the directory
			$handle = opendir($directory);

			// and scan through the items inside
			while (FALSE !== ($item = readdir($handle))) {
				// if the filepointer is not the current directory or the parent directory
				if($item != '.' && $item != '..') {
					// we build the new path to delete
					$path = $directory.'/'.$item;

					// if the new path is a directory
					if(is_dir($path)) {
						// we call this function with the new path
						recursive_remove_directory($path);

					// if the new path is a file
					}else {
						// we remove the file
						unlink($path);
					}
				}
			}

			// close the directory
			closedir($handle);

			// if the option to empty is not set to true
			if($empty == FALSE) {
				// try to delete the now empty directory
				if(!rmdir($directory)) {
					// return false if not possible
					return FALSE;
				}
			}

			// return success
			return TRUE;
		}
	}
?>
