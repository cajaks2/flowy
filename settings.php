<?php
$servername = "localhost";
$username = "root";
$password = "mysqlpass";
$dbname = "flowy";

// Create connection
include "./php/functions.php";

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

   global $conn;
  $sql = "Select sum(viewers) as totalsum from Users";
  $result = $conn->query($sql);
  $total = 0;
  $views = 0;
  $mins = 0;
  $key = "Default key <br><br>";
  $streamname = "Default stream name ";
  $username = "Error";
  $header = "";
  $title = "<h1>Settings: <span>Your User Information</span> </h1>";
  $streams = array();
  $counter = 0;
while($row = $result->fetch_assoc ()) {
        $views = $row['totalsum'];
    }
if($views<0){
$views =0; 
}
   $sql = "Select count(*) as total from viewers";
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc ()) {
        $total = $row['total'];
    }

    $mins = round($mins, 2);
$sql = "select (sum(time_done)- sum(time))/60 as time from viewers WHERE time_done IS NOT NULL";
$result = $conn->query($sql);
 while($row = $result->fetch_assoc ()) {
        $mins = $row['time'];
    }

     session_start();
    if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $authed = login($user, $pass);

   if ($authed) {
      $header = getHeader();
       $_SESSION['username'] =  $user;
    }
}

 if(isset($_POST['option']) && $_POST['option']=="sign") { signUp(); 
   } else {
 showSettings(); 
   }



$conn->close();
function signUp(){
  logout();
  global $conn;
  global $title;
  $username = $conn->real_escape_string($_POST['username']); 
  $username = preg_replace("/[^a-zA-Z0-9]+/", "", $username);
  $email = $conn->real_escape_string($_POST['email']);
  $sql = "select * from Users where user_name = '$username';";
  $result = $conn->query($sql);
  if($result->num_rows ==0){
  $password = sha1($conn->real_escape_string($_POST['password'])); 
  $stream = htmlentities($conn->real_escape_string($_POST['streamname']));
  $hash = sha1($password." ".$email." ".time()); 
  $sql = "INSERT INTO Users (email, user_name, user_password, stream_name, hash)
  VALUES ('$email','$username','$password', '$stream','$hash' )";
  $result = $conn->query($sql);
  session_start();
  $_SESSION['username'] =  $username;
  showSettings();
} else {
  $title = "<h1>Error: <span>User name already in use</span> </h1>";

}
}

function showSettings(){
      global $conn;
      global $streamname;
	  global $gamename;
	  global $username;
      global $key;
      global $title;
      global $imageloc;
	  global $backloc;
	  global $header;
	  $header = getHeader();
      $title = "<h1>Error: <span>Please Log In</span> </h1>";
      $user = $_SESSION['username'];
	  $username = $user;
      if(isset($_SESSION['username'])){
      $sql = "SELECT hash,stream_name, user_password, game_playing,background_loc FROM Users WHERE '$user' = user_name" ;
      $result = $conn->query($sql);
      while($row = $result->fetch_row()) {
        $streamname = $row[1];
		$gamename = $row[3];
		$backloc = $row[4];
        $key = ($row[0]."?p=".md5($row[2]));
       $title = "<h1>$user: <span>User Information</span> </h1>";
      } 
	if(file_exists("/usr/local/nginx/html/users/images/{$user}.jpg")){
	$imageloc = $user;
	}else{
	$imageloc="default";
	}
    }
     
    
   
  }
     
        

      

echo "
<!DOCTYPE html>
<html>

<head>
  <meta charset='utf-8' />
  <title>Flowy | Settings</title>

  <!--SEO Meta Tags-->
  <meta name='description' content='Flowy: Live Streams' />
  <meta name='keywords' content='video streaming' />
  <meta name='author' content='Live Streams' />

  <!--Mobile Specific Meta Tag-->
  <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'>

  <!--Favicon-->
  <link rel='shortcut icon' href='favicon.png' type='image/x-icon'>

  <!-- Page Specific Styles -->
  <link href='css/style.css' rel='stylesheet'>
  
  <!-- Page Specific Color Styles -->
  <link class='color-scheme' href='css/colors/blue.css' rel='stylesheet' media='screen'>

  <!--Modernizr-->
  <script src='js/libs/modernizr.custom.js'></script>

  <!--Modernizr extention-->
  <script src='js/libs/detectizr.min.js'></script>
  <script src='js/jquery-1.9.1.js'></script>
