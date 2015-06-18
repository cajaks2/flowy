<?php
$servername = "localhost";
$username = "root";
$password = "mysqlpass";
$dbname = "flowy";
$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT user_name, hash FROM Users WHERE live=1";
      $result = $conn->query($sql);
      while($row = $result->fetch_row()) {
	$shell = "hi{$row[0]}";
 $shell2 = "/root/bin/ffmpeg -y -i \"rtmp://178.62.77.84/flowy/{$row[1]} live=1\" -qscale:v 2 -f image2 -vframes 1 -s '1336x768' /usr/local/nginx/html/users/images/thumbs/{$row[0]}thumb.jpg";
 shell_exec($shell2);
 $shell3 = "/root/bin/ffmpeg -y -i \"rtmp://178.62.77.84/flowy/{$row[1]} live=1\" -qscale:v 2 -f image2 -vframes 1 -s '334x192' /usr/local/nginx/html/users/images/thumbs/{$row[0]}thumbsmall.jpg";
 shell_exec($shell3);
	      }
?>