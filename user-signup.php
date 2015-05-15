<?php
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
function NewUser() {
	 global $conn;
	$userName = strtolower($conn->real_escape_string($_POST['username'])); 
	$email = $conn->real_escape_string($_POST['email']);
 	$password = sha1($conn->real_escape_string($_POST['password'])); 
	$stream = strtolower($conn->real_escape_string($_POST['streamname']));
	$hash = sha1($password." ".$email); 
	$sql = "INSERT INTO Users (email, user_name, user_password, stream_name, hash)
	VALUES ('$email','$userName','$password', '$stream','$hash' )";
	$result = $conn->query($sql);
	$conn->close();
	if($result) 
		{ 
			$p=md5($password);
			echo "<!doctype html>
<html>
<head>
<meta charset='utf-8'>
<title>{$stream}</title>
 <LINK href='../css/styles.css' rel='stylesheet' type='text/css'>
 <LINK href='../css/flowy.css' rel='stylesheet' type='text/css'>

</head>

<div class='mainDiv' style = ' border: 2px solid;
    border-radius: 25px;'>
<div class='header'> <h2 > Flowy </h2></div>
<div style = 'border-bottom: 2px solid;'> <a href='http://cooperandrewjackson.com/flowy.php'> <img src='http://178.62.77.84/images/sine1.png' width='332' height='84' alt=''/> </a></div>
<div class='mainVideo'>
<h3> {$stream}</h3>

<script type='text/javascript' src='http://178.62.77.84/jwplayer/jwplayer.js'></script>
<div id='mainVid' >Hopefully you wont see this</div>

<script type='text/javascript'>
jwplayer('mainVid').setup({
    file: 'rtmp://178.62.77.84/flowy/{$hash}',
    height: 565,
    width: 1000,

});

</script>
</div>
<h3>
Your registration is complete
</h3>
<p> Your URL is <a href='http://cooperandrewjackson.com/users/{$userName}.html'> http://cooperandrewjackson.com/users/{$userName}.html </a> </p>
<br>
<p> Setup OBS: Go to Broadcast Settings.</p><br>
<p> FMS URL should be 'rtmp://cooperandrewjackson.com/flowy'.</p><br>
<p> Stream key should be {$hash}?p={$p}</p>

<br>
<br>
<br>
<p> How did I get here. </p>
"; 
			

$stringData = "<!doctype html>
<html>
<head>
<meta charset='utf-8'>
<title>{$stream}</title>
 <LINK href='../css/styles.css' rel='stylesheet' type='text/css'>
 <LINK href='../css/flowy.css' rel='stylesheet' type='text/css'>

</head>

<div class='mainDiv' style = ' border: 2px solid;
    border-radius: 25px;'>
<div class='header'> <h2 > Flowy </h2></div>
<div> <a href='http://cooperandrewjackson.com/flowy.php'> <img src='http://178.62.77.84/images/sine1.png' width='332' height='84' alt=''/> </a></div>
<div class='mainVideo'>
<h3> {$stream}</h3>

<script type='text/javascript' src='http://178.62.77.84/jwplayer/jwplayer.js'></script>
<div id='mainVid' >Hopefully you wont see this</div>

<script type='text/javascript'>
jwplayer('mainVid').setup({
    file: 'rtmp://178.62.77.84/flowy/{$hash}',
    height: 565,
    width: 1000,

});

</script>
</div>
<h3>
Live Videos go here
</h3>

<p> How did I get here. </p>


</div>
</body>
</html>

";   




} } 
	function SignUp()
	 {
	
	 { 
	 	global $conn;
		$userName = $conn->real_escape_string($_POST['username']); 
		$stream = $conn->real_escape_string($_POST['streamname']); 
	 	$sql = "SELECT user_name FROM Users WHERE user_name = '$userName'  ";
	 	$result = $conn->query($sql);
	 if($result->num_rows ==0 ) { 
	 	newuser(); } 
	 	else 
	 		{ 
 while($row = $result->fetch_assoc ()) {
        echo $row["user_name"]. "was taken"; 
    }


} } }
		 if(isset($_POST['username'])) { SignUp(); 
	 } 
	 ?>
