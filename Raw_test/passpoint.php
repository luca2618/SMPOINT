

<form action="" method="post">
  <label for="fname">Studienr:</label>
  <input type="text" id="studienr" name="studienr" required><br><br>
  <label for="fname">Aktivitet:</label>
  <input type="text" id="aktivitet" name="aktivitet" required><br><br>
  <label for="fname">Kommentar:</label>
  <input type="text" id="kommentar" name="kommentar"><br><br>
  <label for="fname">Points:</label>
  <input type="text" id="points" name="points" required><br><br>
  <label for="fname">Password:</label>
  <input type="password" id="password" name="password" required><br><br>
  <input type="submit" value="Submit">
  </form>

<?php
//inputs
// password (acces password for adding points) (pass is 1234 now)
// aktivitet (aktivitet deltaget i)
// is used of studie nr is not set card_id - used to identify student/studentnr

include("user_class.php");
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