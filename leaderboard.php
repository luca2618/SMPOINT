<head>
        <link rel="stylesheet" href="./style/Stylesheet.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script> <!-- Tilføjer javascript-library "jqeury" -->
        <script src="https://requirejs.org/docs/release/2.3.5/minified/require.js" defer></script>
</head>
<?php

include("./navbar/Navbar.php"); // Indkluderer navbar.
include("user_class.php");
include("db_connect.php");
echo("<br><br>");

$sqli = "SELECT * FROM users ORDER BY `point` DESC";
$result = mysqli_query($db, $sqli);

console_log($result);

if ($result != False){
    $ranknr = 0;
    if ($result->num_rows > 0)  {
        // output data of each row
        echo("<table>
        <tr>
        <th>Rank</th>
        <th>Navn</th>
        <th>Studienr</th>
        <th>Point</th>
        </tr>");
        while($row = $result->fetch_assoc()) {
            $ranknr += 1;
            echo("<tr> <th> " . $ranknr. "</th><th>" . $row["navn"]. "</th><th>"
            . $row["studienr"] . "</th><th>" . $row["point"]. "</th></tr>");
        }
    } else {
        echo ("<th>0 results</th>");
    }
    echo("</table>");
    mysqli_close($db);
    }




?>