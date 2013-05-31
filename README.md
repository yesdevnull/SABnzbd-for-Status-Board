# SABnzbd+ for Status Board
This is a collection of graphs and info panels for your SABNzbd+ server that can be displayed in [Status Board](http://panic.com/statusboard/).

## Usage
There are two major compotents to this Status Board script.  The first section is a graph of current downloads sorted by category.  The second component is basic details of SABnzbd+ at the current point in time (i.e. download speed, GBs remaining to download).

The first and most important part of this is to make sure you first make a file called config.php that has your SABnzbd+ server details in it.  For example:

```php
<?php

$sabnzbd = array (
	'protocol' => 'http' , // Change this to https if you use it
	'server' => '192.168.1.x' , // Change this to the IP of your SABnzbd+ server
	'port' => '8080' , // Change this to the port of your SABnzbd+ server
	'apikey' => 'your api key here' // Put your SABNzbd+ API key here (not NZB key)
);
```

This file should be stored in the same location as the other files for this code.  This file is __required__ to access your SABnzbd+ server's API.

## Graphs
Currently, the only graph available is a bar graph of current downloads.  When entering the URI for the Status Board panel, enter ```http://path/to/file/sabnzbd_graph?graph=categories```.

## Page
In its current form, the server info page is only designed for a panel 4 blocks wide and 4 blocks high.  I plan on making it more responsive, however, at the present time it should be good enough (it's not like it's not readable in its current form).

## TODO
- Add support for username/password auth for SABnzbd+
