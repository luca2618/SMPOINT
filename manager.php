<!DOCTYPE html>
<html>
<title>Manage</title>

<?php
if (isset($_SESSION['role']) && ($_SESSION['role']>1)){

include("header.php");
include("./navbar/Navbar.php"); // Indkluderer navbar.
include("./config/db_connect.php");
include("user_class.php");


$delete_message = "";
$tilføj_kostitueret_message = "";
$tilføj_kostitueret_liste_message = "";
$tilføj_aktivitet_liste_message = "";
$tilføj_møde_liste_message = "";
$møde_message = "";

//variables for meeting form dates and stuff
$today = date('Y-m-d');

?>

<?php
if (isset($_SESSION['role']) && ($_SESSION['role']>1) && isset($_POST['submit'])){
    //switch statement to handle the post data, depending on which form you filled. 
    //the value is determined by the submit value of each form and determined from that,
    switch ($_POST['submit']) {
        case "Tilføj konstitueret":
            //Setup variables
            $studienr = $_POST['studienr'];
            $navn = $_POST['navn'];
            $email = $_POST['email'];
            $telefonnr = $_POST['telefonnr'];
            
            if (add_konstiueret($studienr, $navn, $email, $telefonnr)){
                $tilføj_kostitueret_message = "Succes";
            }else{
                $tilføj_kostitueret_message = "Allerede registeret studienr";
            }


            break;
        case "Delete point":
            $studienr = $_POST['studienr'];
            $pointid = $_POST['point_id'];
            $konstitueret = new bruger($studienr);
            if ($konstitueret->deletepoint($pointid)){
                $delete_message = "succes";
            }else{
                $delete_message = "id eller studienr ikke fundet";
            }


            break;
        case "Tilføj konstitueret liste":
            $succes = 0;
            $entries = 0;
            $tmpName = $_FILES['k_liste']['tmp_name'];
            $csvAsArray = array_map('str_getcsv', file($tmpName));
            //konstitueringslisterne starter på række 6 og indeholder i rækkefølge:
            //'Fulde Navn', 'Studienr.', 'E-mail', 'Telefonnummer prefix(landkode)', 'Telefonnummer'
            for ($row = 5; $row<sizeof($csvAsArray); $row++){
                $navn = $csvAsArray[$row][0];
                $studienr = $csvAsArray[$row][1];
                $email = $csvAsArray[$row][2];
                $telefonnr = $csvAsArray[$row][3].$csvAsArray[$row][4];
                if (trim($navn) != ""){
                    if(add_konstiueret($studienr, $navn, $email, $telefonnr)){
                        $succes++;
                        console_log($navn);
                    }
                    $entries++;
                }
            }
            $tilføj_kostitueret_liste_message = "Succes på ".$succes." ud af ".$entries.", allerede konstituerede medlemmer tæller ikke som succes";
            break;

            case "Tilføj aktivitetsliste":
                $succes = 0;
                $entries = 0;
                $tmpName = $_FILES['a_liste']['tmp_name'];
                $csvAsArray = array_map('str_getcsv', file($tmpName));
                //konstitueringslisterne starter på række 2 og indeholder i rækkefølge:
                //'Fulde Navn', 'Studienr.', 'aktivitet', 'dato', 'kommentar', 'points'
                for ($row = 1; $row<sizeof($csvAsArray); $row++){
                    $navn = $csvAsArray[$row][0];
                    $studienr = $csvAsArray[$row][1];
                    $aktivitet = $csvAsArray[$row][2];
                    $dato = $csvAsArray[$row][3];
                    $kommentar = $csvAsArray[$row][4];
                    $point = $csvAsArray[$row][5];
                    if (trim($navn) != ""){
                        if(studienr_exists($studienr)){
                            $konstitueret = new bruger($studienr);
                            $konstitueret->addpoint_no_date($point, $aktivitet, $kommentar, $dato);
                            $succes++;
                        }
                        $entries++;
                    }
                }
                $tilføj_aktivitet_liste_message = "Succes på ".$succes." ud af ".$entries;
                break;
        
                case "Tilføj mødeliste":
                    $succes = 0;
                    $entries = 0;
                    $tmpName = $_FILES['møde_liste']['tmp_name'];
                    $csvAsArray = array_map('str_getcsv', file($tmpName));
                    //konstitueringslisterne starter på række 2 og indeholder i rækkefølge:
                    //'dato', 'Navn', 'Studienr.', 'retning', 'konstitueret(Ja/Nej)', 'random spørgsmål'
                    for ($row = 1; $row<sizeof($csvAsArray); $row++){
                        $dato = $csvAsArray[$row][0];
                        //$date format from the google forms include exazt time so we just cut the date out
                        $dato = substr($dato, 0, -9);
                        $navn = $csvAsArray[$row][1];
                        $studienr = $csvAsArray[$row][2];
                        $medlem =  $csvAsArray[$row][4];
                        $aktivitet = "Studierådsmøde";
                        $kommentar = "Fremmødt";
                        $point = 1;
                        //check om personen siger de er medlem af rådet
                        if ((trim($navn) != "")&&(strcasecmp($medlem,"Ja") == 0)){
                            if(studienr_exists($studienr)){
                                console_log($navn);
                                $konstitueret = new bruger($studienr);
                                $konstitueret->addpoint_no_date($point, $aktivitet, $kommentar, $dato);
                                $succes++;
                            }
                            $entries++;
                            }
                    }
                    $tilføj_møde_liste_message = "Succes på ".$succes." ud af ".$entries;
                    break;

                    case "Opret møde":
                        $dato = $_POST['dato'];
                        $dato = str_replace("-","/",$dato);
                        $kode = $_POST['kode'];
                        $opretter = $_SESSION['name'];
                        
                        //check if meeting already exists on that day
                        $sqlcheck = "SELECT * FROM `raadsmode` WHERE `dato` = '$dato'";
                        $checkresult = mysqli_query($db, $sqlcheck);
                        //get result array
                        $checkresult = $checkresult->fetch_assoc();
                        if ($checkresult == null){
                            //setup sql query
                            $sql = "INSERT INTO `raadsmode`( `dato`, `kode`, `opretter`) VALUES (
                            '$dato',
                            '$kode',
                            '$opretter'
                        )";
                        }else{
                            $sql = "UPDATE `raadsmode` SET `kode` = '$kode' WHERE `dato` = '$dato'";
                        }

                        $result = mysqli_query($db, $sql);
                        // check if the person already exists
                        if ($result){
                            $møde_message = "Succes";
                        }else{
                            $møde_message = "error:".mysqli_error($db);
                        }

                        break;
    }
}
?>


