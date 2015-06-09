<?php
$servername = "localhost";
$username = "root";
$password = "mysqlpass";
$dbname = "flowy";
$conn = new mysqli($servername, $username, $password, $dbname);
if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    switch($action) {
        case 'changeStreamName' : changeStreamName();break;
        case 'changeGameName' : changeGameName();break;
        case 'logout' : logout();break;
	case 'picture' : changePicture();break;
	case 'loadStreams' : loadStreams();break;
	case 'topStreams' : topStreams();break;

        // ...etc...
    }
}


function changeStreamName(){
	session_start();
     if (isset($_SESSION['username'])) {
      global $conn;
      $user = $conn->real_escape_string($_SESSION['username']);   
      $newstream =$conn->real_escape_string($_POST['streamname']);
 
      $sql = "UPDATE Users SET stream_name='$newstream' WHERE user_name = '$user'";
      $result = $conn->query($sql);
 } 
}

function changeGameName(){
	session_start();
     if (isset($_SESSION['username'])) {
      global $conn;
      $user = $conn->real_escape_string($_SESSION['username']);   
      $newgame =$conn->real_escape_string($_POST['gamename']);
 
      $sql = "UPDATE Users SET game_playing='$newgame' WHERE user_name = '$user'";
      $result = $conn->query($sql);
 } 
}
function generateThumbs(){

 global $conn;
 $sql = "SELECT user_name, hash FROM Users WHERE live=1";
 $result = $conn->query($sql);
 while($row = $result->fetch_row()) {
	$shell = "hi{$row[0]}";
 $shell2 = "/root/bin/ffmpeg -y -i \"rtmp://178.62.77.84/flowy/{$row[1]} live=1\" -qscale:v 2 -f image2 -vframes 1 -s '1280x800' /usr/local/nginx/html/users/images/thumbs/{$row[0]}thumb.jpg";
 shell_exec($shell2);
      }
}
function logout(){
session_start();
unset($_SESSION["username"]);
header('Location: ../flowy.php'); 
}
function loadStreams(){
global $conn;
$counter =0;
$streams = array();
$sql = "Select user_name,stream_name, viewers from Users where live=1";
$result = $conn->query($sql);
 while($row = $result->fetch_assoc ()) {
        $counter++;
 $streamshort=$row['stream_name'];
        $usershort = $row['user_name'];
        if(strlen($streamshort)>17){
          $streamshort=substr($streamshort,0,25)."...";
        }
        if(strlen($usershort)>17){
          $usershort=substr($usershort,0,25)."...";
        }
      $views = $row['viewers'];
      if($views<0){
        $views = 0;
      }
		$filename=$usershort;
		if(file_exists("/usr/local/nginx/html/users/images/thumbs/{$usershort}thumbsmall.jpg")){
			$filename = "../users/images/thumbs/{$usershort}thumbsmall.jpg";
		}else{
			//if(file_exists("/usr/local/nginx/html/users/images/{$usern}.jpg")){
			//$filename = "../users/images/{$usern}.jpg";
		   // } else {
				$filename = "../users/images/thumbs/genericthumb.jpg";
			
		}
       $streams[]="<div class='col-md-3 col-xs-6'>
       <a href=../users/user?user={$row['user_name']}>
       <img src={$filename}>
             <h4>
                <strong>{$streamshort}</a></strong>
              </h4>
              <h5>By {$usershort} </h5>
              <h6>{$views} currently watching.</h6>
            </div>";
    }
$totalString="";

    if($counter<1){
      	$live="There is no one currently streaming";
$totalString ="<h6>{$live}</h6>";

}
foreach ($streams as $value) {
$totalString= $totalString."".$value;
}    
echo $totalString;
}
function topStreams(){
global $conn;
$streams = array();
$sql = "select user_name, hash, Users.stream_name,Users.viewers, total from Users, (select *  from (select stream_name,count(*) as total from viewers group by stream_name)as temp ORDER BY total desc)as temp2 where Users.hash = temp2.stream_name order by total desc limit 4";
$result = $conn->query($sql);
 while($row = $result->fetch_assoc ()) {
        
 $streamshort=$row['stream_name'];
        $usershort = $row['user_name'];
        if(strlen($streamshort)>17){
          $streamshort=substr($streamshort,0,25)."...";
        }
        if(strlen($usershort)>17){
          $usershort=substr($usershort,0,25)."...";
        }
      $views = $row['total'];
      if($views<0){
        $views = 0;
      }
		$filename=$usershort;
		if(file_exists("/usr/local/nginx/html/users/images/thumbs/{$usershort}thumbsmall.jpg")){
			$filename = "../users/images/thumbs/{$usershort}thumbsmall.jpg";
		}else{
			//if(file_exists("/usr/local/nginx/html/users/images/{$usern}.jpg")){
			//$filename = "../users/images/{$usern}.jpg";
		   // } else {
				$filename = "../users/images/thumbs/genericthumb.jpg";
			
		}
       $streams[]="<div class='col-md-3 col-xs-6'>
       <a href=../users/user?user={$row['user_name']}>
       <img src={$filename}>
             <h4>
                <strong>{$streamshort}</a></strong>
              </h4>
              <h5>By {$usershort} </h5>
              <h6>{$views} total views.</h6>
            </div>";
    }
$totalString="";
foreach ($streams as $value) {
$totalString= $totalString."".$value;
}    
echo $totalString;
}
function getHeader(){
$returnString = "";
session_start();
if (isset($_SESSION['username'])) {
$returnString = "<li>
                <a href='../settings.php' class='btn btn-primary-outline'>Settings</a>
              </li>
              <li>
                <a href='../php/functions.php?action=logout' data-toggle='modal'>Log out</a>
              </li>";
}else {
$returnString = "<li>
                <a href='' class='btn btn-primary-outline' data-toggle='modal' data-target='#signUp'>Sign Up</a>
              </li>
              <li>
                <a href='' data-toggle='modal' data-target='#login'>Login</a>
              </li>";
}
return $returnString;
}

function login($user, $password){
      global $conn;
      $sql = "SELECT user_password FROM Users WHERE '$user'=user_name" ;
      $result = $conn->query($sql);
      while($row = $result->fetch_row()) {
      if(sha1($password)==$row[0]){
	return true;
      } 
    } 
return false;
}
function changePicture(){
session_start();
 if (isset($_SESSION['username'])) {


$user=$_SESSION['username'];
if(isset($_FILES["file"]["type"]))
{
$validextensions = array("jpeg", "jpg", "png");
$temporary = explode(".", $_FILES["file"]["name"]);
$file_extension = end($temporary);
if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")
) && ($_FILES["file"]["size"] < 50000000)//Approx. 100kb files can be uploaded.
&& in_array($file_extension, $validextensions)) {
if ($_FILES["file"]["error"] > 0)
{
echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
}
else
{
if (file_exists("upload/" . $_FILES["file"]["name"])) {
echo $_FILES["file"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
}
else
{
$photolocation = time();
$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
$targetPath = "/usr/local/nginx/html/users/images/".$user.".jpg"; // Target path where file is to be stored
move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
global $conn;
      $sql = "UPDATE Users SET background_loc = $photolocation  WHERE '$user'=user_name" ;
      $result = $conn->query($sql);


}
}

}
}
}}
?>