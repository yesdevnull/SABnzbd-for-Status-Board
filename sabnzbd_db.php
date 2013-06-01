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

// Create our history database using the native SQLite 3 driver
new SQLite3 ( 'sabnzbd_history.db' );

// Now to use PDO, I'd rather use PDO than the SQLite3 driver for the rest of the script
$db = new PDO ( 'sqlite:sabnzbd_history.db' );

$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

// Build up our table if it doesn't exist
$createHistoryTable = 'CREATE TABLE IF NOT EXISTS stats (
						time INTEGER NOT NULL , 
						download_speed REAL NOT NULL ,
						speedlimit REAL NOT NULL ,
						size_left TEXT NOT NULL ,
						total_downloads INTEGER NOT NULL
					)';

$db->exec ( $createHistoryTable );

// Bring in our SABnzbd+ config details
require_once ( 'config.php' );

$sabQueueURI = $sabnzbd['protocol'] . '://' . $sabnzbd['server'] . ':' . $sabnzbd['port'] . '/sabnzbd/api?apikey=' . $sabnzbd['apikey'] . '&mode=queue&output=json';

$ch = curl_init();
curl_setopt ( $ch , CURLOPT_HEADER , 0 );
curl_setopt ( $ch , CURLOPT_RETURNTRANSFER , true );
curl_setopt ( $ch , CURLOPT_URL , $sabQueueURI );

// Execute the curl command and convert it to an associative array using json_decode
$queue = json_decode ( curl_exec ( $ch ) , true );

if ( curl_errno ( $ch ) == 7 ) {
	// SABnzbd+ server is down
	die ( 'Error: SABnzbd+ Server Inaccessible' . "\n" );
}

curl_close ( $ch );

// Build up our data vars from the SABnzbd+ Queue API
$currentTime = time();
$currentSpeed = (string) formatSizeUnits ( $queue['queue']['kbpersec'] * 1024 );
$sizeLeft = (string) $queue['queue']['sizeleft'];
$speedlimit = (string) $queue['queue']['speedlimit'];
$totalDownloads = (int) count ( $queue['queue']['slots'] );

// Prepare and Bind!
$stmt = $db->prepare( 'INSERT INTO
						stats
							(
								time ,
								download_speed ,
								speedlimit ,
								size_left ,
								total_downloads
							)
					   VALUES
					   		(
					   			:time ,
					   			:download_speed ,
					   			:speedlimit ,
					   			:size_left ,
					   			:total_downloads
					   		)' );

$stmt->bindParam( ':time' , $currentTime );
$stmt->bindParam( ':download_speed' , $currentSpeed );
$stmt->bindParam( ':speedlimit' , $speedlimit );
$stmt->bindParam( ':size_left' , $sizeLeft );
$stmt->bindParam( ':total_downloads' , $totalDownloads );

try {
	// Execute and be verbose with what we've inserted (for logging purposes)
	$stmt->execute();
	
	echo 'Data Insert Successful!' . "\n";
	echo 'Current Time: ' . date ( 'H:i e' , $currentTime ) . "\n";
	echo 'Current Speed: ' . $currentSpeed . "\n";
	echo 'Size Left: ' . $sizeLeft . "\n";
	echo 'Speed Limit: ' . $speedlimit . "\n";
	echo 'Total Downloads: ' . $totalDownloads . "\n";
} catch ( PDOException $e ) {
	// SQLite error - ruh roh
	echo 'Error: ' . $e->getMessage(). ' (' . $e->getCode() . ')';
}