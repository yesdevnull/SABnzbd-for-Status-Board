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

$protocol	= ( $_GET['ssl'] == 'true' ) ? 'https': 'http';
$server		= $_GET['server'];
$port		= $_GET['port'];
$apikey		= $_GET['apikey'];

$baseSabServer = $protocol . '://' . $server . ':' . $port . '/sabnzbd/';

//$queue = $baseSabServer. 'api?apikey=' . $apikey . '&mode=queue&output=json';

$ch = curl_init();
curl_setopt ( $ch , CURLOPT_HEADER , 0 );
curl_setopt ( $ch , CURLOPT_RETURNTRANSFER , true );
curl_setopt ( $ch , CURLOPT_URL , $baseSabServer . 'api?apikey=' . $apikey . '&mode=queue&output=json' );

$output = json_decode ( curl_exec ( $ch ) , true );
//echo '<pre>';
//var_dump($output);

$currentSpeed = $output['queue']['kbpersec'] * 1024;

//echo $currentSpeed * 1024;
$status = $output['queue']['status'];
$sizeleft = $output['queue']['sizeleft'];

?><!DOCTYPE html>

	<html lang="en">

		<head>

			<meta charset="UTF-8">

		</head>

		<body>
		
			<div id="main">
			
				<ul>
				
					<li class="current-speed"><?php echo formatSizeUnits ( $currentSpeed ); ?>/s</li>
					
					<li class="status"><?php echo $status; ?></li>
					
					<li class="sizeleft"><?php echo $sizeleft; ?></li>
				
				</ul>
			
			</div>

		</body>

	</html>