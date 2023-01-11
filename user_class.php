<?php

class bruger {
    public $id;
    public $studienr;
    public $navn;
    public $email;
    public $telefonnr;
    public $point;
    public $aktivitets_liste;

    // Klassens constructor.
    function __construct($studienr) {
        include("./config/db_connect.php"); // Forbinder til databasen.

        $this->studienr = $studienr;
        if (!$this->studienr_exists()){
            exit("Ikke konstitueret medlem");
        }
        $sqli = "SELECT * FROM `medlemmer` WHERE studienr=('$studienr')";
        $result = mysqli_query($db, $sqli);
        $data= mysqli_fetch_array($result); 
        
        $this->id = $data['id'];
        $this->point = $data['point'];
        $this->navn = $data['navn'];
        $this->telefonnr = $data['telefonnr'];  
        $this->email = $data['email'];

        $aktiviteter_sqli = "SELECT * FROM `aktiviteter` WHERE studienr=('$studienr') AND approved='1' ORDER BY dato DESC";
        $aktiviteter_result = mysqli_query($db, $aktiviteter_sqli);
        while ($row = mysqli_fetch_assoc($aktiviteter_result)) {
            $this->aktivitets_liste[] = $row; // Inside while loop
         }

    }

    //tilføjer point til brugeren
    function addpoint($points, $aktivitet, $kommentar, $dato, $approved = '1') {
        if ($aktivitet == "Studierådsmøde"){
            $this->fremmødt($dato=$dato);
            return;
        }
        // Forbinder til databasen.
        include("./config/db_connect.php"); 
        $points = str_replace(",",".",$points);
        //tilføjer aktivitet til brugeren
        $insertSQL = "INSERT INTO `aktiviteter` (`studienr`, `aktivitet`, `point`, `kommentar`, `approved`, `dato`) 
        VALUES ('$this->studienr', '$aktivitet', '$points', '$kommentar', '$approved' , '$dato')";
        $result = mysqli_query($db, $insertSQL);
        $this->update_points();
        console_log($result);
    }

    function deletepoint($pointid){
        // Forbinder til databasen.
        include("./config/db_connect.php"); 
        //checkf først om id'et af aktiviteten findes
        $idsql = "SELECT * FROM `aktiviteter` WHERE (`id` = $pointid and `studienr` = '$this->studienr') LIMIT 1";
        $result = mysqli_query($db, $idsql);
        //if the acitity doesent exist then return false
        if ($result->fetch_assoc() == null){
            return false;
        }

        //fjerner aktivitet fra brugeren
        $insertSQL = "DELETE FROM `aktiviteter` WHERE `id`='$pointid'";
        $result = mysqli_query($db, $insertSQL);
        
        //on succes update points and return true
        $this->update_points();
        return true;
    }

    function approvepoint($pointid){
        // Forbinder til databasen.
        include("./config/db_connect.php"); 
        //checkf først om id'et af aktiviteten findes
        $idsql = "UPDATE `aktiviteter` SET `approved` = '1' WHERE `id` = '$pointid';";
        $result = mysqli_query($db, $idsql);

        $this->update_points();

        return $result;
    }

    //Checker om brugeren har fremmødt
    function fremmødt($dato=false) {
        if ($dato == false){
            $dato = $today = date('Y-m-d');
        }
        include("./config/db_connect.php"); // Forbinder til databasen.
        if (!$this->studienr_exists()){
            exit("Ikke konstitueret medlem");
        }

        $sqli = "SELECT * FROM `aktiviteter` WHERE (`dato`='$dato' AND `aktivitet`='studierådsmøde' AND `studienr` = '$this->studienr')";
        $result = mysqli_query($db, $sqli);
        $data= mysqli_fetch_array($result);
        if ($data == NULL){
            $points = 1;
            $kommentar = "Fremmødt";
            $aktivitet = "Studierådsmøde";
    
            $this->addpoint($points, $aktivitet, $kommentar, $dato);
            $this->update_points();
            }
    }
    
