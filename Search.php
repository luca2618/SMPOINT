

<head>
        <link rel="stylesheet" href="./style/Stylesheet.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Search</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script> <!-- Tilføjer javascript-library "jqeury" -->
</head>


<?php 
include_once("./navbar/Navbar.php"); // Indkluderer navbar.
include_once("./config/db_connect.php"); // Forbinder til databasen.
include_once("user_class.php");
include_once("name_fill.php");
//variables for meeting form dates and stuff
$today = date('Y-m-d');
$konstituerede = fetch_konstituerede();
?>



<br> <br>

<div class="row">

<div style="" class="column">
<form action="" method="get" class="form">
  <text class="title">Search</text><br>
  <text class="subtitle">Søg på studie nr. eller navn for at hente aktiviter og information</text>

<div class="input-container ic1"> 
  <input list="navneliste" id="navn" name="navn" autocomplete="off" onkeyup="Updatestudienr();" class="input" placeholder=" "><br><br>
  <div class="cut"></div>
  <datalist id="navneliste">
  <?php
  foreach ($konstituerede as $medlem){
    echo("<option value=\"");
    echo($medlem['navn']);
    echo("\">");
  }
  ?>
  </datalist>
  <label for="Navn" class="placeholder">Navn:</label>
</div>

<div class="input-container ic1"> 
  <input list="studienrliste" id="studienr" name="studienr" required autocomplete="off" onkeyup="Updatenavn();" class="input" placeholder=" "><br><br>
  <div class="cut"></div>
  <datalist id="studienrliste">
  <?php
  foreach ($konstituerede as $medlem){
    echo("<option value=\"");
    echo($medlem['studienr']);
    echo("\">");
  }
  ?>
  </datalist>
  <label for="studienr" class="placeholder">Studienr:</label>
</div>




    <input type="submit" value="Search" name="submit" class="submit">
    </form>

</div>



</div> 

<br><br>

<?php

if (isset($_GET['submit'])){
switch ($_GET['submit']) {
  case "Search":
    $studienr = mysqli_real_escape_string($db, $_GET['studienr']);
    echo("<div class=\"bluebox\">");
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
              echo("<tr> <th>id: " . $row["id"]. "</th><th> " . $row["aktivitet"]. "</th><th>"
              . $row["point"] . "</th><th>" . $row["kommentar"]. "</th><th>" . $row["dato"] . "</th></tr>");
          }
      } else {
          echo ("<br><br><th>0 results</th><br>");
      }
      echo("</table>");
      mysqli_close($db);
  
      break;
        
    }
}
?>
<div>



