<?php

function remotefilesize($url) {
	$sch = parse_url($url, PHP_URL_SCHEME);
	if (($sch != "http") && ($sch != "https") && ($sch != "ftp") && ($sch != "ftps")) { return false; }  // Error: unrecognized URI scheme.
 
	if (($sch == "http") || ($sch == "https")) {
		$headers = array_change_key_case(get_headers($url, 1), CASE_LOWER);
		if (!array_key_exists("content-length", $headers)) { return false; }  // Error: no 'content-length' header.
 
		return $headers["content-length"];  // Return: $headers["content-length"] value.
	}
 
	if (($sch == "ftp") || ($sch == "ftps")) {
		$server = parse_url($url, PHP_URL_HOST);
		$port = parse_url($url, PHP_URL_PORT);
		$path = parse_url($url, PHP_URL_PATH);
		$user = parse_url($url, PHP_URL_USER);
		$pass = parse_url($url, PHP_URL_PASS);
 
		if ((!$server) || (!$path)) { return false; }  // Error: invalid FTP URI.
		if (!$port) { $port = 21; }
		if (!$user) { $user = "anonymous"; }
		if (!$pass) { $pass = "phpos@"; }
 
		switch ($sch) {
			case "ftp":
				$ftpid = ftp_connect($server, $port);
				break;
			case "ftps":
				$ftpid = ftp_ssl_connect($server, $port);
				break;
		}
 
		if (!$ftpid) { return false; }  // Error: could not connect to the FTP server.
 
		$login = ftp_login($ftpid, $user, $pass);
		if (!$login) { return false; }  // Error: could not login to the FTP server.
 
		$ftpsize = ftp_size($ftpid, $path);
		if ($ftpsize == -1) { return false; }  // Error: could not size the FTP file.
 
		ftp_close($ftpid);
 
		return $ftpsize;  // Return: $ftpsize value.
	}
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
	header('Content-Disposition: attachment; filename="video.mp4"');
	header("Expires: 0");
	header("Accept-Ranges: bytes");
	$filesize = remotefilesize($str);
	header("Content-Length: ".$filesize);
	while (!feof($handle2)) {
		echo fgets($handle2, 4096);
	}
}
?>