</head>
<script type='text/javascript'>
function changeStreamName()
{
    $.ajax({ url: '../php/functions.php',
        data: {'streamname':$('#streamnameInput').val().substring(0,35), 'action':'changeStreamName'},
        type: 'post',
        success: function(output) {
                  $('#streamname').text($('#streamnameInput').val().substring(0,35));
                  $('#streamnameInput').val('');
        }
    });
}
function changeGameName()
{
    $.ajax({ url: '../php/functions.php',
        data: {'gamename':$('#gamenameInput').val().substring(0,35), 'action':'changeGameName'},
        type: 'post',
        success: function(output) {
                  $('#gamename').text($('#gamenameInput').val().substring(0,35));
                  $('#gamenameInput').val('');
        }
    });
}
$(document).ready(function(e) {
  $('#uploadimage').on('submit',(function(e) {
e.preventDefault();
$.ajax({
url: '../php/functions.php', // Url to which the request is send
type: 'POST',             // Type of request to be send, called as method
data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
contentType: false,       // The content type used when sending data to the server.
cache: false,             // To unable request pages to be cached
processData:false,        // To send DOMDocument or non processed data file it is set to false
success: function(data)   // A function to be called if request succeeds
{
location.reload();
}
});
}));
});


</script>
<body class='parallax'>
  <span class='scroll-up scrollTop-btn'><i class='fa fa-chevron-up'></i></span>
  

    
    <!-- Navbar -->
    <header class='header header-animated'>
      <div class='container'>
        <nav class='navbar navbar-default' role='navigation'>
          <div class='navbar-header'>
            <a class='logo' href='../flowy'>
              <img src='img/logo-dark.png' data-logo-default='img/logo-light.png' data-logo-alt='img/logo-dark.png' alt='' />
            </a>
          </div>

          <div class='nav-toggle collapsed' data-toggle='collapse' data-target='#navbarMain'>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class='collapse navbar-collapse' id='navbarMain'>
            <ul class='nav navbar-nav navbar-right'>
           $header
            </ul>
            <ul class='nav navbar-nav collapsed-color'>

              <li class='active'>
                <a href='#'>Home</a>
              </li>
              <li>
                <a href='#Information' class='scroll'>Information</a>
              </li>
              <li>
                <a href='#background' class='scroll'>Change Picture</a>
              </li>

              <li>
                <a href='#digit-animation' class='scroll'>Statistics</a>
              </li>


            

            </ul>
          </div>
          <!-- .navbar-collapse -->
        </nav>
      </div>
    </header>
    <!-- ***** HEADER END ***** -->


    <!-- ***** HERO BACKGROUND BLOCK ***** -->
    <div class='hero-bg' style='background-image: url(users/images/$username.jpg?$backloc)'>
      <div class='color-overlay'></div>
      
      <div class='hero-fader' >
        <div class='aligned-container'>
          <div class='container'>
           $title

           
              
            </p>

           
          </div>
        </div>
      </div>
    </div>
    
    <main class='content main-animation'>

   

      <!-- ***** TEXT + ICONS ***** -->
      <section class='text-and-icon' id = 'Information'>
        <div class='container margin-top margin-bottom-2x'>
          <div class='row'>
            <div class='col-md-6 col-sm-12'>
              <h2 class='color'>All your Flowy <span>Information!</span></h2>
              <span class='subtitle'>$username's Information</span>

              <p class='margin-bottom'> Make sure your OBS settings are correct if you encounter any errors while streaming in.</p>
              <a href ='http://help.twitch.tv/customer/portal/articles/1262922-open-broadcaster-software'>Check out Twitch's guide for OBS </a>

            </div>

            <div class='col-md-6 col-sm-12'>
              <div class='row'>
                <div class='col-xs-2'>
                  <i class='icon-imac fa-4x'></i>
                </div>
                  <div class='col-xs-10'>
                  <h4>FMS URL</h4>
                  <p>rtmp://cooperandrewjackson.com/flowy</p>
                  <br>
                </div>
                <div class='col-xs-2'>
                  <i class='icon-smile fa-4x'></i>
                </div>
                <div class='col-xs-10'>
                  <h4>Unique Stream Key:</h4>
                  <p>$key</p>
                </div>
                <div class='col-xs-2'>
                  <i class='icon-target fa-4x'></i>
                </div>
                <div class='col-xs-10'>
                  <h4>Current Stream Title</h4>
                  <p id='streamname'>$streamname </p>
                   <input type='text' name='streamnameInput' id='streamnameInput' placeholder='New Stream Name' required autocomplete='off'>
              <button onclick='changeStreamName()'>Submit</button>

              </div>
			   <div class='col-xs-2'>
                  <i class='icon-skull fa-4x'></i>
                </div>
			      <div class='col-xs-10'>
                  <h4>Current Game Playing </h4>
                  <p id='gamename'>$gamename </p>
                   <input type='text' name='gamenameInput' id='gamenameInput' placeholder='New Current Game' required autocomplete='off'>
              <button onclick='changeGameName()'>Submit</button>

              </div>
            </div>
          </div>
        </div>
      </section>



      <!-- ***** BIG IMG AND TEXT ***** -->
      <section class='margin-top-2x margin-bottom-2x' id='background'>
        <div class='container'>
          <div class='text-center'>
            <h2 class=' color'>Current 
              <span>Background</span>
            </h2>
            <span class='subtitle'>This is your current background</span>
          </div>

          <div class='margin-top margin-bottom'>
            <img src='users/images/$imageloc.jpg' alt='' id'previewimage'>
          </div>

          <div class='row'>
            <div class='col-md-offset-2 col-md-8 text-justify'>
              <p>You can change it!</p>
