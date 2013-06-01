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
	
	case 'download-speed' :
		
		$finalArray['graph']['title'] = 'Downloads';
		$finalArray['graph']['type'] = 'line';
	
		$db = new PDO ( 'sqlite:sabnzbd_history.db' );
		
		$sql = 'SELECT
					time ,
					download_speed ,
					speedlimit
				FROM
					stats
				ASC
				LIMIT
					20';
		
		$stmt = $db->prepare ( $sql );
		
		$stmt->execute();
		
		foreach ( $stmt->fetchAll() as $row ) {
			$time = date ( 'H:i' , $row['time'] );
			
			$downloadSpeed[] = array ( 'title' => $time , 'value' => $row['download_speed'] );
			$speedlimit[] = array ( 'title' => $time , 'value' => $row['speedlimit'] );
		}
		
		$finalArray['graph']['datasequences'] = array (
			array (
				'title' => 'Speed Limit' ,
				'datapoints' => $speedlimit ,
			) ,
			array (
				'title' => 'Download Speed' ,
				'datapoints' => $downloadSpeed ,
			) ,
		);
	
	break;
}

header ( 'content-type: application/json' );

echo json_encode ( $finalArray );