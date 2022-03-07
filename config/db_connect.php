<?php
	// Enable us to use Headers
    ob_start();

    // Set sessions
    if(!isset($_SESSION)) {
        session_start();
    }
	
	$connection = mysqli_connect("localhost:3306", "root", "", "smdatabase"); // Information til forbindelse til database.
	$db = $connection;
	if(mysqli_connect_errno() > 0) { // Forbinder til database. Hvis mere end 0 fejl ved forbindelse, så gives fejlbesked.
		die("Unable to connect to database: " . mysqli_connect_error($db));
	}

	$recaptcha_key = ""; //Use this secret key for communication between your site and reCAPTCHA


?>