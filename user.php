<?php
$servername = "localhost";
$username = "root";
$password = "mysqlpass";
$dbname = "flowy";
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

  $username = $conn->real_escape_string($_GET['user']);
  if (!isset($_GET['user'])){
    $username = 'kok';
  } else {
     $username = $conn->real_escape_string($_GET['user']);
  }
  $sql = "Select viewers from Users where user_name = '{$username}'";
  $result = $conn->query($sql);
  $total = 0;
  $views = 0;
  $liveText = "There is no one currently streaming '{$username}'.";
  $streams = array();
  $counter = 0;

  $mins = 0;
  $live = "Not Currently Streaming {$username}";
while($row = $result->fetch_assoc ()) {
        $views = $row["viewers"];
    }
    if($views<0){
      $views=0;
    }   
   $sql = "Select count(*) as total from viewers, Users where Users.hash=viewers.stream_name AND Users.user_name = '{$username}'";
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc ()) {
        $total = $row["total"];
    }
    $mins = round($mins, 2);
$sql = "select (sum(time_done)- sum(time))/60 as time from viewers, Users where Users.hash=viewers.stream_name AND Users.user_name = '{$username}' AND time_done IS NOT NULL";
$result = $conn->query($sql);
 while($row = $result->fetch_assoc ()) {
        $mins = $row["time"];
    }
$sql = "Select live from Users where user_name = '{$username}'";
$result = $conn->query($sql);
 while($row = $result->fetch_assoc ()) {
        if($row["live"]==true){
          $live = "Live";
        }
    }

$conn->close();



echo '
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <title>Flowy | kok</title>

  <!--SEO Meta Tags-->
  <meta name="description" content="Flowy: kok" />
  <meta name="keywords" content="video streaming" />
  <meta name="author" content="kok" />

  <!--Mobile Specific Meta Tag-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

  <!--Favicon-->
  <link rel="shortcut icon" href="favicon.png" type="image/x-icon">

  <!-- Page Specific Styles -->
  <link href="css/style.css" rel="stylesheet">
  
  <!-- Page Specific Color Styles -->
  <link class="color-scheme" href="css/colors/blue.css" rel="stylesheet" media="screen">

  <!--Modernizr-->
  <script src="js/libs/modernizr.custom.js"></script>

  <!--Modernizr extention-->
  <script src="js/libs/detectizr.min.js"></script>
</head>

<body class="parallax">
  <span class="scroll-up scrollTop-btn"><i class="fa fa-chevron-up"></i></span>
  

    
    <!-- Navbar -->
    <header class="header header-animated">
      <div class="container">
        <nav class="navbar navbar-default" role="navigation">
          <div class="navbar-header">
            <a class="logo" href="../flowy#streams">
              <img src="img/logo-dark.png" data-logo-default="img/logo-light.png" data-logo-alt="img/logo-dark.png" alt="" />
            </a>
          </div>

          <div class="nav-toggle collapsed" data-toggle="collapse" data-target="#navbarMain">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="nav navbar-nav navbar-right">
              <li>
                <a href="" class="btn btn-primary-outline" data-toggle="modal" data-target="#signUp">Sign Up</a>
              </li>
              <li>
                <a href="" data-toggle="modal" data-target="#login">Login</a>
              </li>
            </ul>

            <ul class="nav navbar-nav collapsed-color">

              <li class="active">
                <a href="#">Home</a>
              </li>

              <li>
                <a href="#digit-animation" class="scroll">Statistics</a>
              </li>            

            </ul>
          </div>
          <!-- .navbar-collapse -->
        </nav>
      </div>
    </header>
    <!-- ***** HEADER END ***** -->

    <!-- ***** HERO BACKGROUND BLOCK ***** -->
    <div class="hero-bg video-player" style="background-image: url(img/hero-bg/fader1/3.jpg);">
      <div class="color-overlay"></div>

      <div class="text-center">
        <div class="container">
          <h2 class="">
            very sexy space fox <span>by</span> kok
          </h2>

        <p class="margin-bottom">
        '.$live.'
        </p>
          <div class="home-player-container">
            <div class="embed-responsive embed-responsive-16by9">
            
             <script type="text/javascript" src="http://178.62.77.84/jwplayer/jwplayer.js"></script>
<div id="mainVid">Hopefully you wont see this</div>

