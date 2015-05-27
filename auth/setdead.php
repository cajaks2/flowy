<?php
// Example stream authentication
$servername = "localhost";
$username = "root";
$password = "mysqlpass";
$dbname = "flowy";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
if ( !empty($_POST) ) {
	switch ( $_POST['call'] ) {
		case "publish_done":
			$publisher_ip = $_POST['addr'];
			if ( isset($_POST['name']) && $_POST['name'] != NULL ) {
			$p = $conn->real_escape_string($_POST['p']);				
			$hash =$conn->real_escape_string($_POST['name']);
			$sql = "SELECT user_password FROM Users WHERE '$hash'=hash" ;
			$result = $conn->query($sql);
			$pass=false;
			while($row = $result->fetch_row()) {
			if($p==md5($row[0])){
			$pass=true;
			}
			}
			 if( $pass==true ) {
			
			header("HTTP/1.1 202 Accepted"); // 2xx responses will keep session going
			$sql = "UPDATE Users SET live=false WHERE '$hash'=hash ";
			$result = $conn->query($sql);
				} else {
					header("HTTP/1.1 403 Forbidden"); // Drop the session - incorrect passphrase
				}
			} else { 
				header("HTTP/1.1 403 Forbidden"); // Drop the session - no passphrase
			}
		$conn->close();
		break;
		case "play":
			// The same parameters - name, addr, etc. also work for playing streams over RTMP
			// You could use the on_play parameter to authorize plays against this same file
			// and perhaps limit plays to an IP address in a database, etc.
			// to enforce a paywall or to track visits
		break;
	}
}
?>