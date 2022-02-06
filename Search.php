

<head>
        <link rel="stylesheet" href="./style/Stylesheet.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Search</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script> <!-- Tilføjer javascript-library "jqeury" -->
</head>


<?php 
include("./navbar/Navbar.php"); // Indkluderer navbar.
include("./config/db_connect.php"); // Forbinder til databasen.
include("user_class.php");
//variables for meeting form dates and stuff
$today = date('Y-m-d');








?>
<br> <br>

<div class="row">

<div style="" class="column">
  <form action="" method="get" class="form">
  <text class="title">Search</text><br>
  <text class="subtitle">Søg på studie nr. for at hente aktiviter og information</text>

  <div class="input-container ic1">
    <input type="text" id="studienr" name="studienr" class="input" placeholder=" "><br><br>
    <div class="cut"></div>
    <label for="studienr" class="placeholder">Studienr:</label>
  </div>
    <input type="submit" value="Search" name="submit" class="submit">
    </form>

</div>

<!--Form for searching meetings-->
<div style="" class="column">
  <form action="" method="get" class="form">
      <text class="title">Søg møde</text>
      <br>
      <div class="input-container ic1">
      <input type="date" id="dato" name="dato" required class="input" placeholder=" " 
      <?php echo("value=\"$today\"
        min=\"$today\" max=\"2025-12-31\"");?>><br><br>
      <div class="cut"></div>
      <label for="dato" class="placeholder">Dato:</label>
      </div>

      <input type="submit" value="Søg møde" name="submit" class="submit">

      </form>
  </div>




</div> 

<br><br>

<?php

if (isset($_GET['submit'])){
switch ($_GET['submit']) {
  case "Search":

    echo("<div class=\"bluebox\">");
    $studienr = $_GET['studienr'];
    echo("<text class=\"subtitle\"> ");
    $person = new bruger($studienr);
    $person->update_points();
    echo("</text>");
    #get all aktivities by id ascending order
    ?>
    <text class="title"><?php echo($person->navn); ?> </tekst><br>
    <text class="subtitle">Studienummer: <?php echo($person->studienr); ?> </tekst><br>
    <text class="subtitle">Total points:<?php echo($person->point); ?> </tekst><br><br>
    <?php
      $result = $person->aktivitets_liste;
      if ($result != null)  {
          // output data of each row
          echo("<table>
          <tr>
          <th>Point ID</th>
          <th>Aktivitet</th>
          <th>Point</th>
          <th>Kommentar</th>
          <th>Dato</th>
          </tr>");
          foreach ($result as $row) {
            console_log($result);
              echo("<tr> <th>id: " . $row["id"]. "</th><th> " . $row["aktivitet"]. "</th><th>"
              . $row["point"] . "</th><th>" . $row["kommentar"]. "</th><th>" . $row["dato"] . "</th></tr>");
          }
      } else {
          echo ("<br><br><th>0 results</th><br>");
      }
      echo("</table>");
      mysqli_close($db);
  
      break;

  case "Søg møde":
        $møde_dato = $_GET['dato'];
        $mødeliste_sqli = "SELECT * FROM `aktiviteter` WHERE (`aktivitet` = 'Studierådsmøde' and `dato` = '$møde_dato') " ;
        $mødeliste_result = mysqli_query($db, $mødeliste_sqli);
        echo("<div class=\"bluebox\">");
        echo("<text class=\"subtitle\"> ");
        if ($mødeliste_result != null)  {
          
          // output data of each row
          echo("
          <table>
          <tr>
          <th>Studienr</th>
          <th>Navn</th>
          <th>Konstitueret</th>
          <th>Kommentar</th>
          </tr>");
          foreach ($mødeliste_result as $row) {
              $person = new bruger($row["studienr"]);
              $navn = $person->navn;
              $konstitueret = "Ja";

              echo("<tr> <th>" . "<a class=\"link\"  href=\"./search?studienr=". $row["studienr"]."&submit=Search\">". $row["studienr"]."</a>"
              . "</th><th> " . $navn. "</th><th>"
              . $konstitueret . "</th><th>" . $row["kommentar"]. "</th></tr>");
          }
          } else {
              echo ("<br><br><th>0 results</th><br>");
          }

          echo("</div>");
            break;
          }
        
  
}
?>
<div>



