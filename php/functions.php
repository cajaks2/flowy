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
        case 'logout' : logout();break;
	case 'picture' : changePicture();break;
	case 'loadStreams' : loadStreams();break;
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
          $streamshort=substr($streamshort,0,20)."...";
        }
        if(strlen($usershort)>17){
          $usershort=substr($usershort,0,20)."...";
        }
      $views = $row['viewers'];
      if($views<0){
        $views = 0;
      }
		$filename=$usershort;
		if(file_exists("/usr/local/nginx/html/users/images/thumbs/{$usershort}thumb.jpg")){
			$filename = "../users/images/thumbs/{$usershort}thumb.jpg";
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
              <p>{$views} currently watching.</p>
            </div>";
    }
    if($counter<1){
      	$live="There is no one currently streaming";
$totalString ="<span class='subtitle'>{$live}</span><br><p></p>";

}
foreach ($streams as $value) {
$totalString= $totalString."".$value;
}    
echo $totalString;
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
$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
$targetPath = "/usr/local/nginx/html/users/images/".$user.".jpg"; // Target path where file is to be stored
move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file

}
}

}
}
}}
?>