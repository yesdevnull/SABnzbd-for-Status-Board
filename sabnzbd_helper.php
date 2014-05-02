<?php

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

require_once ( 'config.php' );

$baseSabServer = $sabnzbd['protocol'] . '://' . $sabnzbd['server'] . ':' . $sabnzbd['port'] . '/sabnzbd/';

$ch = curl_init();
curl_setopt ( $ch , CURLOPT_HEADER , 0 );
curl_setopt ( $ch , CURLOPT_RETURNTRANSFER , true );
curl_setopt ( $ch , CURLOPT_URL , $baseSabServer . 'api?apikey=' . $sabnzbd['apikey'] . '&mode=queue&output=json' );

$output = json_decode ( curl_exec ( $ch ) , true );

if ( curl_errno ( $ch ) == 7 ) {
	// SABnzbd+ server is down
	
	$finalArray = array (
		'current-speed' => '0 KB/s' ,
		'status' => 'Offline' ,
		'sizeleft' => '0 B' ,
		'version' => 'Offline'
	);
} else {
	$currentSpeed = formatSizeUnits ( $output['queue']['kbpersec'] * 1024 , 1 ) . '/s';
	$status = $output['queue']['status'];
	//$sizeleft = $output['queue']['sizeleft'];
	//$totalsize = $output['queue']['size'];
	$remaining = $output['queue']['timeleft'];
	$version = $output['queue']['version'];
	
	$finalArray = array (
		'current-speed' => $currentSpeed ,
		'status' => $status ,
		//'sizeleft' => $sizeleft ,
		//'totalsize' => $totalsize ,
		'remaining' => $remaining,
		'version' => $version ,
	);
}

curl_close ( $ch );

header ( 'content-type: application/json' );

echo json_encode ( $finalArray );