    //Opdaterer brugerens point
    function update_points(){
        include("./config/db_connect.php"); // Forbinder til databasen.
        $legacy_date = '2022-09-13';
        $sql="SELECT sum(`point`) as total FROM `aktiviteter` WHERE studienr=('$this->studienr') AND approved=('1') AND dato > '$legacy_date'";

        $result = mysqli_query($db, $sql);

        while ($row = mysqli_fetch_assoc($result))
        { 
        $this->point = $row['total'];
        }
        $sqli = "UPDATE `medlemmer` SET `point`=('$this->point') WHERE studienr=('$this->studienr')";
        $result = mysqli_query($db, $sqli);
    }

    function update($columm, $value){
        include("./config/db_connect.php"); // Forbinder til databasen.
        $value = mysqli_real_escape_string($db,$value);
        $sqli = "UPDATE `medlemmer` SET `$columm` =('$value') WHERE studienr=('$this->studienr')";
        // mysqli_query returns true or false when trying to update value.
        $result = mysqli_query($db, $sqli);
        return $result;


    }

    function studienr_exists(){
        include("./config/db_connect.php"); // Forbinder til databasen.
        $sqli = "SELECT * FROM `medlemmer` WHERE studienr='$this->studienr';";
        $result = mysqli_query($db, $sqli);
        if (mysqli_fetch_array($result) == null){
            return false;
        }
    return true;
    }
}

function studienr_exists($studienr){
    include("./config/db_connect.php"); // Forbinder til databasen.
    $sqli = "SELECT * FROM `medlemmer` WHERE studienr=('$studienr');";
    $result = mysqli_query($db, $sqli);
    if (mysqli_fetch_array($result) == null){
        return false;
    }
    return true;
    }

