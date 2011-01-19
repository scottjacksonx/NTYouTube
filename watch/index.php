<?php

header("Content-Type: video/mp4");
header('Content-Disposition: attachment; filename="video.mp4"');
header("Expires: 0");

// Website url to open
$daurl = 'http://www.youtube.com/watch?v='.$_GET["v"];

// Get that website's content
$handle = fopen($daurl, "r");

$source_code = "";
if ($handle) {
	while (!feof($handle)) {
		$source_code .= fgets($handle, 4096);
	}
	fclose($handle);
	$dStart = strpos($source_code, "18|") + 3;
	$dEnd = strpos($source_code, ",", $dStart);
	$urlLength = $dEnd - $dStart;
	$dURL = substr($source_code, $dStart, $urlLength);
	$str = str_ireplace("\\", "", $dURL);
	$handle2 = fopen($str, "r");
	while (!feof($handle2)) {
		echo fgets($handle2, 4096);
	}
}
?>