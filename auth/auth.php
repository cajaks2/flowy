<?php
include '/usr/local/nginx/html/php/functions.php';
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

if ( !empty($_POST) && $_POST['addr']!="178.62.77.84") { //need to change this to allow restream
	switch ( $_POST['call'] ) {
		case "publish":
			if ( isset($_POST['name']) && $_POST['name'] != NULL ) {
			$ip =$conn->real_escape_string( $_POST['addr']);
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
			$sql = "UPDATE Users SET live=true WHERE '$hash'=hash ";
			$result = $conn->query($sql);
			$sql = "UPDATE Users SET address='$ip' WHERE '$hash'=hash ";
			$result = $conn->query($sql);
			$sql = "UPDATE Users SET viewers=0 WHERE '$hash'=hash ";
			generateThumbs();
			$result = $conn->query($sql);
				} else {
					header("HTTP/1.1 403 Forbidden"); // Drop the session - incorrect passphrase
				}
			} else { 
				header("HTTP/1.1 403 Forbidden"); // Drop the session - no passphrase
			}
		$conn->close();
		break;
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
			$sql = "UPDATE Users SET viewers=0 WHERE '$hash'=hash ";
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
	if ( isset($_POST['name']) && $_POST['name'] != NULL ) {
			$add = $conn->real_escape_string($_POST['addr']);				
			$hash =$conn->real_escape_string($_POST['name']);
			$client = $conn->real_escape_string($_POST['clientid']);
			
			$time = time();
			$sql = "select count(*) as total from Users WHERE '$hash'=hash AND live=true";
			$result = $conn->query($sql);
			while($row = $result->fetch_assoc ()) {
			if(!empty($row['total'])){
			$sql = "INSERT INTO viewers (stream_name, address, client_id, time)
			VALUES ('$hash','$add','$client','$time')";
			$result = $conn->query($sql);
			break;
			}else{
			header("HTTP/1.1 403 Forbidden"); // Drop the session 
			}

			}
}
			
		$conn->close();

		break;
		case "play_done":
		if ( isset($_POST['name']) && $_POST['name'] != NULL ) {
							
			
			$client = $conn->real_escape_string($_POST['clientid']);
			$hash =$conn->real_escape_string($_POST['name']);
			$sql = "UPDATE Users SET viewers = viewers - 1 WHERE '$hash'=hash";
			$result = $conn->query($sql);
			$time = time();	
			$sql = "UPDATE viewers SET time_done = '$time' WHERE '$client'=client_id AND  '$hash'=stream_name";
			$result = $conn->query($sql);
				
			}		
$conn->close();

		break;

	}
}
?>