<form id='uploadimage'  method='post'  enctype='multipart/form-data'>
<input type='hidden' name='action' value='picture'>
        <input id='file' type='file'  name='file' required/><br>
 

        <input type='submit'  value='Upload' class = 'submit'/>        
        </form>
              </div>
            </div>
          </div>
        </div>
      </section>


   

    <main class='content main-animation'>
 <section id='digit-animation' class='digitizer text-center' style='background-image: url(img/parallax-bg.jpg)' data-stellar-background-ratio='0.5'>

        <div class='color-overlay'></div>

        <div class='container'>
          <div class='margin-top-2x'>
            <h2>Users'
              <span>Statistics</span>
            </h2>
            
          </div>

          <div class='row animated-digits margin-top margin-bottom-2x'>

            <div class='col-md-4 col-sm-4 text-center'>
              <ul class='list-inline'>
                <li>
                  <i class='icon-display fa-4x'></i>
                </li>
                <li>
                  <div class='digit' data-number='{$views}'>0</div>
                </li>
              </ul>

              <h4>
                Current Viewers
              </h4>
           
            </div>

            <div class='col-md-4 col-sm-4 text-center'>
              <ul class='list-inline'>
                <li>
                  <i class='icon-smile fa-4x'></i>
                </li>
                <li>
                  <div class='digit' data-number='{$total}'>0</div>
                </li>
              </ul>

              <h4>
                Total Views
              </h4>
             
            </div>

            <div class='col-md-4 col-sm-4 text-center'>
              <ul class='list-inline'>
                <li>
                  <i class='icon-data fa-4x'></i>
                </li>
                <li>
                  <div class='digit' data-number='{$mins}'>0</div>
                </li>
              </ul>

              <h4>
                Minutes spent watching
              </h4>
              
            </div>

          </div>
        </div>
      </section>


    <!-- ***** HERE IS A FOOTER ***** -->
    <footer class='footer'>

     

      <div class='footer-content'>
        <div class='container'>
          <div class='row'>
            <div class='col-sm-6 col-md-3'>
              <h5>About Flowy</h5>

              <p>Flowy - Low latency streaming.</p>
            </div>

        
            <div class='col-sm-6 col-md-3'>
              <h5>Contact Info</h5>

              <address>
                

                <a href='mailto:cajaks2@cooperandrewjackson.com'><i class='fa fa-envelope'>&nbsp;</i>admin@cooperandrewjackson.com</a>
              </address>
            </div>

          </div>
        </div>
      </div>

      
    </footer>
    <!-- .footer -->

  </div>
  <!-- .wrapper -->



  
