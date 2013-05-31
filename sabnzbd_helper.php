<?php

// From: http://stackoverflow.com/a/5501447
function formatSizeUnits ( $bytes ) {
	if ( $bytes >= 1073741824 ) {
		$bytes = number_format ( $bytes / 1073741824 , 2 ) . ' GB';
	} elseif ( $bytes >= 1048576 ) {
		$bytes = number_format ( $bytes / 1048576 , 2 ) . ' MB';
	} elseif ( $bytes >= 1024 ) {
		$bytes = number_format ( $bytes / 1024 , 2 ) . ' KB';
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
//echo '<pre>';
//var_dump($output);

$currentSpeed = formatSizeUnits ( $output['queue']['kbpersec'] * 1024 ) . '/s';
$status = $output['queue']['status'];
$sizeleft = $output['queue']['sizeleft'];

$finalArray = array (
	'current-speed' => $currentSpeed ,
	'status' => $status ,
	'sizeleft' => $sizeleft
);

header ( 'content-type: application/json' );

echo json_encode ( $finalArray );