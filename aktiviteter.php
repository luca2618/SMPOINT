<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>

<form action="" method="post">
  <label for="fname">Studienr:</label>
  <input type="text" id="studienr" name="studienr"><br><br>
  <input type="submit" value="Submit">
  </form>

<?php



if (isset($_POST['studienr'])){
    $studienrS = $_POST['studienr'];
    include("user_class.php");
    include("db_connect.php"); // Forbinder til databasen.

    $sqli = "SELECT * FROM `$studienrS`";
    $result = mysqli_query($db, $sqli);


    if ($result->num_rows > 0) {
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
?>



