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
Her kan du hvilket point du selv og andre har opnået, 
samt indstillle dig selv og andre til point for opgaver i har lavet i rådet.
</tekst>
<h2> Hvad er SM-KID point?</h2>
<tekst> 
SM/KID point er måden vi her i råddet følger med i hvilke frivilige opgaver
der er og hvem der udfører dem.
</tekst>

<h3> Hvad kan jeg bruge SM-KID point til?</h3>
<tekst>
SM-KID point bestemmer den interne prioritering af årsfestbilleterne som rådet får tildelt.
<br>
Pointene bruges også til vurdere,
</tekst>
<br>

<h3> Hvordan får jeg SM-KID point?</h3>
<tekst> Du får SM-KID point ved at udføre forskellige frivillige 
opgaver i SM-KID rådet, og ved at møde op til studierådsmøderne. 
<br>
Hvor mange point du får for en opgave kan ses i tabellen her:
<br><br>
</tekst>


<?php
//print the activity type table
fetch_aktivitetstype($print=true);
?>



</html>

<?php 
?>


