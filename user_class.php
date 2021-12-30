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

        $aktiviteter_sqli = "SELECT * FROM `aktiviteter` WHERE studienr=('$studienr')";
        $aktiviteter_result = mysqli_query($db, $aktiviteter_sqli);
        while ($row = mysqli_fetch_assoc($aktiviteter_result)) {
            $this->aktivitets_liste[] = $row; // Inside while loop
         }

    }

    //tilføjer point til brugeren
    function addpoint($points, $aktivitet, $kommentar, $dato) {
        // Forbinder til databasen.
        include("./config/db_connect.php"); 

        //tilføjer aktivitet til brugeren
        $insertSQL = "INSERT INTO `aktiviteter` (`studienr`, `aktivitet`, `point`, `kommentar`, `dato`) 
        VALUES ('$this->studienr', '$aktivitet', '$points', '$kommentar' , '$dato')";
        $result = mysqli_query($db, $insertSQL);
        console_log($insertSQL);
        //$this->update();
    }

    function deletepoint($pointid){
        // Forbinder til databasen.
        include("./config/db_connect.php"); 
        //check if studienr exists
        if (!$this->studienr_exists()){
            return false;
        }
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
        $this->update();
        return true;
    }

    //Checker om brugeren har fremmødt
    function fremmødt() {
        include("./config/db_connect.php"); // Forbinder til databasen.
        $dato = date('d/m/Y');
        if (!$this->studienr_exists()){
            exit("Ikke konstitueret medlem");
        }

        $sqli = "SELECT * FROM `aktiviteter` WHERE (`dato`='$dato' AND `aktivitet`='studierådsmøde' AND `studienr` = '$this->studienr')";
        $result = mysqli_query($db, $sqli);
        $data= mysqli_fetch_array($result);
        console_log($data);
        if ($data == NULL){
            $points = 1;
            $kommentar = "Fremmødt";
            $aktivitet = "Studierådsmøde";
    
            $this->addpoint($points, $aktivitet, $kommentar);
            $this->update();
            }
    }
    
    //Opdaterer brugerens point
    function update(){
        include("./config/db_connect.php"); // Forbinder til databasen.
        $sql="SELECT sum(`point`) as total FROM `aktiviteter` WHERE studienr=('$this->studienr')";

        $result = mysqli_query($db, $sql);

        while ($row = mysqli_fetch_assoc($result))
        { 
        $this->point = $row['total'];
        }
        $sqli = "UPDATE `medlemmer` SET point=('$this->point') WHERE studienr=('$this->studienr')";
        $result = mysqli_query($db, $sqli);
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

function add_konstiueret($studienr, $navn, $email, $telefonnr){
    include("./config/db_connect.php"); // Forbinder til databasen.
    //setup sql query
    $sql = "INSERT INTO `medlemmer`( `studienr`, `navn`, `email`, `telefonnr`, `point`) VALUES (
        '$studienr',
        '$navn',
        '$email',
        '$telefonnr',
        '0'
    )";
    // check if the person already exists
    if (studienr_exists($studienr)==false){
        $result = mysqli_query($db, $sql);
        return true;
    }else{
        return false;
    }
}



function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
  }

?>