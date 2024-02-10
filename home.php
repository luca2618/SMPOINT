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
 Her kan du se, hvilke point du selv og andre har opnået, 
 samt indstille dig selv og andre til point for opgaver, I har lavet i rådet.
</tekst>
<h2> Hvad er SM-KID point?</h2>
<tekst> 
SM/KID point er måden, vi her i rådet følger med i, hvilke frivillige opgaver der er, og hvem der udfører dem. 
Pointene er en måde at anerkende og belønne jeres indsats for at gøre SM/KID til et bedre studiemiljø og fællesskab.
</tekst>

<h3> Hvad kan jeg bruge SM-KID point til?</h3>
<tekst>
Hvad kan jeg bruge SM-KID point til? SM-KID point bestemmer 
den interne prioritering af årsfestbilletterne, som rådet får tildelt. 
Jo flere point du har, jo større er din chance for at få en billet til den festlige begivenhed.
<br>
Mere om point her
</tekst>
<br>

<h3> Hvordan får jeg SM-KID point?</h3>
<tekst> Du får SM-KID point ved at udføre forskellige frivillige opgaver i SM-KID rådet 
og ved at møde op til studierådsmøderne. Nogle opgaver giver flere point end andre,
 afhængigt af deres sværhedsgrad, varighed og betydning. Du kan også få bonuspoint for
  at være ekstra aktiv, kreativ eller hjælpsom.
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


