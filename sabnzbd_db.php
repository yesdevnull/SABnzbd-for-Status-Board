<?php

set_include_path ( '.' );

// From: http://stackoverflow.com/a/5501447
function formatSizeUnits ( $bytes , $rounding = 0 ) {	
	if ( $bytes >= 1073741824 ) {
		$bytes = number_format ( $bytes / 1073741824 , $rounding ) . ' GB';
	} elseif ( $bytes >= 1048576 ) {
		$bytes = number_format ( $bytes / 1048576 , $rounding ) . ' MB';
	} elseif ( $bytes >= 1024 ) {
		$bytes = number_format ( $bytes / 1024 , $rounding ) . ' KB';
	} elseif ( $bytes > 1 ) {
		$bytes = $bytes . ' B';
	} elseif ( $bytes == 1 ) {
		$bytes = $bytes . ' B';
	} else {
		$bytes = 0 . ' B';
	}

	return $bytes;
}

$db = new SQLite3 ( 'sabnzbd_history.db' );

// Bring in our SABnzbd+ config details
require_once ( 'config.php' );

$sabQueueURI = $sabnzbd['protocol'] . '://' . $sabnzbd['server'] . ':' . $sabnzbd['port'] . '/sabnzbd/api?apikey=' . $sabnzbd['apikey'] . '&mode=queue&output=json';

$ch = curl_init();
curl_setopt ( $ch , CURLOPT_HEADER , 0 );
curl_setopt ( $ch , CURLOPT_RETURNTRANSFER , true );
curl_setopt ( $ch , CURLOPT_URL , $sabQueueURI );

$queue = json_decode ( curl_exec ( $ch ) , true );

if ( curl_errno ( $ch ) == 7 ) {
	// SABnzbd+ server is down
	exit;
}

curl_close ( $ch );

$currentTime = time();
$currentSpeed = formatSizeUnits ( $queue['queue']['kbpersec'] * 1024 );
$sizeleft = $queue['queue']['sizeleft'];
$totalDownloads = count ( $queue['queue']['slots'] );

var_dump($currentSpeed,$sizeLeft,$totalDownloads);

$stmt = $db->prepare( 'INSERT INTO stats ( time, download_speed, size_left, total_downloads ) VALUES ( :time, :download_speed, :size_left, :total_downloads )' );

$stmt->bindParam( ':time' , $currentTime );
$stmt->bindParam( ':download_speed' , $currentSpeed );
$stmt->bindParam( ':size_left' , $sizeLeft );
$stmt->bindParam( ':total_downloads' , $totalDownloads );

$stmt->execute();