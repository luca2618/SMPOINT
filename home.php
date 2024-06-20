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
<h1> S/M-KID point </h1>
<tekst> 
Velkommen til S/M-KID rådets pointside. <br>
 Her kan du se, hvilke point du selv og andre har opnået, 
 samt indstille dig selv og andre til point for opgaver, i har lavet i rådet.
</tekst>
<h2> Hvad er S/M-KID point?</h2>
<tekst> 
S/M-KID point er måden, vi her i rådet følger med i, hvilke frivillige opgaver der er, og hvem der udfører dem. <br>
Pointene er en måde at anerkende og belønne jeres indsats for at gøre S/M-KID til et bedre studiemiljø og fællesskab.
</tekst>

<h2> Hvad kan jeg bruge S/M-KID point til?</h3>
<tekst>
Hvad kan jeg bruge S/M-KID point til? S/M-KID point bestemmer 
den interne prioritering af årsfestbilletterne, som rådet får tildelt. <br>
Jo flere point du har, jo større er din chance for at få en billet til den festlige begivenhed.

</tekst>
<br>

<h2> Hvordan får jeg S/M-KID point?</h3>
<tekst> Du får S/M-KID point ved at udføre forskellige frivillige opgaver i S/M-KID rådet 
og ved at møde op til studierådsmøderne. <br>Nogle opgaver giver flere point end andre,
 afhængigt af deres sværhedsgrad, varighed og betydning. <br> Du kan også få bonuspoint for
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


