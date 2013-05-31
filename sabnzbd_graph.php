<?php

require_once ( 'config.php' );

$baseSabServer = $sabnzbd['protocol'] . '://' . $sabnzbd['server'] . ':' . $sabnzbd['port'] . '/sabnzbd/';

$graph = $_GET['graph'];

$ch = curl_init();
curl_setopt ( $ch , CURLOPT_HEADER , 0 );
curl_setopt ( $ch , CURLOPT_RETURNTRANSFER , true );

$finalArray = array (
	'graph' => array (
		'title' => '' ,
		'type' => '' ,
		'total' => '' ,
		'refreshEveryNSeconds' => '60' ,
		'datasequences' => '' ,
	)
);

switch ( $graph ) {
	case 'categories' :
	
		curl_setopt ( $ch , CURLOPT_URL , $baseSabServer . 'api?apikey=' . $sabnzbd['apikey'] . '&mode=queue&output=json' );
		
		$queue = json_decode ( curl_exec ( $ch ) , true );
		
		curl_close ( $ch );
		
		foreach ( $queue['queue']['slots'] as $job ) {
			$cats[] = $job['cat'];
		}
		
		$finalCats = array_count_values ( $cats );
		
		foreach ( $finalCats as $category => $total ) {
			$downloads[] = array (
				'title' => $category ,
				'value' => $total
			);
		}
		
		$finalArray['graph']['title'] = 'Downloads by Category';
		$finalArray['graph']['type'] = 'bar';
		$finalArray['graph']['total'] = true ;
		
		$finalArray['graph']['datasequences'] = array (
			array (
				'title' => 'Categories' ,
				'datapoints' => $downloads
			)
		);	
	break;
}

header ( 'content-type: application/json' );

echo json_encode ( $finalArray );