

<head>
        <link rel="stylesheet" href="./style/Stylesheet.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script> <!-- Tilføjer javascript-library "jqeury" -->
</head>


<?php 
include("./navbar/Navbar.php"); // Indkluderer navbar.

?>
<br> </br>
<form action="" method="post">
  <label for="fname">Studienr:</label>
  <input type="text" id="studienr" name="studienr"><br><br>
  <input type="submit" value="Search">
  </form>

<?php


if (isset($_POST['studienr'])){
    $studienr = $_POST['studienr'];
    include("user_class.php");
    include("db_connect.php"); // Forbinder til databasen.

    $person = new bruger($studienr);
    $total = $person->point;

    #get all aktivities by id ascending order

    $sqli = "SELECT * FROM `$studienr` ORDER BY `point_id` DESC";
    $result = mysqli_query($db, $sqli);

    

    echo("Total points:");
    echo($total);

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
          echo ("<th>0 results</th>");
      }
      echo("</table>");
      mysqli_close($db);
      }
    }
?>



