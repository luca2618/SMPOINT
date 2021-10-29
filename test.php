<?php
include("user_class.php");
include("db_connect.php"); // Forbinder til databasen.


$lucas = new bruger("s214636");
//$lucas->addpoint(5,"","");
$lucas->fremmødt();
echo($lucas->point);

$lucas->update()

?>