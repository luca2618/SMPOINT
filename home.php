<!DOCTYPE html>
<html>
<head>
        <link rel="stylesheet" href="./style/Stylesheet.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script> <!-- Tilføjer javascript-library "jqeury" -->
</head>


<body>
<?php
include("./navbar/Navbar.php"); // Indkluderer navbar.
include("user_class.php");
?>

<br>
<h1> SM-KID point </h1>
<tekst> 
Velkommen til SM-KID rådets pointside. <br>
Her kan du hvilket point du selv og andre har opnået, samt 
</tekst>
<h2> Hvad er SM-KID point?</h2>
<tekst> hi </tekst>

<h2> Hvordan får jeg SM-KID point?</h2>
<tekst> Du får SM-KID point ved at udføre forskellige frivillige opgaver i SM-KID rådet, og ved at møde op til studierådsmøderne.  <br><br></tekst>

<?php

fetch_aktivitetstype($print=true);


?>

<h2> Hvad kan jeg bruge SM-KID point til?</h2>
<tekst> Det kan jeg fandme ikke huske, noget med en fest eller noget. </tekst><br><br>

</html>

<?php 
?>


