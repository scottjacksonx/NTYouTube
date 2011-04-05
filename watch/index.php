<?php

function remotefilesize($url) {
	$sch = parse_url($url, PHP_URL_SCHEME);
	$headers = array_change_key_case(get_headers($url, 1), CASE_LOWER);
	if (!array_key_exists("content-length", $headers)) { return false; }  // Error: no 'content-length' header.
	return $headers["content-length"];  // Return: $headers["content-length"] value.
}

$userAgent = $_SERVER['HTTP_USER_AGENT'];
$nt = "NoteTote";
$s = substr($userAgent, 0, 8);

$daurl = 'http://www.youtube.com/watch?v='.$_GET["v"];
$handle = fopen($daurl, "r");
$source_code = "";

if ($handle && !strcmp($s, $nt)) {
	while (!feof($handle)) {
		$source_code .= fgets($handle, 4096);
	}
	fclose($handle);

	$dStart = strpos($source_code, "\"fmt_stream_map\": ");
	if ($_GET["hd"]) {
		$dStart2 = strpos($source_code, "22|http", $dStart) + 3;
		if (!$dStart2) {
			$dStart = strpos($source_code, "18|http", $dStart) + 3;
		} else {
			$dStart = $dStart2;
		}
	} else {
		$dStart = strpos($source_code, "18|http", $dStart) + 3;
	}
	$dEnd = strpos($source_code, "||", $dStart);
	$urlLength = $dEnd - $dStart;
	
	$dURL = substr($source_code, $dStart, $urlLength);
	$dURL = str_ireplace("\\", "", $dURL);
	$dURL = str_ireplace("u0026", "&", $dURL);
	$dURL = str_ireplace("%2C", ",", $dURL);
	$handle2 = fopen($dURL, "r");
	header("Content-Type: video/mp4");
	header("Expires: 0");
	header("Accept-Ranges: bytes");
	$filesize = remotefilesize($dURL);
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
