<?php
include("user_class.php");
include("db_connect.php"); // Forbinder til databasen.


$lucas = new bruger("s214636");
$lucas->addpoint(1);
echo($lucas->point);

?>