<br><br>
<!--Form for deleting an aktivity that a konstitueret has performed/ a point they have earned-->
<div class="row">
<div style="" class="column">
    <form action="" method="post" class="form">
    <text class="title">Delete points</text>
    <br>
    <text class="subtitle"><?php echo($delete_message);?></text>

    <div class="input-container ic1">
    <input type="text" id="studienr" name="studienr" required class="input" placeholder=" "><br><br>
    <div class="cut"></div>
    <label for="studienr" class="placeholder">Studienr:</label>
    </div>

    <div class="input-container ic1">
    <input type="text" id="point_id" name="point_id" required autocomplete="off" autofill="off" class="input" placeholder=" " autocomplete="off">
    <div class="cut"></div>
    <label for="points" class="placeholder">Point id:</label>
    </div>

    <input type="submit" value="Delete point" name="submit" class="submit">

    </form>
</div>
<!--Form for adding a meeting-->
<div style="" class="column">
<form action="" method="post" class="form">
    <text class="title">Tilføj møde</text>
    <br>
    <text class="subtitle">Hvis møde allerede existere, opdateres koden</text><br>
    <text class="subtitle"><?php echo($møde_message);?></text>

    <div class="input-container ic1">
    <input type="date" id="dato" name="dato" required class="input" placeholder=" " 
    <?php echo("value=\"$today\"
       min=\"$today\" max=\"2025-12-31\"");?>><br><br>
    <div class="cut"></div>
    <label for="dato" class="placeholder">Dato:</label>
    </div>

    <div class="input-container ic1">
    <input type="text" id="kode" name="kode" required autocomplete="off" autofill="off" class="input" placeholder=" " autocomplete="off">
    <div class="cut"></div>
    <label for="points" class="placeholder">Kode:</label>
    </div>

    <input type="submit" value="Opret møde" name="submit" class="submit">

    </form>
