<head>
        <link rel="stylesheet" href="./style/Stylesheet.css">
        <link rel="icon" type="image/x-icon" href="favicon.ico"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add point</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script> <!-- Tilføjer javascript-library "jqeury" -->
        <script src="https://requirejs.org/docs/release/2.3.5/minified/require.js" defer></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<?php

include("./navbar/Navbar.php"); // Indkluderer navbar.
include("user_class.php");
include("./config/db_connect.php"); // Forbinder til databasen.
include_once("name_fill.php");

$konstituerede = fetch_konstituerede();

//variables for meeting form dates and stuff
$today = date('Y-m-d');

$sqli = "SELECT * FROM `aktivitet_typer` ORDER BY `type_id` ASC";
$result = mysqli_query($db, $sqli);

while ($row = mysqli_fetch_assoc($result)) {
    $aktivitets_typer[] = $row; // Inside while loop
 }



?>

<br> </br>
<script>
            // Funktion der ændrer udfylder værdier på siden, hver gang man ændrer aktiviteten.
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
            }
            </script>


<div class="row">
<div style="" class="column">
<form action="" method="post" class="form">
<text class="title">Add points</text>

<div class="input-container ic1"> 
  <input list="navneliste" id="navn" name="navn" autocomplete="off" onkeyup="Updatestudienr();" class="input" placeholder=" "><br><br>
  <div class="cut"></div>
  <datalist id="navneliste">
  <?php
  foreach ($konstituerede as $medlem){
    echo("<option value=\"");
    echo($medlem['navn']);
    echo("\">");
  }
  ?>
  </datalist>
  <label for="Navn" class="placeholder">Navn:</label>
</div>

<div class="input-container ic1"> 
  <input list="studienrliste" id="studienr" name="studienr" required autocomplete="off" onkeyup="Updatenavn();" class="input" placeholder=" "><br><br>
  <div class="cut"></div>
  <datalist id="studienrliste">
  <?php
  foreach ($konstituerede as $medlem){
    echo("<option value=\"");
    echo($medlem['studienr']);
    echo("\">");
  }
  ?>
  </datalist>
  <label for="studienr" class="placeholder">Studienr:</label>
</div>

<div class="input-container ic1">
    <input type="date" id="dato" name="dato" required class="input" placeholder=" " 
    <?php echo("value=\"$today\"
       min=\"2000-01-01\" max=\"2025-12-31\"");?>><br><br>
    <div class="cut"></div>
    <label for="dato" class="placeholder">Dato:</label>
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


<br><br>
<div class="g-recaptcha" data-sitekey="6Lcnh7weAAAAAJPuuq1PhC31cFwUsTNlwqitBCXv"></div>

  <input type="submit" value="Submit" name="submit" class="submit">
</form>
</div>

<?php 
if (isset($_SESSION['role']) && $_SESSION['role']>1){
?>
<div class="bluebox">
<div style="" class="column">
<text class="title"> Anmodninger om point </text><br><br>
<text class="subtitle">Standard value er aktivitet med bestemt forudbestemte point værdier</text><br><br>
<text class="subtitle">No standard er aktiviteter der ikke defineret og ikke har prebestemt værdi</text><br><br>
<text class="subtitle">Wrong standard er aktivitet med standard point værdi,
 men som har fået defineret en anden point værdi. Kan selvfølgelig være af god grund 
 evt. beskrevet i kommentaren.</text>
 <br><br>
<?php
  if (isset($_POST['submit']) && ($_POST['submit'] == "Approve")){
    $studienr = $_POST['studienr'];
    $medlem = new bruger($studienr);
    $medlem->approvepoint($_POST['pointid']);
  }
  if (isset($_POST['submit']) && ($_POST['submit'] == "Disapprove")){
    $studienr = $_POST['studienr'];
    $medlem = new bruger($studienr);
    $medlem->deletepoint($_POST['pointid']);
  }
  

  include("./config/db_connect.php"); // Forbinder til databasen. 
  $sqli = "SELECT * FROM `aktiviteter` WHERE `approved`='0' ORDER BY `id` ASC";
  $result = mysqli_query($db, $sqli);
    if ($result != False){
            //reset data pointer of mysql result object
            // output data of each row
            echo("<table>
            <tr>
            <th>Navn</th>
            <th>Aktivitet</th>
            <th>Dato</th>
            <th>Point</th>
            <th>Kommentar</th>
            <th>Standard value</th>
            <th>Approve</th>
            <th>Disapprove</th>
            </tr>");
            while($row = $result->fetch_assoc()) {
                $medlem = new bruger($row["studienr"]);
                $navn = $medlem->navn;
                $aktivitetstyper = fetch_aktivitetstype();
                $stardard_value = "No standard";
                foreach ($aktivitetstyper as $aktivitetstype){
                  if ($aktivitetstype[1] == $row["aktivitet"]){
                    $stardard_value = "Wrong Standard";
                    if ($aktivitetstype[2]==$row["point"]){
                      $stardard_value = "Standard";
                    }
                  }
                }


                echo("<tr>
                <th>" . $navn. "</th>
                <th>" . $row["aktivitet"]. "</th>
                <th>" . $row["dato"]. "</th>
                <th>" . $row["point"]. "</th>
                <th>" . $row["kommentar"]. "</th>
                <th>" . $stardard_value. "</th>
                <th>
                  <form action=\"\" method=\"post\">
                  <input type=\"hidden\" id=\"pointid\" name=\"pointid\" value=\"".$row['id']."\">
                  <input type=\"hidden\" id=\"studienr\" name=\"studienr\" value=\"".$row['studienr']."\">
                  <input type=\"submit\" value=\"Approve\" name=\"submit\" class=\"submit\">
                  </form>
                </th>
                <th>
                  <form action=\"\" method=\"post\">
                  <input type=\"hidden\" id=\"pointid\" name=\"pointid\" value=\"".$row['id']."\">
                  <input type=\"hidden\" id=\"studienr\" name=\"studienr\" value=\"".$row['studienr']."\">
                  <input type=\"submit\" value=\"Disapprove\" name=\"submit\" class=\"submit\">
                  </form>
                </th>

                </tr>");
            }
        echo("</table>");
        }
        echo("</div></div>");
}
?>



<?php
//https://codeforgeek.com/google-recaptcha-tutorial/
if(isset($_POST['g-recaptcha-response'])){
  $captcha=$_POST['g-recaptcha-response'];
  $secretKey = $recaptcha_key; //insert in dataase config file
  // post request to server
  $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
  $response = file_get_contents($url);
  $responseKeys = json_decode($response,true);
  $CAPTCHA_succes = $responseKeys["success"];



}

//inputs
// password (acces password for adding points) (pass is 1234 now)
// aktivitet (aktivitet deltaget i)
// is used of studie nr is not set card_id - used to identify student/studentnr
include("./config/db_connect.php");
// the && only runs first check if its false therefore no problem with $_SESSION['role'] as its only reffered to if $_SESSION['role'] exists.
if (isset($_POST['submit']) && ($_POST['submit'] == "Submit")  && ($CAPTCHA_succes || (isset($_SESSION['role']) && $_SESSION['role']>1))){
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
    $dato =  $_POST['dato'];
    $approved_status = 0;
    if ((isset($_SESSION['role']) && $_SESSION['role']>1)){
      $approved_status = 1; //set approved to true if its an admin who adds the point
    }

    $user = new bruger($studienr);
    $points = trim($points);
    if (! (is_numeric($points)) || $points <= 0){
        exit("Error:non positive float point value!");
    }

    $user->addpoint($points, $aktivitet, $kommentar, $dato, $approved = $approved_status);

}

?>
