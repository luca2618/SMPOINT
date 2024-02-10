<!DOCTYPE html>
<html>
<title>Manage</title>

<?php
if (isset($_SESSION['role']) && ($_SESSION['role']>1)){

include("header.php");
include("./navbar/Navbar.php"); // Indkluderer navbar.
include("./config/db_connect.php");
include("user_class.php");
update_all();

$delete_message = "";
$tilføj_kostitueret_message = "";
$møde_message = "";
$opdater_værdi_message = "";
$fremmødte_message = "";

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
            
            if (add_konstiueret($studienr, $navn, $email)){
                $tilføj_kostitueret_message = "Succes";
            }else{
                $tilføj_kostitueret_message = "Allerede registeret studienr";
            }


            break;
        case "Delete point":
            $studienr = $_POST['studienr'];
            $pointid = $_POST['point_id'];
            if (studienr_exists($studienr)){
            $konstitueret = new bruger($studienr);
                if ($konstitueret->deletepoint($pointid)){
                    $delete_message = "succes";
                }else{
                    $delete_message = "id eller studienr ikke fundet";
                }
            }else{
                $delete_message = "id eller studienr ikke fundet";
            }
            


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
            
                case "Opdater værdi":
                    $studienr = $_POST['studienr'];
                    $column = $_POST['column'];
                    $new_value = $_POST['new_value'];
                    
                    if (studienr_exists($studienr)){

                        $person = new bruger($studienr);

                        if ($person->update($column,$new_value)){
                            $opdater_værdi_message = "Succes";
                        }else{
                            $opdater_værdi_message = "Error:".mysql_errno($db);
                        }

                    }else{
                        $opdater_værdi_message = "Studienr not found";
                    }

                break;

                case "Register fremmødte":
                    $dato = $_POST['dato'];
                    $dato = str_replace("-","/",$dato);

                    console_log($_POST);
                    $studie_numre = $_POST['studie_numre'];
                    $studie_numre = explode(",", $studie_numre);
                    $error_count = 0;

                    foreach ($studie_numre as $studie_nr){
                        $studie_nr = trim($studie_nr);
                        if (studienr_exists($studie_nr)){
                            $bruger = new bruger($studie_nr);
                            $bruger->fremmødt($dato=$dato);
                        } else {
                            $fremmødte_message = $fremmødte_message."<br>".$studie_nr;
                            $error_count = 0;
                        }
                    }

                    $fremmødte_message = "Error on:". $fremmødte_message ;
                        if ($error_count == 0){
                            $fremmødte_message = "Succes on all";
                        }
                break;

                case "Update legacy date":
                    $dato = $_POST['dato'];
                    #$dato = str_replace("-","/",$dato);
                    console_log($dato);
                    console_log(update_legacy_date($dato));
                break;
    }
}
?>


<br><br>
<!--Form for deleting an aktivity that a konstitueret has performed/ a point they have earned-->
<div class="row">
<div style="" class="column">
    <form action="" method="post" class="form" onsubmit="return confirm('Are you sure you want to submit?')">
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
       min=\"2000-01-01\" max=\"2025-12-31\"");?>><br><br>
    <div class="cut"></div>
    <label for="dato" class="placeholder">Dato:</label>
    </div>

    <div class="input-container ic1">
    <input type="text" id="kode" name="kode" required autofill="off" class="input" placeholder=" " autocomplete="off">
    <div class="cut"></div>
    <label for="points" class="placeholder">Kode:</label>
    </div>

    <input type="submit" value="Opret møde" name="submit" class="submit">

    </form>
</div>

<!--Form for manually adding a manglende konstitueret-->
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

    <input type="submit" value="Tilføj konstitueret" name="submit" class="submit">
    </form>
</div>

</div>

<div class="row">

<div style="" class="column">
    <form action="" method="post" class="form">
    <text class="title">Opdater medlems værdier</text>
    <br>
    <text class="subtitle"><?php echo($opdater_værdi_message);?></text>

    <div class="input-container ic1">
    <input type="text" id="studienr" name="studienr" required class="input" placeholder=" "><br><br>
    <div class="cut"></div>
    <label for="studienr" class="placeholder">Studienr:</label>
    </div>
    <br>
    <label for="column" class="subtitle">Column:</label><br>
    <select id="column" name="column">
        <option value="studienr">Studienr</option>
        <option value="navn">Navn</option>
        <option value="email">Email</option>
        <option value="point">Point</option>
    </select>
    <div class="input-container ic1">
    <input type="text" id="new_value" name="new_value" required class="input" placeholder=" " autocomplete="off"><br><br>
    <div class="cut"></div>
    <label for="new_value" class="placeholder">Ny værdi:</label>
    </div>

    <input type="submit" value="Opdater værdi" name="submit" class="submit">
    </form>
</div>

<div style="" class="column">
    <form action="" method="post" class="form">
        <text class="title">Register fremmødte</text>
        <br>
        <text class="subtitle">Studie nr. comma separeret</text><br>
        <text class="subtitle"><?php echo($fremmødte_message);?></text>

        <div class="input-container ic1">
        <input type="date" id="dato" name="dato" required class="input" placeholder=" " 
        <?php echo("value=\"$today\"
        min=\"2000-01-01\" max=\"2025-12-31\"");?>><br><br>
        <div class="cut"></div>
        <label for="dato" class="placeholder">Dato:</label>
        </div>

        <div class="input-container ic1">
        <input type="text" id="studie_numre" name="studie_numre" required autofill="off" class="input" placeholder=" " autocomplete="off">
        <div class="cut"></div>
        <label for="points" class="placeholder">studie numre:</label>
        </div>

        <input type="submit" value="Register fremmødte" name="submit" class="submit">

    </form>
</div>

<div style="" class="column">
    <form action="" method="post" class="form">
        <text class="title">Opdater legacy dato</text>
        <br>
        <text class="subtitle">Opdaterer datoen hvorfra point tælles</text><br>
        <text class="subtitle"><?php echo($fremmødte_message);?></text>

        <div class="input-container ic1">
        <input type="date" id="dato" name="dato" required class="input" placeholder=" " 
        <?php echo("value=\"$today\"
        min=\"2000-01-01\" max=\"2025-12-31\"");?>><br><br>
        <div class="cut"></div>
        <label for="dato" class="placeholder">Dato:</label>
        </div>

        <input type="submit" value="Update legacy date" name="submit" class="submit">

    </form>
</div>

</div>


<?php
}
?>