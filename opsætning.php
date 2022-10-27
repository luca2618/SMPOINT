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
            $csvAsArray = csv_to_array($tmpName);
            //konstitueringslisterne starter på række 1 og indeholder i rækkefølge:
            //'Fulde Navn', 'Studienr.', 'E-mail', 'Telefonnummer prefix(landkode)', 'Telefonnummer'
            for ($row = 1; $row<sizeof($csvAsArray); $row++){
                $navn = $csvAsArray[$row][0];
                $studienr = $csvAsArray[$row][1];
                $email = $csvAsArray[$row][2];
                if (trim($navn) != ""){
                    if(add_konstiueret($studienr, $navn, $email)){
                        $succes++;
                    }
                    $entries++;
                }
            }
            console_log($csvAsArray);
            $tilføj_kostitueret_liste_message = "Succes på ".$succes." ud af ".$entries.", allerede konstituerede medlemmer tæller ikke som succes";
            break;

            case "Tilføj aktivitetsliste":
                $succes = 0;
                $entries = 0;
                $tmpName = $_FILES['a_liste']['tmp_name'];
                $csvAsArray = csv_to_array($tmpName);
                //Aktivitetslisterne starter på række 2 og indeholder i rækkefølge:
                //'Studienr.', 'aktivitet', 'dato', 'kommentar', 'points'
                for ($row = 1; $row<sizeof($csvAsArray); $row++){
                    $studienr = $csvAsArray[$row][0];
                    $aktivitet = $csvAsArray[$row][1];
                    $dato = $csvAsArray[$row][2];
                    //reformat to yyyy-mm-dd, should just be input with "-"
                    $dato = str_replace('/', '-', $dato);
                    $dato = date ('Y-m-d', strtotime($dato));

                    $kommentar = $csvAsArray[$row][3];
                    $point = $csvAsArray[$row][4];
                    
                    if(studienr_exists($studienr)){
                        $konstitueret = new bruger($studienr);
                        $konstitueret->addpoint($point, $aktivitet, $kommentar, $dato);
                        $succes++;
                    }
                    $entries++;
                    
                }
                $tilføj_aktivitet_liste_message = "Succes på ".$succes." ud af ".$entries;
                break;
        
                case "Tilføj mødeliste":
                    $succes = 0;
                    $entries = 0;
                    $tmpName = $_FILES['møde_liste']['tmp_name'];
                    $csvAsArray = csv_to_array($tmpName);
                    //konstitueringslisterne starter på række 2 og indeholder i rækkefølge:
                    //'Studienr.', 'dato'
                    for ($row = 1; $row<sizeof($csvAsArray); $row++){
                        $studienr = $csvAsArray[$row][0];
                        $dato = $csvAsArray[$row][1];
                        
                        //$date format from the google forms include exact time so we just cut the date out
                        $dato = substr($dato, 0, -9);
                        //reformat to yyyy-mm-dd
                        $dato = str_replace('/', '-', $dato);
                        $dato = date ('Y-m-d', strtotime($dato));
                        //check om personen siger de er medlem af rådet
                        if(studienr_exists($studienr)){
                            $konstitueret = new bruger($studienr);
                            $konstitueret->fremmødt($dato=$dato);
                            $succes++;
                        }
                        $entries++;
                        }
                    
                    $tilføj_møde_liste_message = "Succes på ".$succes." ud af ".$entries;
                    break;

                    
                    case "Tilføj aktivitetstype liste":
                        $succes = 0;
                        $entries = 0;
                        $tmpName = $_FILES['a_type_liste']['tmp_name'];
                        $csvAsArray = csv_to_array($tmpName);
                        for ($row = 1; $row<sizeof($csvAsArray); $row++){
                            $aktivitet = $csvAsArray[$row][0];
                            //skifter fra komma seperation for decimaler til punktum.
                            $point = str_replace(',', '.', $csvAsArray[$row][1]);
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

                    case "Tilføj aktivitetstype":
                        if (isset($_POST['aktivitetsnavn'])){
                            $aktivitet = $_POST['aktivitetsnavn'];
                            $point = $_POST['pointvalue'];
                            $forklaring = $_POST['forklaring'];
                            add_aktivitet_type($aktivitet, $point, $forklaring);
                        }
                    break;

                        

    }
}
?>


<br><br>
<!--Form for deleting an aktivity that a konstitueret has performed/ a point they have earned-->
<div class="row">
<!--Form for importing a "konstituerings liste" into the database(from row 6 and beyond)-->
<div style="" class="column">
    <form action="" method="post" class="form" enctype="multipart/form-data"  onsubmit="return confirm('Are you sure you want to submit?');">>
    <text class="title">Import konstitueret liste</text>
    <br>
    <text class="formtekst">
    Tager udgangspunkt i formattering på følgende skabelon:
    <a href="/tilmelding_skabelon" download="tilmelding_skabelon.csv">
    Skabelon - click her
    </a>
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
    <form action="" method="post" class="form" enctype="multipart/form-data"  onsubmit="return confirm('THIS IS A MESS TO FIX IF YOU FUCK UP, ARE YOU SURE?');">>
    <text class="title">Import aktivitetsliste</text>
    <br>
    <text class="formtekst">
    Tager udgangspunkt i formattering på følgende skabelon:
    <a href="/aktivitetsliste_skabelon" download="aktivitetsliste_skabelon.csv">
    Skabelon - click her
    </a>
    </text>
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
    <form action="" method="post" class="form" enctype="multipart/form-data" onsubmit="return confirm('THIS IS A MESS TO FIX IF YOU FUCK UP, ARE YOU SURE?');">
    <text class="title">Import mødeliste</text>
    <br>
    <text class="formtekst">
    Tager udgangspunkt i formattering på følgende skabelon:
    <a href="/modeliste_skabelon" download="mødeliste_skabelon.csv">
    Skabelon - click her
    </a></text>
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
    <form action="" method="post" class="form">
    <text class="title">Tilføj aktivitetstype</text><br>

    <div class="input-container ic1">
    <input type="text" id="aktivitetsnavn" name="aktivitetsnavn" required autocomplete="off" autofill="off" class="input" placeholder=" ">
    <div class="cut"></div>
    <label for="aktivitetsnavn" class="placeholder">Aktivitetsnavn:</label>
    </div>

    <div class="input-container ic1">
    <input type="number" step="0.01" min=0 id="pointvalue" name="pointvalue" required autocomplete="off" autofill="off" class="input" placeholder=" ">
    <div class="cut"></div>
    <label for="pointvalue" class="placeholder">pointvalue:</label>
    </div>

    <div class="input-container ic1">
    <input type="text" id="forklaring" name="forklaring" required autocomplete="off" autofill="off" class="input" placeholder=" ">
    <div class="cut"></div>
    <label for="forklaring" class="placeholder">forklaring:</label>
    </div>
    
    
    <input type="submit" value="Tilføj aktivitetstype" name="submit" class="submit">
    </form>
</div>


<!--Form for importing list of predefined "aktiviteter"-->
<div style="" class="column">
    <form action="" method="post" class="form" enctype="multipart/form-data">
    <text class="title">Import aktivitetstype liste</text><br>
    <text class="formtekst">    Tager udgangspunkt i formattering på følgende skabelon:
    <a href="/aktivitetstype_skabelon" download="aktivitetstype_skabelon.csv">
    Skabelon - click her
    </a></text><br>
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