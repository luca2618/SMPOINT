
<!DOCTYPE html>
<html>
<head>
        <link rel="stylesheet" href="./style/Stylesheet.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Search</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script> <!-- Tilføjer javascript-library "jqeury" -->
</head>

<body>
<?php 
//t
include("./navbar/Navbar.php"); // Indkluderer navbar.
include("./config/db_connect.php"); // Forbinder til databasen.
include("user_class.php");
//variables for meeting form dates and stuff
$today = date('Y-m-d');








?>
<br> <br>
<div class="row">
    <!--Form for searching meetings-->
    <div style="" class="column">
        <form action="" method="get" class="form">
            <text class="title">Søg møde</text>
            <br>
            <div class="input-container ic1">
            <input type="date" id="dato" name="dato" required class="input" placeholder=" " 
            <?php echo("value=\"$today\"
                min=\"2000-12-31\" max=\"2025-12-31\"");?>><br><br>
            <div class="cut"></div>
            <label for="dato" class="placeholder">Dato:</label>
            </div>

            <input type="submit" value="Søg møde" name="submit" class="submit">

        </form>
        <br><br>
        <div class="bluebox">
            <?php
            $mødeliste_sqli = "SELECT * FROM `raadsmode` ORDER BY `dato` DESC";
            console_log($mødeliste_sqli);
            $mødeliste_result = mysqli_query($db, $mødeliste_sqli);
            echo("<text class=\"title\">Møder</text>");
            if ($mødeliste_result != null)  {
            
            // output data of each row
            echo("
            <table>
            <tr>
            <th>Møde id</th>
            <th>Dato</th>
            <th>Opretter</th>
            <th>Kommentar</th>
            </tr>");
            foreach ($mødeliste_result as $row) {
                $mode_id = $row["mode_id"];
                $dato = $row["dato"];
                $kommentar = "";
                $opretter = $row["opretter"];

                echo("<tr> <th>". $mode_id
                . "</th><th> ". "<a class=\"link\"  href=\"./meetings?dato=". " $dato"."&submit=Søg+møde\">" . $dato."</a>" . "</th><th>"
                . $opretter . "</th><th>" . $kommentar. "</th></tr>");
            }
            } else {
                echo ("<br><br><th>0 results</th><br>");
            }
            echo("</table>");
            ?>
        </div>
    </div>

    

        <div style="" class="column">
        <?php
            if (isset($_GET['submit'])){
            switch ($_GET['submit']) {
            case "Søg møde":
                    echo("<div class=\"bluebox\">");
                    $møde_dato = $_GET['dato'];
                    $deltagerliste_sqli = "SELECT * FROM `aktiviteter` WHERE (`aktivitet` = 'Studierådsmøde' and `dato` = '$møde_dato') " ;
                    $deltagerliste_result = mysqli_query($db, $deltagerliste_sqli);
                    if ($deltagerliste_result != null)  {
                    echo("<text class=\"title\">"."Deltagerliste for den " . $møde_dato."</text><br><br>");
                    // output data of each row
                    echo("
                    <table>
                    <tr>
                    <th>Studienr</th>
                    <th>Navn</th>
                    <th>Konstitueret</th>
                    <th>Kommentar</th>
                    </tr>");
                    foreach ($deltagerliste_result as $row) {
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

                    echo("</table></div>");
                        break;
                }
                    
            
                }
                ?>
            </div>
            </div>
</div>
<br>
<div class="row">
    <div style="" class="column">
        
        
    </div>
</div>
<br>

        </div>
</body>
</html>  



