<?php

require_once ( 'config.php' );

$baseSabServer = $sabnzbd['protocol'] . '://' . $sabnzbd['server'] . ':' . $sabnzbd['port'] . '/sabnzbd/';

$graph = filter_input ( INPUT_GET , 'graph' , FILTER_SANITIZE_STRING );

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
	/* !Categories */
	case 'categories' :
		
		$finalArray['graph']['title'] = 'Downloads by Category';
		$finalArray['graph']['type'] = 'bar';
		$finalArray['graph']['total'] = true ;
	
		curl_setopt ( $ch , CURLOPT_URL , $baseSabServer . 'api?apikey=' . $sabnzbd['apikey'] . '&mode=queue&output=json' );
		
		$queue = json_decode ( curl_exec ( $ch ) , true );
		
		curl_close ( $ch );
		
		// Quick check to see if the queue is empty
		if ( count ( $queue['queue']['slots'] ) == 0 ) {
			$downloads[] = array (
				'title' => 'Downloads' ,
				'datapoints' => array (
					array (
						'title' => '' ,
						'value' => 0
					)
				)
			);
		} else {
			foreach ( $queue['queue']['slots'] as $job ) {
				$cats[] = $job['cat'];
			}
			
			$finalCats = array_count_values ( $cats );
			
			foreach ( $finalCats as $category => $total ) {
				$downloads[] = array (
					'title' => $category ,
					'datapoints' => array (
						array ( 'title' => $category , 'value' => $total )
					)
				);
			}
		}
		
		$finalArray['graph']['datasequences'] = $downloads;

	break;
}

header ( 'content-type: application/json' );

echo json_encode ( $finalArray );