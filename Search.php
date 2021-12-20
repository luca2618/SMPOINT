

<head>
        <link rel="stylesheet" href="./style/Stylesheet.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Search</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script> <!-- Tilføjer javascript-library "jqeury" -->
</head>


<?php 
include("./navbar/Navbar.php"); // Indkluderer navbar.

?>
<br> <br>
<form action="" method="get" class="form">
<text class="title">Search</text><br>
<text class="subtitle">Søg på studie nr. for at hente aktiviter og information</text>

<div class="input-container ic1">
  <input type="text" id="studienr" name="studienr" class="input" placeholder=" "><br><br>
  <div class="cut"></div>
  <label for="studienr" class="placeholder">Studienr:</label>
</div>
  <input type="submit" value="Search" class="submit">
  </form>
  <br>

<?php


if (isset($_GET['studienr'])){
    echo("<div class=\"bluebox\">");
    $studienr = $_GET['studienr'];
    include("user_class.php");
    include("./config/db_connect.php"); // Forbinder til databasen.
    echo("<text class=\"subtitle\"> ");
    $person = new bruger($studienr);
    echo("</text>");
    #get all aktivities by id ascending order

    $sqli = mysqli_real_escape_string($db,"SELECT * FROM `$studienr` ORDER BY `point_id` DESC");
    $result = mysqli_query($db, $sqli);

    ?>
    <text class="title"><?php echo($person->navn); ?> </tekst><br>
    <text class="subtitle">Studienummer: <?php echo($person->studienr); ?> </tekst><br>
    <text class="subtitle">Total points:<?php echo($person->point); ?> </tekst><br><br>
    <?php
    if ($result != False){
      if ($result->num_rows > 0)  {
          // output data of each row
          echo("<table>
          <tr>
          <th>Point ID</th>
          <th>Aktivitet</th>
          <th>Point</th>
          <th>Kommentar</th>
          <th>Dato</th>
          </tr>");
          while($row = $result->fetch_assoc()) {
              echo("<tr> <th>id: " . $row["point_id"]. "</th><th> " . $row["aktivitet"]. "</th><th>"
              . $row["point"] . "</th><th>" . $row["kommentar"]. "</th><th>" . $row["dato"] . "</th></tr>");
          }
      } else {
          echo ("<br><br><th>0 results</th><br>");
      }
      echo("</table>");
      mysqli_close($db);
      }
    }
?>
<div>