<script type="text/javascript">
jwplayer("mainVid").setup({
    file: "rtmp://178.62.77.84/flowy/e37ff2e0a1bef2277e445883c3066b3a4311254d",
    height: 562,
    width: 1000,

});
</script>
</div>

            </div>
          </div>
        </div>
      </div>
    </div>

          </div>
        </div>
      </section>
    <main class="content main-animation">
 <section id="digit-animation" class="digitizer text-center" style="background-image: url(img/parallax-bg.jpg)" data-stellar-background-ratio="0.5">

        <div class="color-overlay"></div>

        <div class="container">
          <div class="margin-top-2x">
            <h2>User
              <span>Statistics</span>
            </h2>
            
          </div>

          <div class="row animated-digits margin-top margin-bottom-2x">

            <div class="col-md-4 col-sm-4 text-center">
              <ul class="list-inline">
                <li>
                  <i class="icon-display fa-4x"></i>
                </li>
                <li>
                  <div class="digit" data-number="'.$views.'">0</div>
                </li>
              </ul>

              <h4>
                Current Viewers
              </h4>
           
            </div>

            <div class="col-md-4 col-sm-4 text-center">
              <ul class="list-inline">
                <li>
                  <i class="icon-smile fa-4x"></i>
                </li>
                <li>
                  <div class="digit" data-number="'.$total.'">0</div>
                </li>
              </ul>

              <h4>
                Total Views
              </h4>
             
            </div>

            <div class="col-md-4 col-sm-4 text-center">
              <ul class="list-inline">
                <li>
                  <i class="icon-data fa-4x"></i>
                </li>
                <li>
                  <div class="digit" data-number="'.$mins.'">0</div>
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
    <footer class="footer">

     

      <div class="footer-content">
        <div class="container">
          <div class="row">
            <div class="col-sm-6 col-md-3">
              <h5>About Flowy</h5>

              <p>Flowy - Low latency streaming.</p>
            </div>

        
            <div class="col-sm-6 col-md-3">
              <h5>Contact Info</h5>

              <address>
                

                <a href="mailto:cajaks2@cooperandrewjackson.com"><i class="fa fa-envelope">&nbsp;</i>admin@cooperandrewjackson.com</a>
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
  <div class="modal fade" id="signUp" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-center form-dialog">
      <div class="modal-content text-center">
        <div class="modal-header" style="background-image: url(img/parallax-bg.jpg)">
          Flowy
        </div>
        <div class="modal-body">
          <h3>Sign Up Now<span> Free!</span></h3>
          

          <form method="post" class="contact-form form-validate3 " action="../user-signup.php">

            <div class="form-group">
              <input class="form-control input-lg" type="text" name="username" id="username" placeholder="User Name" required autocomplete="off">
            </div>

            <div class="form-group">
              <input class="form-control input-lg" type="text" name="streamname" id="streamname" placeholder="Stream Name" required autocomplete="off">
            </div>

            <div class="form-group">
              <input class="form-control input-lg" type="email" name="email" placeholder="E-mail" required autocomplete="off">
            </div>

            <div class="form-group">
              <input type="password" name="pass" class="form-control" placeholder="Password" required autocomplete="off">
            </div>

            <input class="btn btn-md btn-primary btn-center" type="submit" value="Sign Up">
          </form>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <!-- ***** LOGIN MODAL ***** -->
  <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-center form-dialog">
      <div class="modal-content text-center">
        <div class="modal-header" style="background-image: url(img/parallax-bg.jpg)">
          Flowy
        </div>
        <div class="modal-body">
          <h3>Login and Get <span>Started</span></h3>
          <span class="subtitle">Just fill in the form below</span>

          <form method="post" class="contact-form form-validate4">

            <div class="form-group">
              <input class="form-control input-lg" type="email" name="email" placeholder="E-mail" required autocomplete="off">
            </div>

            <div class="form-group">
              <input type="password" name="pass" class="form-control" placeholder="Password" required autocomplete="off">
            </div>

            <input class="btn btn-md btn-primary btn-center" type="submit" value="Login">
          </form>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>




  <!-- *********** ALL JS PLUGINS AND LIBRARIES ************* -->

  <!-- Jquery lib -->
  <script src="js/libs/jquery-1.11.1.min.js"></script>

  <!-- Bootstrap js -->
  <script src="js/plugins/bootstrap.min.js"></script>

  <!-- Parallax -->
  <script src="js/plugins/jquery.stellar.min.js"></script>

  <!-- Page smooth scroll -->
  <script src="js/plugins/smoothscroll.js"></script>

  <!-- Lightbox plugin -->
  <script src="js/plugins/jquery.prettyPhoto.min.js"></script>

  <!-- Waypoint plugin -->
  <script src="js/plugins/waypoints.min.js"></script>

  <!-- Numbers animate plugin -->
  <script src="js/plugins/jquery-numerator.min.js"></script>

  <!-- Circle progress bar plugin -->
  <script src="js/plugins/jquery.circliful.min.js"></script>

  <!-- Parallax plugin -->
  <script src="js/plugins/owl.carousel.min.js"></script>

  <!-- Auto typing text -->
  <script src="js/plugins/typed.min.js"></script>

  <!-- Map api -->
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASm3CwaK9qtcZEWYa-iQwHaGi3gcosAJc"></script>

  <!-- Custom checkbox & radio plugin -->
  <script src="js/plugins/icheck.min.js"></script>

  <!-- Countdown -->
  <script src="js/plugins/jquery.countdown.min.js"></script>

  <!-- Form validate plug -->
  <script src="js/plugins/jquery.validate.min.js"></script>

  <!-- Form validate plug -->
  <script src="mailer/mailer.js"></script>


  <!-- Custom js with all initialisation and plugin settings -->
  <script src="js/custom.js"></script>




</body>

</html>
';
?>
