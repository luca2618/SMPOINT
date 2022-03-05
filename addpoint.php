<head>
        <link rel="stylesheet" href="./style/Stylesheet.css">
        <link rel="icon" type="image/x-icon" href="favicon.ico"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add point</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script> <!-- Tilføjer javascript-library "jqeury" -->
        <script src="https://requirejs.org/docs/release/2.3.5/minified/require.js" defer></script>
</head>
<?php

include("./navbar/Navbar.php"); // Indkluderer navbar.
include("user_class.php");
include("./config/db_connect.php"); // Forbinder til databasen.
$sqli = "SELECT * FROM `aktivitet_typer` ORDER BY `type_id` ASC";
$result = mysqli_query($db, $sqli);

while ($row = mysqli_fetch_assoc($result)) {
    $aktivitets_typer[] = $row; // Inside while loop
 }


if ((isset($_SESSION['role']) && $_SESSION['role']>1)){
?>

<br> </br>
<script>
            // Funktion der ændrer layoutet på siden, hver gang man ændrer aktiviteten.
            function changeform(){
                var aktivitet = document.getElementById('aktivitet').value;
                var aktivitets_typer = <?php echo json_encode($aktivitets_typer); ?>;
                var prefill_elements = document.getElementsByClassName("prefill");
                var hide = false;
                
                for (i in aktivitets_typer){
                    //console.log(aktivitet_values_array[aktivitet_index]["Aktivitet"]);
                    if (aktivitet === aktivitets_typer[i]['Aktivitet']) {
                        hide =true;
                        document.getElementById("points").value = aktivitets_typer[i]['Point'];
                        document.getElementById("kommentar").value = aktivitets_typer[i]['Forklaring'];
                    }

                }
                //This if statement hides alle the prefilled
                /*
                if (hide != true){
                        for (i = 0; i < prefill_elements.length; i++) {
                            prefill_elements[i].style.display = "inline";
                        }
                    }
                    else 
                    {
                        for (i = 0; i < prefill_elements.length; i++) {
                            prefill_elements[i].style.display = "none";
                        }
                    }*/
            }
            </script>



<form action="" method="post" class="form">
<text class="title">Add points</text>
<div class="input-container ic1">
  <input type="text" id="studienr" name="studienr" required class="input" placeholder=" "><br><br>
  <div class="cut"></div>
  <label for="studienr" class="placeholder">Studienr:</label>
</div>

<div class="input-container ic1">
  
  <input list="aktivitets_list" id="aktivitet" name="aktivitet" required autocomplete="off" onkeyup="changeform();" class="input" placeholder=" "><br><br>
  <div class="cut"></div>
  <datalist id="aktivitets_list">
  <?php
  foreach ($aktivitets_typer as $value){
    echo("<option value=\"");
    echo($value['Aktivitet']);
    echo("\">");
  }
  ?>
  </datalist>

  <label for="aktivitet" class="placeholder">Aktivitet:</label>
</div>

<div class="input-container ic1">
    <input type="text" id="kommentar" name="kommentar" class="prefill input" placeholder=" "  autocomplete="off">
    <div class="cut"></div>
    <label for="kommentar" class="prefill placeholder">Kommentar:</label>
</div>

<div class="input-container ic1">
  <input type="text" id="points" name="points" required autocomplete="off" autofill="off" class="prefill input" placeholder=" " autocomplete="off"><br class="prefill"><br class="prefill">
  <div class="cut"></div>
  <label for="points" class="prefill placeholder">Points:</label>
</div>
  <input type="submit" value="Submit" name="submit" class="submit">
</form>

<?php 
}
?>



<?php
//inputs
// password (acces password for adding points) (pass is 1234 now)
// aktivitet (aktivitet deltaget i)
// is used of studie nr is not set card_id - used to identify student/studentnr
include("./config/db_connect.php");
// the && only runs first check if its false therefore no problem with $_SESSION['role'] as its only reffered to if $_SESSION['role'] exists.
if ((isset($_POST['password']) &&  (strcmp(hash('md5',$_POST['password']),"81dc9bdb52d04dc20036dbd8313ed055") == 0)) ||
(isset($_SESSION['role']) && ($_SESSION['role']>1) && isset($_POST['submit']))){
    if (isset($_POST['studienr'])){
        $studienr = $_POST['studienr'];
    }elseif(isset($_POST['card_id'])){
        $card_id = $_POST['card_id'];
        $sqli = "SELECT studienr FROM `card_data` WHERE card_id=('$card_id')";
        $studienr = mysqli_query($db, $sqli)->fetch_object()->studienr;
    }
    $aktivitet = $_POST['aktivitet'];
    $points = $_POST['points'];
    $kommentar = $_POST['kommentar'];
    $dato = date('Y-m-d');

    $user = new bruger($studienr);
    $points = trim($points);
    if (! (is_numeric($points))){
        exit("Error:non integer point value!");
    }

    //checker om brugeren er fremmødt til studierådsmøde (er lig med 0 hvis str er ens)
    if (strcasecmp($aktivitet, "studierådsmøde") == 0){
        $user->fremmødt();
    }else{
        $user->addpoint($points, $aktivitet, $kommentar, $dato);
        }

}

?>