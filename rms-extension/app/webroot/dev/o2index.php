<?php
function bindIP($clntIP, $serverIP) {
	$ip = "localhost";
	$port = 2107;

	$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	if ($sock == false) {
		echo "socket_create error\n";
	}

	socket_connect($sock, $ip, $port);

	$msg = socket_read($sock, 128);

	$msg = "APP_CLIENT";
	socket_write($sock, $msg, strlen($msg));
	$msg = socket_read($sock, 128);

	$msg = 'B'.$clntIP.'#'.$serverIP;
	echo $msg;

	$lenStr = strlen($msg).'#';

	socket_write($sock, $lenStr, strlen($lenStr));
	socket_write($sock, $msg, strlen($msg));

	$msg = socket_read($sock, 128);	
	
	socket_shutdown($sock);
	socket_close($sock);
}


if (isset($_POST["target"])) {
	$client = $_SERVER['REMOTE_ADDR'];
    $server = htmlspecialchars($_POST["target"]);
	bindIP($client, $server);
}
?>


<html>
<head>
<link href="../css/cloud_list.css" rel="stylesheet" type="text/css" media="all" />
<script src="../js/jquery.min.js"></script>
</head>

<body>
<br>

<div id="contents">

</div>

<br>
<form method="POST">
<input type="text" name="target">
</form>

<script type="text/javascript">
	var dataobj = null;
	
	function bindTo(clientIP) {
		console.log("binding to "+clientIP);
		$.post("index.php", { "target": clientIP });
	}
	
	function refreshContent(data) {
		if (data == "ERROR") {
			$("#contents").html('<div class="error">Server unreachable</div>');
			return;
		}
		dataobj = JSON.parse(data);
		
		var tableStr = "<table> <tr> <td>Name</td> <td>Robot IP</td> <td>Bound IPs</td> <td>Time</td> <td>Battery</td> <td>Connection quality</td> <td></td> </tr>";
		
		if ("clients" in dataobj) {
			for (robot in dataobj.clients) {
				if ("data" in dataobj.clients[robot]) {
					var boundIPs = "";
					var boundToMe = false;
					var myIP = dataobj.clntIP;
					
					var robotIP = dataobj.clients[robot].ip;
					for (b in dataobj.bindings) {
						if (dataobj.bindings[b] == robotIP) {
							if (boundIPs != "") { boundIPs += ", "; }
							boundIPs += b;
							if (b == myIP) { boundToMe = true; }
						}
					}
					if (boundToMe) { 	tableStr += '<tr id="boundToMe"> '; }
					else { 				tableStr += "<tr> "; } 
					tableStr += "<td>" + robot + "</td> ";
					tableStr += "<td>" + robotIP + "</td> ";
					tableStr += "<td>" + boundIPs + "</td> ";
					tableStr += "<td>" + dataobj.clients[robot].data.time + "</td> ";
					tableStr += "<td>" + dataobj.clients[robot].data.battery + "</td> ";
					tableStr += "<td>" + dataobj.clients[robot].data.conn_quality + "</td> ";
					if (boundToMe) {	tableStr += "<td>Bound</td>";}
					else { 				tableStr += '<td> <a href="#" onclick="bindTo('+"'" + robotIP + "'" + ')">Bind</a> </td> '; }
					tableStr += "</tr> ";
				}
			}
			
		}
		else {
			tableStr += "No robots connected";
		}
		tableStr += "</table>";
		
		$("#contents").html(tableStr);
	}
	
	function getJSON() {
		$.ajax({url:"getData.php", success: refreshContent });
	}
	getJSON();
	setInterval(getJSON, 1000);
</script>

</body>

</html>