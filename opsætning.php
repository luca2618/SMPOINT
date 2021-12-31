<!DOCTYPE html>
<html>
<title>Opsætning</title>

<?php
if (isset($_SESSION['role']) && ($_SESSION['role']>1)){

include("header.php");
include("./navbar/Navbar.php"); // Indkluderer navbar.
include("./config/db_connect.php");
include("user_class.php");


$tilføj_kostitueret_message = "";
$tilføj_kostitueret_liste_message = "";
$tilføj_aktivitet_liste_message = "";
$tilføj_møde_liste_message = "";
$tilføj_aktivitet_option_message = "";

?>

<?php
console_log($_FILES);
if (isset($_SESSION['role']) && ($_SESSION['role']>1) && isset($_POST['submit'])){
    //switch statement to handle the post data, depending on which form you filled. 
    //the value is determined by the submit value of each form and determined from that,
    switch ($_POST['submit']) {

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
                    //reformat to yyyy-mm-dd
                    $dato = str_replace('/', '-', $dato);
                    $dato = date ('Y-m-d', strtotime($dato));

                    $kommentar = $csvAsArray[$row][4];
                    $point = $csvAsArray[$row][5];
                    if (trim($navn) != ""){
                        if(studienr_exists($studienr)){
                            $konstitueret = new bruger($studienr);
                            $konstitueret->addpoint($point, $aktivitet, $kommentar, $dato);
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
                        
                        //$date format from the google forms include exact time so we just cut the date out
                        $dato = substr($dato, 0, -9);
                        //reformat to yyyy-mm-dd
                        $dato = str_replace('/', '-', $dato);
                        $dato = date ('Y-m-d', strtotime($dato));
                        $navn = $csvAsArray[$row][1];
                        $studienr = $csvAsArray[$row][2];
                        $medlem =  $csvAsArray[$row][4];
                        $aktivitet = "Studierådsmøde";
                        $kommentar = "Fremmødt";
                        $point = 1;
                        //check om personen siger de er medlem af rådet
                        if ((trim($navn) != "")&&(strcasecmp($medlem,"Ja") == 0)){
                            if(studienr_exists($studienr)){
                                $konstitueret = new bruger($studienr);
                                $konstitueret->addpoint($point, $aktivitet, $kommentar, $dato);
                                $succes++;
                            }
                            $entries++;
                            }
                    }
                    $tilføj_møde_liste_message = "Succes på ".$succes." ud af ".$entries;
                    break;

                    
                        case "Tilføj aktivitetstype liste":
                            $succes = 0;
                            $entries = 0;
                            $tmpName = $_FILES['a_type_liste']['tmp_name'];
                            $csvAsArray = array_map('str_getcsv', file($tmpName));
                            //konstitueringslisterne starter på række 2 og indeholder i rækkefølge:
                            //'Fulde Navn', 'Studienr.', 'aktivitet', 'dato', 'kommentar', 'points'
                            for ($row = 1; $row<sizeof($csvAsArray); $row++){
                                $aktivitet = $csvAsArray[$row][0];
                                $point = $csvAsArray[$row][1];
                                $forklaring = $csvAsArray[$row][2];
                                $sqlicheck = "SELECT * FROM `aktivitet_typer` WHERE `Aktivitet` = '$aktivitet'; ";
                                $result = mysqli_query($db, $sqlicheck);
                                if (trim($aktivitet) != ""){
                                    if(add_aktivitet_type($aktivitet,$point,$forklaring)){
                                    $succes++;
                                    }
                                    $entries++;
                                }
                            }
                            $tilføj_aktivitet_option_message = "Succes på ".$succes." ud af ".$entries;


                        break;

                        

    }
}
?>


<br><br>
<!--Form for deleting an aktivity that a konstitueret has performed/ a point they have earned-->
<div class="row">
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
    <input type="file" style="color:white" accept=".csv" id="k_liste" name="k_liste" required  placeholder=" ">
    </div>

    <input type="submit" value="Tilføj konstitueret liste" name="submit" class="submit">

    </form>
</div>



<br><br>
<!--Form for import aktivities that members have performed, into the database-->
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
    <label for="møde_liste" class="subtitle">Mødeliste:</label>
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    <input type="file" style="color:white" accept=".csv" id="møde_liste" name="møde_liste" required autocomplete="off" autofill="off" placeholder=" " autocomplete="off">
    </div>

    <input type="submit" value="Tilføj mødeliste" name="submit" class="submit">

    </form>
</div>

</div>
<br><br>
<div class="row">
<!--Form for -->
<div style="" class="column">

</div>


<!--Form for importing list of predefined "aktiviteter"-->
<div style="" class="column">
    <form action="" method="post" class="form" enctype="multipart/form-data">
    <text class="title">Import aktivitetstype liste</text><br>
    <text class="subtitle">Læser fra række 1 og frem, ikke succes på allerede existrerende aktivitetstyper</text><br>
    <text class="subtitle"><?php echo($tilføj_aktivitet_option_message);?></text>

    <div class="input-container ic1">
    <label for="a_type_liste" class="subtitle">Konstitueringsliste:</label>
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    <input type="file" style="color:white" accept=".csv" id="a_type_liste" name="a_type_liste" required  placeholder=" ">
    </div>
    
    <input type="submit" value="Tilføj aktivitetstype liste" name="submit" class="submit">
    </form>
</div>

<!--Form for -->
<div style="" class="column">

</div>


</div>

<?php
}
?>