<!-- ***** SIGN UP MODAL ***** -->
  <div class='modal fade' id='signUp' tabindex='-1' role='dialog' aria-hidden='true' style='display: none;'>
    <div class='modal-dialog modal-dialog-center form-dialog'>
      <div class='modal-content text-center'>
        <div class='modal-header' style='background-image: url(img/parallax-bg.jpg)'>
          Flowy
        </div>
        <div class='modal-body'>
          <h3>Sign Up Now<span> Free!</span></h3>
          

          <form method='post' class='contact-form form-validate3 ' action='../settings.php'>

            <div class='form-group'>
              <input class='form-control input-lg' type='text' name='username' id='username' placeholder='User Name' required autocomplete='off'>
            </div>

            <div class='form-group'>
              <input class='form-control input-lg' type='text' name='streamname' id='streamname' placeholder='Stream Name' required autocomplete='off'>
            </div>

            <div class='form-group'>
              <input class='form-control input-lg' type='email' name='email' placeholder='E-mail' required autocomplete='off'>
            </div>

            <div class='form-group'>
              <input type='password' name='password' class='form-control' placeholder='Password' required autocomplete='off'>
              <input type='hidden' name='option' value='sign'>
            </div>

            <input class='btn btn-md btn-primary btn-center' type='submit' value='Sign Up'>
          </form>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <!-- ***** LOGIN MODAL ***** -->
  <div class='modal fade' id='login' tabindex='-1' role='dialog' aria-hidden='true' style='display: none;'>
    <div class='modal-dialog modal-dialog-center form-dialog'>
      <div class='modal-content text-center'>
        <div class='modal-header' style='background-image: url(img/parallax-bg.jpg)'>
          Flowy
        </div>
        <div class='modal-body'>
          <h3>Login and Get <span>Started</span></h3>
          <span class='subtitle'>Just fill in the form below</span>

          <form method='post' class='contact-form form-validate4' action='../settings.php'>

            <div class='form-group'>
              <input class='form-control input-lg' type='username' name='username' placeholder='User Name' required autocomplete='off'>
            </div>

            <div class='form-group'>
              <input type='password' name='password' class='form-control' placeholder='Password' required autocomplete='off'>
               <input type='hidden' name='option' value='login'>
            </div>

            <input class='btn btn-md btn-primary btn-center' type='submit' value='Login'>
          </form>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <!-- *********** ALL JS PLUGINS AND LIBRARIES ************* -->

  <!-- Jquery lib -->
  <script src='js/libs/jquery-1.11.1.min.js'></script>

  <!-- Bootstrap js -->
  <script src='js/plugins/bootstrap.min.js'></script>

  <!-- Parallax -->
  <script src='js/plugins/jquery.stellar.min.js'></script>

  <!-- Page smooth scroll -->
  <script src='js/plugins/smoothscroll.js'></script>

  <!-- Lightbox plugin -->
  <script src='js/plugins/jquery.prettyPhoto.min.js'></script>

  <!-- Waypoint plugin -->
  <script src='js/plugins/waypoints.min.js'></script>

  <!-- Numbers animate plugin -->
  <script src='js/plugins/jquery-numerator.min.js'></script>

  <!-- Circle progress bar plugin -->
  <script src='js/plugins/jquery.circliful.min.js'></script>

  <!-- Parallax plugin -->
  <script src='js/plugins/owl.carousel.min.js'></script>

  <!-- Auto typing text -->
  <script src='js/plugins/typed.min.js'></script>


  <!-- Custom checkbox & radio plugin -->
  <script src='js/plugins/icheck.min.js'></script>

  <!-- Countdown -->
  <script src='js/plugins/jquery.countdown.min.js'></script>

  <!-- Form validate plug -->
  <script src='js/plugins/jquery.validate.min.js'></script>

  <!-- Form validate plug -->
  <script src='mailer/mailer.js'></script>


  <!-- Custom js with all initialisation and plugin settings -->
  <script src='js/custom.js'></script>




</body>

</html>";