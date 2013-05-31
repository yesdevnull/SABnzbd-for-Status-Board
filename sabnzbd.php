<?php

$protocol	= ( $_GET['ssl'] == 'true' ) ? 'https': 'http';
$server		= $_GET['server'];
$port		= $_GET['port'];
$apikey		= $_GET['apikey'];

$baseSabServer = $protocol . '://' . $server . ':' . $port . '/sabnzbd/';

$graph = $_GET['graph'];

$ch = curl_init();
curl_setopt ( $ch , CURLOPT_HEADER , 0 );
curl_setopt ( $ch , CURLOPT_RETURNTRANSFER , true );

echo '<pre>';

switch ( $graph ) {
	case 'categories' :
	
		curl_setopt ( $ch , CURLOPT_URL , $baseSabServer . 'api?apikey=' . $apikey . '&mode=queue&output=json' );
		
		$queue = json_decode ( curl_exec ( $ch ) , true );
		
		$totalJobs = count ( $queue['jobs'] );
		
		foreach ( $queue['queue']['slots'] as $job ) {
			$cats[] = $job['cat'];
		}
		
		$finalCats = array_count_values ( $cats );
		
		//$cats = array_count_values ( $queue['slots'] );
		
		var_dump($finalCats);
		
		//var_dump($queue);
	
	break;
}

header ( 'content-type: application/json' );

echo json_encode ( $finalArray );