# SABnzbd+ for Status Board
This is a collection of graphs and info panels for your SABNzbd+ server that can be displayed in [Status Board](http://panic.com/statusboard/).

## Usage
There are two major components to this Status Board script.  The first section is a graph of current downloads sorted by category.  The second component is basic details of SABnzbd+ at the current point in time (i.e. download speed, GBs remaining to download).

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
### Current Downloads For Each Category (Bar)
When entering the URI for the Status Board panel, enter ```http://example.com/path/to/sabnzbd_graph.php?graph=categories```.

### Download Speed and Speedlimit (Line) - Advanced
This graph is the current download speed, along with the speedlimit (if set).  To get this working properly, you should have a decent knowledge of the command line as you __MUST HAVE__ correct permissions to make sure SQLite can work properly, along with setting up a cron task for data collection.

1. Once you've cloned/downloaded these files, add your config file then run
```chmod -R 775 .```
to make sure the folder is writeable.  If you get SQLite errors after the ```sabnzbd_history.db``` database has been generated, you may have to run ```chmod 775 sabnzbd_history.db```.
2. After you've done that, type
```crontab -e```
to edit your crontab.  Enter in ```*/5 * * * * ( /path/to/php /path/to/sabnzbd_db.php )```
This ensures that the database scraping script is run every 5 minutes.
**Note:** If you wish to change how often the script is run, feel free to change any of the values in the crontab.
3. Finally, enter ```http://example.com/path/to/sabnzbd_graph.php?graph=download-speed``` in Status Board to get the graph.

## Page
In its current form, the server info page is only designed for a panel 4 blocks wide and 4 blocks high.  I plan on making it more responsive, however, at the present time it should be good enough (it's not like it's not readable in its current form).

The code for getting the page panel is ```http://example.com/path/to/file/sabnzbd_page.html```

## Requirements
- PHP 5.x or greater
### PHP Modules/Extensions
- PDO with SQLite
- SQLite3
- curl

## TODO
- Add support for username/password auth for SABnzbd+
