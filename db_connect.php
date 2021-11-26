<?php
	$db = mysqli_connect("localhost:3306", "root", "", "SMDATABASE"); // Information til forbindelse til database.
	if(mysqli_connect_errno() > 0) { // Forbinder til database. Hvis mere end 0 fejl ved forbindelse, så gives fejlbesked.
		die("Unable to connect to database: " . mysqli_connect_error($db));
	}
?>