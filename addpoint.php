<head>
        <link rel="stylesheet" href="./style/Stylesheet.css">
        <link rel="icon" type="image/x-icon" href="favicon.ico"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script> <!-- Tilføjer javascript-library "jqeury" -->
        <script src="https://requirejs.org/docs/release/2.3.5/minified/require.js" defer></script>
</head>
<?php

include("./navbar/Navbar.php"); // Indkluderer navbar.
include("user_class.php");

$csv = array();
$file = fopen('point_liste.csv', 'r');

while (($result = fgetcsv($file)) !== false)
{
    $csv[] = $result;
}
array_shift($csv);
fclose($file);

?>

<br> </br>
<script>
            // Funktion der ændrer layoutet på siden, hver gang man ændrer aktiviteten.
            function changeform(){
                var aktivitet = document.getElementById('aktivitet').value;
                var csv = <?php echo json_encode($csv); ?>;
                var prefill_elements = document.getElementsByClassName("prefill");
                var hide = false;
                
                for (i in csv){
                    //console.log(aktivitet_values_array[aktivitet_index]["Aktivitet"]);
                    if (aktivitet === csv[i][0]) {
                        hide =true;
                        document.getElementById("points").value = csv[i][1];
                        document.getElementById("kommentar").value = csv[i][2];
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
  foreach ($csv as $value){
    echo("<option value=");
    echo($value[0]);
    echo(">");
  }
  ?>
  </datalist>

  <label for="aktivitet" class="placeholder">Aktivitet:</label>
</div>

<div class="input-container ic1">
    <input type="text" id="kommentar" name="kommentar" class="prefill input" placeholder=" ">
    <div class="cut"></div>
    <label for="kommentar" class="prefill placeholder">Kommentar:</label>
</div>

<div class="input-container ic1">
  <input type="text" id="points" name="points" required autocomplete="off" class="prefill input" placeholder=" "><br class="prefill"><br class="prefill">
  <div class="cut"></div>
  <label for="points" class="prefill placeholder">Points:</label>
</div>

<div class="input-container ic1">
  <input type="password" id="password" name="password" required class="input" placeholder=" "><br><br>
  <div class="cut"></div>
  <label for="password" class="placeholder">Password:</label>
</div>
  <input type="submit" value="Submit" class="submit">
  </form>










<?php
//inputs
// password (acces password for adding points) (pass is 1234 now)
// aktivitet (aktivitet deltaget i)
// is used of studie nr is not set card_id - used to identify student/studentnr

include("db_connect.php");

if (isset($_POST['password'])){
    $pass_hash = hash('md5',$_POST['password']);
    console_log($pass_hash);
    //compare hash with password
    if (strcmp($pass_hash,"81dc9bdb52d04dc20036dbd8313ed055") == 0){
        console_log("passed hash check");
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
        
        $user = new bruger($studienr);
        $points = trim($points);
        if (! (is_numeric($points))){
            exit("Error:non integer point value!");
        }

        //checker om brugeren er fremmødt til studierådsmøde
        if (strcasecmp($aktivitet, "studierådsmøde") == 0){
            $user->fremmødt();
        }else{
            $user->addpoint($points, $aktivitet, $kommentar);
        }
    }else{
        exit("Error:Wrong password!");
    }
}

?>