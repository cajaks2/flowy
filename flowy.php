
<?php
$servername = "localhost";
$username = "root";
$password = "mysqlpass";
$dbname = "flowy";

include "./php/functions.php";
// Create connection


$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

   global $conn;
  $total = 0;
  $views = getAllViewers();
  $mins = 0;
  $streams = array();
  $counter = 0;
  $header = getHeader();
  $backgroundnum = rand(2,4);

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

$conn->close();

echo "
<!DOCTYPE html>
<html>

<head>
  <meta charset='utf-8' />
  <title>Flowy | Live Streams</title>

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
<script src='js/jquery-1.9.1.js'></script>
<script type='text/javascript'>
var sqlPage=0;
function loadStreams()
{
    $.ajax({ url: '../php/functions.php',
        data: {action:'loadStreams'},
        type: 'post',
        success: function(responseText) {
                  $('#loadStreams').html(responseText);
        }
    });
}
function topStreams()
{
	
		
    $.ajax({ url: '../php/functions.php',
        data: {'action':'topStreams', number:sqlPage, number2:4},
        type: 'post',
        success: function(responseText) {

                  $('#topStreams').html($('#topStreams').html()+responseText);
     			sqlPage=sqlPage+4;
			if(!responseText){ $('#moreButton').attr('onclick', 'collapse()');
			$('#moreButton').html('Collapse');
		
}
        }

    });

}
function collapse()
{
	sqlPage=0;
	    $.ajax({ url: '../php/functions.php',
        data: {'action':'topStreams', number:sqlPage, number2:4},
        type: 'post',
        success: function(responseText) {
                  $('#topStreams').html(responseText);
			sqlPage=sqlPage+4;
			$('#moreButton').attr('onclick', 'topStreams()');
			$('#moreButton').html('More');


        }

    });
location.href = '#streams';


}
     $(document).ready(function() {
		
		 loadStreams();
		 topStreams();
       var refreshId = setInterval(function() {
          loadStreams();
       }, 10000);
    });
</script>

</script>
</head>

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
                <a href='#streams' class='scroll'>Streams</a>
              </li>
			  <li>
                <a href='#topStreamsSection' class='scroll'>Top Streams</a>
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


    <div class='hero-bg' style='background-image: url(img/hero-bg/$backgroundnum.jpg);'>
      <div class='color-overlay'></div>

      <div class='aligned-container'>
        <div class='container'>
          <div class='typing-block'>
            Flowy is: <span class='typed'></span>
          </div>


        </div>
      </div>
    </div>

    <main class='content main-animation'>

      <!-- ***** STREAMS ***** -->
      <section class='digitizer text-center ' id='streams' >

        <div class='container'>
					<div class='margin-top'>

          <div class='text-center'>
            <h1 class='block-header'>Live
              <span>Streams</span>
            </h1>
          </div>
<div class='margin-bottom'></div>

          <div class='row text-center' id='loadStreams'>

          </div>
		  		  <div class='margin-bottom'></div>

        </div>
      </section>

   <main class='content main-animation'>

      <!-- ***** Top Streams ***** -->
      <section class='digitizer text-center' id='topStreamsSection' style='background-image: url(img/parallax-bg-inverted.jpg)' data-stellar-background-ratio='0.5'>

        <div class='container'>
			<div class='margin-top'>
            <h1>Top
              <span>Streamers</span>
            </h1>
          </div>
<div class='margin-bottom'></div>
          <div class='row text-center' id='topStreams'>

          </div>
		  <button id='moreButton' class='btn btn-primary-outline'  onclick='topStreams()'>More</button>

<div class='margin-bottom'></div>


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
                

                <a href='mailto:cooper.andrew.jackson@gmail.com'><i class='fa fa-envelope'>&nbsp;</i>cooper.andrew.jackson@gmail.com</a>
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

</html>
";