function add_konstiueret($studienr, $navn, $email){
    include("./config/db_connect.php"); // Forbinder til databasen.
    //setup sql query
    $telefonnr="";
    $sql = "INSERT INTO `medlemmer`( `studienr`, `navn`, `email`, `telefonnr`, `point`) VALUES (
        '$studienr',
        '$navn',
        '$email',
        '$telefonnr',
        '0'
    )";
    // check if the person already exists
    if ((studienr_exists($studienr)==false)&&($navn!="")&&($email!="")) {
        if (mysqli_query($db, $sql)) {return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function add_aktivitet_type($aktivitet, $point, $forklaring){
        include("./config/db_connect.php");
        $aktivitet = mysqli_real_escape_string($db,$aktivitet);
        $forklaring = mysqli_real_escape_string($db,$forklaring);
        $sqlicheck = "SELECT * FROM `aktivitet_typer` WHERE `Aktivitet` = '$aktivitet'; ";
        $result = mysqli_query($db, $sqlicheck);
        if(mysqli_fetch_array($result) == null){
        $sqli = "INSERT INTO  `aktivitet_typer` (`Aktivitet`, `Point`, `Forklaring`) 
            VALUES ('$aktivitet', '$point', '$forklaring'); ";
        $result = mysqli_query($db, $sqli);
        #console_log($result);
        #console_log(mysqli_error($db));
        return true;
        }
        return false;
}

function guest_fremmødt($studienr, $navn) {
    include("./config/db_connect.php"); // Forbinder til databasen.
    $dato = date('Y-m-d');
    if (!$this->studienr_exists()){
        exit("Ikke konstitueret medlem");
    }

    $sqli = "SELECT * FROM `aktiviteter` WHERE (`dato`='$dato' AND `aktivitet`='studierådsmøde' AND `studienr` = '$studienr')";
    $result = mysqli_query($db, $sqli);
    $data= mysqli_fetch_array($result);
    if ($data == NULL){
        $points = 1;
        $kommentar = "Fremmødt";
        $aktivitet = "Studierådsmøde";

        $insertSQL = "INSERT INTO `aktiviteter` (`studienr`, `aktivitet`, `point`, `kommentar`, `dato`, `guest_name`) 
        VALUES ('$this->studienr', '$aktivitet', '$points', '$kommentar' , '$dato', '$name')";
        $result = mysqli_query($db, $insertSQL);
        }
}

function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
  }



function fetch_aktivitetstype($print=false){
    include("./config/db_connect.php"); // Forbinder til databasen. 
    $sqli = "SELECT * FROM `aktivitet_typer` ORDER BY `type_id` ASC";
    $result = mysqli_query($db, $sqli);
    $data= mysqli_fetch_all($result);
    if (($result != False) and ($print==true)){
        $ranknr = 0;
        mysqli_data_seek($result, 0);
        if ($result->num_rows > 0)  {
            //reset data pointer of mysql result object
            // output data of each row
            echo("<table>
            <tr>
            <th>Aktivitet</th>
            <th>Point</th>
            <th>Forklaring</th>
            </tr>");
            while($row = $result->fetch_assoc()) {
                $ranknr += 1;
                echo("<tr> <th> " . $row["Aktivitet"]. "</th><th>" . $row["Point"]. "</th><th>" . $row["Forklaring"]. "</th></tr>");
            }
        } else {
            echo ("<th>0 results</th>");
        }
        echo("</table>");
        mysqli_close($db);
        }
        return $data;
    }
    
    function fetch_leaderboard($print=false){
        include("./config/db_connect.php"); // Forbinder til databasen. 
        //+0 to make sure its handled as numbers
        $sqli = "SELECT * FROM `medlemmer` ORDER BY `point`+0 DESC";
        $result = mysqli_query($db, $sqli);
        $data = mysqli_fetch_all($result,MYSQLI_ASSOC);
        if (($result != False) and ($print==true)){
            $ranknr = 0;
            mysqli_data_seek($result, 0);
            if ($result->num_rows > 0)  {
                // output data of each row
                echo("<table>
                <tr>
                <th>Rank</th>
                <th>Navn</th>
                <th>Studienr</th>
                <th>Point</th>
                </tr>");
                while($row = $result->fetch_assoc()) {
                    $ranknr += 1;
                    echo("<tr> <th> " . $ranknr. "</th><th>" . $row["navn"]. "</th><th>"
                    ."<a class=\"link\"  href=\"./search?studienr=". $row["studienr"]."&submit=Search\">". $row["studienr"]."</a>" . "</th><th>" . $row["point"]. "</th></tr>");
                }
            } else {
                echo ("<th>0 results</th>");
            }
            echo("</table>");
            mysqli_close($db);
            }
            return $data;
    }
    function fetch_konstituerede(){
        include("./config/db_connect.php"); // Forbinder til databasen. 
        //+0 to make sure its handled as numbers
        $sqli = "SELECT * FROM `medlemmer` ORDER BY `navn` ASC";
        $result = mysqli_query($db, $sqli);
        $data = mysqli_fetch_all($result,MYSQLI_ASSOC);
        return $data;
    }
    
    function disapprove_all(){
        // Forbinder til databasen.
        include("./config/db_connect.php"); 
        //checkf først om id'et af aktiviteten findes
        $idsql = "DELETE FROM `aktiviteter` WHERE `approved`='0'";
        $result = mysqli_query($db, $idsql);

        return $result;
    }
    function approve_all(){
        // Forbinder til databasen.
        include("./config/db_connect.php"); 
        //checkf først om id'et af aktiviteten findes
        $idsql = "UPDATE `aktiviteter` SET `approved` = '1' WHERE `approved` = '0'";
        $result = mysqli_query($db, $idsql);

        return $result;

    }

    //Det her lort er fucking ueffektivt, 
    //så fucking ikke spam de lorte approve/disapprove all knapper
    //eller have for mange medlemmer
    //burde fikse relationen så det her ikke skal være så skide besværligt
    function update_all(){
        $konstituerede = fetch_konstituerede();
            foreach($konstituerede as $konstitueret){
                $studienr = $konstitueret['studienr'];
                $medlem = new bruger($studienr);
                $medlem->update_points();
            }
        }
    
    function csv_to_array($file_path){
        $Data = [];
        foreach(file($file_path) as $x){
            if (substr_count($x,";")>substr_count($x,",")){
                $Data[] = str_getcsv($x, ";"); //parse the rows
            }else{
                $Data[] = str_getcsv($x, ","); //parse the rows
            }
            
        }
        
        return $Data;
    }




?>