<!DOCTYPE html>

	<html lang="en">

		<head>

			<meta charset="UTF-8">
			
			<style type="text/css">
				
				body, * {
					font-family: "Roadgeek 2005 Series C";
				}
				
				body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,textarea,p,blockquote,th,td { 
					margin: 0;
					padding: 0;
				}
					
				fieldset,img { 
					border: 0;
				}
				
					
				/* Settin' up the page */
				
				html, body, #main {
					overflow: hidden; /* */
					text-align: center;
					vertical-align: middle;
				}
				
				.current-speed {
					font-size: 16px;
				}
				
				.status {
					padding: 20px;
					background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(73,73,73,1)), color-stop(100%,rgba(255,255,255,0)));
/*
					background: #000;
					border-top: 1px solid rgb( 100 , 112 , 118 );
*/
				}
				
				.status-downloading {
					color: rgb( 0 , 186 , 0 );
				}
				
				.status-paused {
					color: rgb( 255 , 198 , 0 );
				}
			
			</style>
			
			<script type="text/javascript">
	
			function refresh() {
			    var req = new XMLHttpRequest();
		   	 	console.log("Refreshing Count...");
				
		   	 	req.onreadystatechange = function() {
			   	 	if ( req.readyState == 4 && req.status == 200 ) {
	    				document.getElementById('howmany').innerText = req.responseText;
					}
				}
			    
			    req.open("GET", 'sabnzbd_helper.php', true);
			    req.send(null);
			}
	
			function init() {
				refresh()
				var int = self.setInterval(function(){refresh()},300000);
			}
	
			</script>

		</head>

		<body onload="init()">
		
			<div id="main">
			
				<ul>
				
					<li id="current-speed">0 KB/s</li>
					
					<li id="status status-<?php echo strtolower( $status );?>">Idle</li>
					
					<li id="sizeleft">0 GB</li>
				
				</ul>
			
			</div>

		</body>

	</html>