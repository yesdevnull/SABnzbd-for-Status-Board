# SABnzbd+ for Status Board
This is a collection of graphs and info panels for your SABNzbd+ server that can be displayed in [Status Board](http://panic.com/statusboard/).

## Usage
There are two major compotents to this Status Board script.  The first section is a graph of current downloads sorted by category.  The second component is basic details of SABnzbd+ at the current point in time (i.e. download speed, GBs remaining to download).

The first and most important part of this is to make sure you first make a file called config.php that has your SABnzbd+ server details in it.  For example:

```php
<?php

$sabnzbd = array (
	'protocol' => 'http' ,
	'server' => '192.168.1.x' ,
	'port' => '8080' ,
	'apikey' => 'your api key here'
);
```

This file should be stored in the same location as the other files for this code.  This file is __required__ to access your SABnzbd+ server's API.


