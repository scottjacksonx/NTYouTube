<?php

function remotefilesize($url) {
	$sch = parse_url($url, PHP_URL_SCHEME);
	$headers = array_change_key_case(get_headers($url, 1), CASE_LOWER);
	if (!array_key_exists("content-length", $headers)) { return false; }  // Error: no 'content-length' header.
	return $headers["content-length"];  // Return: $headers["content-length"] value.
}

$daurl = 'http://www.youtube.com/watch?v='.$_GET["v"];
$handle = fopen($daurl, "r");
$source_code = "";

if ($handle) {
	while (!feof($handle)) {
		$source_code .= fgets($handle, 4096);
	}
	fclose($handle);
	if ($_GET["hd"]) {
		$dStart = strpos($source_code, "37|") + 3;
		if (!$dStart) {
			$dStart = strpos($source_code, "22|") + 3;
		}
		if (!$dStart) {
			$dStart = strpos($source_code, "18|") + 3;
		}
	} else {
		$dStart = strpos($source_code, "18|") + 3;
	}
	$dEnd = strpos($source_code, ",", $dStart);
	$urlLength = $dEnd - $dStart;
	$dURL = substr($source_code, $dStart, $urlLength);
	$str = str_ireplace("\\", "", $dURL);
	$handle2 = fopen($str, "r");
	header("Content-Type: video/mp4");
	header("Expires: 0");
	header("Accept-Ranges: bytes");
	$filesize = remotefilesize($str);
	header("Content-Length: ".$filesize);
	
	$titleStart = strpos($source_code, "<meta name=\"title\" content=") + 28;
	$titleEnd = strpos($source_code, "\">", $titleStart);
	$titleLen = $titleEnd - $titleStart;
	$title = substr($source_code, $titleStart, $titleLen);
	header('Content-Disposition: attachment; filename="'.$title.'.mp4"');
	
	while (!feof($handle2)) {
		echo fgets($handle2, 4096);
	}
}
?>