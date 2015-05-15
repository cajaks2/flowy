		$(document).ready(function () {

		});
		//Onlick events for PHP 
		$(document).click(function (event) { //Hides the overlay if the user clicks elsewhere. 
		    if (!$(event.target).closest('#overlay').length) {
		        if ($('#overlay').is(":visible")) {
		            $("#overlay").addClass('hidden');
		        }
		    }
		    if ($(event.target).is($('#newUser'))) { //Brings up the overlay for the PHP image 
		       // $('#overlay').html('<iframe style= " display:block; width:100%; height: 100%" id="frame" src="http://cooperandrewjackson.com/newUser.html" frameborder="0"></iframe>');
		        $("#overlay").removeClass('hidden');
			
		        //shows the overlay. 
   
		}});
