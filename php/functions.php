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
function logout(){
session_start();
unset($_SESSION["username"]);
header('Location: ../flowy.php'); 
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