</div>
<!--Form for importing a "konstituerings liste" into the database(from row 6 and beyond)-->
<div style="" class="column">
    <form action="" method="post" class="form" enctype="multipart/form-data">
    <text class="title">Import konstitueret liste</text>
    <br>
    <text class="formtekst">
    Tager udgangspunkt i formattering af listerne på drev.<br>
    konstitueringslisterne starter på række 6 og indeholder i rækkefølge:<br>
    'Fulde Navn', 'Studienr.', 'E-mail', 'Telefonnummer prefix(landkode)', 'Telefonnummer'<br>
    </text>
    <text class="subtitle">
    <?php echo($tilføj_kostitueret_liste_message);?></text>

    <div class="input-container ic1">
    <label for="k_liste" class="subtitle">Konstitueringsliste:</label>
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    <input type="file" style="color:white" accept=".csv" id="k_liste" name="k_liste" required autocomplete="off" autofill="off" placeholder=" " autocomplete="off">
    </div>

    <input type="submit" value="Tilføj konstitueret liste" name="submit" class="submit">

    </form>
</div>

</div>

<br><br>
<!--Form for import aktivities that members have performed, into the database-->
<div class="row">
<div style="" class="column">
    <form action="" method="post" class="form" enctype="multipart/form-data">
    <text class="title">Import aktivitetsliste</text>
    <br>
    <text class="formtekst">
    Tager udgangspunkt i formattering af listerne på drev.<br>
    konstitueringslisterne starter på række 2 og indeholder i rækkefølge:<br>
    'Navn', 'Studienr.', 'aktivitet', 'dato', 'kommentar', 'points'<br>
    Navn bruges ikke.</text>
    <text class="subtitle">
    <?php echo($tilføj_aktivitet_liste_message);?></text>

    <div class="input-container ic1">
    <label for="a_liste" class="subtitle">Aktivitetsliste:</label>
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    <input type="file" style="color:white" accept=".csv" id="a_liste" name="a_liste" required autocomplete="off" autofill="off" placeholder=" " autocomplete="off">
    </div>
    <input type="submit" value="Tilføj aktivitetsliste" name="submit" class="submit">
    </form>
</div>
<!--Form for importing list of attending members into the database-->
<div style="" class="column">
    <form action="" method="post" class="form" enctype="multipart/form-data">
    <text class="title">Import mødeliste</text>
    <br>
    <text class="formtekst">
    Tager udgangspunkt i formattering af listerne på drev.<br>
    mødelisterne starter på række 2 og indeholder i rækkefølge:<br>
    'dato', 'Navn', 'Studienr.', 'retning', 'konstitueret(Ja/Nej)', 'random spørgsmål'<br>
    Navn bruges ikke.</text>
    <text class="subtitle">
    <?php echo($tilføj_møde_liste_message);?></text>

    <div class="input-container ic1">
    <label for="a_liste" class="subtitle">Mødeliste:</label>
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    <input type="file" style="color:white" accept=".csv" id="møde_liste" name="møde_liste" required autocomplete="off" autofill="off" placeholder=" " autocomplete="off">
    </div>

    <input type="submit" value="Tilføj mødeliste" name="submit" class="submit">

    </form>
</div>
<!--Form for manually adding a missing konstitueret-->
<div style="" class="column">
    <form action="" method="post" class="form">
    <text class="title">Tilføj konstitueret</text>
    <br>
    <text class="subtitle"><?php echo($tilføj_kostitueret_message);?></text>

    <div class="input-container ic1">
    <input type="text" id="studienr" name="studienr" required class="input" placeholder=" " autocomplete="off"><br><br>
    <div class="cut"></div>
    <label for="studienr" class="placeholder">Studienr:</label>
    </div>

    <div class="input-container ic1">
    <input type="text" id="navn" name="navn" required autocomplete="off" autofill="off" class="input" placeholder=" " autocomplete="off">
    <div class="cut"></div>
    <label for="navn" class="placeholder">Navn:</label>
    </div>

    <div class="input-container ic1">
    <input type="text" id="email" name="email" required autocomplete="off" autofill="off" class="input" placeholder=" ">
    <div class="cut"></div>
    <label for="email" class="placeholder">Email:</label>
    </div>

    <div class="input-container ic1">
    <input type="text" id="telefonnr" name="telefonnr" required autocomplete="off" autofill="off" class="input" placeholder=" ">
    <div class="cut"></div>
    <label for="telefonnr" class="placeholder">Telefon nr:</label>
    </div>

    <input type="submit" value="Tilføj konstitueret" name="submit" class="submit">
    </form>


</div>

</div>


<?php
}
?>