<title>Check in</title>
<?php
include("header.php");
include("./navbar/Navbar.php"); // Indkluderer navbar.
include("./config/db_connect.php");
include("user_class.php");
//annoying date format but thats what we get for the html form D:
$today = date('Y/m/d');
$return_besked = "";

if (isset($_GET['submit'])){

$studienr = $_GET['studienr'];
$kode = $_GET['kode'];

    if (studienr_exists($studienr)){
        $today = mysqli_real_escape_string($db,$today);
        $studienr = mysqli_real_escape_string($db,$studienr);
        //we limit the statement to only return 1 hit, as there should not be more than one meeting per day
        $sqli = "SELECT * FROM `raadsmode` WHERE `dato` = '$today' LIMIT 1";
        $result = mysqli_query($db, $sqli);
        $data = $result->fetch_assoc();
        if ($data == null){
            $return_besked = "intet møde i dag";
        }else{
            if (strcmp($data['kode'],$kode) == 0){
                $konstitueret = new bruger($studienr);
                $konstitueret->fremmødt();
                $return_besked = "Succes";
            }else{
                $return_besked = "Wrong code";
            }
        
        }
    }else{
        $return_besked = "ikke registeret studienr";
    }

}

?>





<br><br>


<div style="">
<form action="" method="get" class="form">
<text class="title">Check ind</text>
<br>
<text class="subtitle">Skriv studienr og mødekode</text>
<br>
<text class="subtitle"><?php echo($return_besked); ?></text>

<div class="input-container ic1">
<input type="text" id="studienr" name="studienr" required class="input" placeholder=" "><br><br>
<div class="cut"></div>
<label for="studienr" class="placeholder">Studienr:</label>
</div>

<div class="input-container ic1">
<input type="text" id="kode" name="kode" required autocomplete="off" autofill="off" class="input" placeholder=" " autocomplete="off">
<div class="cut"></div>
<label for="kode" class="placeholder">Kode:</label>
</div>

<input type="submit" value="Check in" name="submit" class="submit">

</form>

