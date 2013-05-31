<?php

$protocol	= ( $_GET['ssl'] == 'true' ) ? 'https': 'http';
$server		= $_GET['server'];
$port		= $_GET['port'];
$apikey		= $_GET['apikey'];

$baseSabServer = $protocol . '://' . $server . ':' . $port . '/';
