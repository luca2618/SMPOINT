<?php


class bruger {
    public $id;
    public $studienr;
    public $navn;
    public $email;
    public $telefonnr;
    public $point;

    // Klassens constructor.
    function __construct($studienr) {
        include("./config/db_connect.php"); // Forbinder til databasen.

        $this->studienr = $studienr;
        if (!$this->studienr_exists()){
            exit("Ikke konstitueret medlem");
        }
        $sqli = "SELECT * FROM `konstituerede` WHERE studienr=('$studienr')";
        $result = mysqli_query($db, $sqli);
        $data= mysqli_fetch_array($result); 
        
        $this->id = $data['id'];
        $this->point = $data['point'];
        $this->navn = $data['navn'];
        $this->telefonnr = $data['telefonnr'];  
        $this->email = $data['email'];
    }

    //tilføjer point til brugeren
    function addpoint($points, $aktivitet, $kommentar) {

        if (!$this->studienr_exists()){
            exit("Ikke konstitueret medlem");
        }
        
        // Forbinder til databasen.
        include("./config/db_connect.php"); 


        $dato = date('d/m/Y');

        //tilføjer aktivitet til brugeren
        $insertSQL = "INSERT INTO `$this->studienr` (`aktivitet`, `point`, `kommentar`, `dato`) 
        VALUES ('$aktivitet', '$points', '$kommentar' , '$dato')";
        $result = mysqli_query($db, $insertSQL);

        $this->update();
    }

    function addpoint_no_date($points, $aktivitet, $kommentar, $dato) {

        if (!$this->studienr_exists()){
            exit("Ikke konstitueret medlem");
        }
        
        // Forbinder til databasen.
        include("./config/db_connect.php"); 

        //tilføjer aktivitet til brugeren
        $insertSQL = "INSERT INTO `$this->studienr` (`aktivitet`, `point`, `kommentar`, `dato`) 
        VALUES ('$aktivitet', '$points', '$kommentar' , '$dato')";
        $result = mysqli_query($db, $insertSQL);

        $this->update();
    }

    function deletepoint($pointid){
        // Forbinder til databasen.
        include("./config/db_connect.php"); 
        //check if studienr exists
        if (!$this->studienr_exists()){
            return false;
        }
        //checkf først om id'et af aktiviteten findes
        $idsql = "SELECT * FROM `$this->studienr` WHERE `point_id` = $pointid LIMIT 1";
        $result = mysqli_query($db, $idsql);
        //if the acitity doesent exist then return false
        if ($result->fetch_assoc() == null){
            return false;
        }

        //fjerner aktivitet fra brugeren
        $insertSQL = "DELETE FROM `$this->studienr` WHERE `point_id`='$pointid'";
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

        $sqli = "SELECT * FROM `$this->studienr` WHERE (dato=('$dato') AND aktivitet=('studierådsmøde'))";
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
        $sql="SELECT sum(`point`) as total FROM `$this->studienr`";

        $result = mysqli_query($db, $sql);

        while ($row = mysqli_fetch_assoc($result))
        { 
        $this->point = $row['total'];
        }
        $sqli = "UPDATE `konstituerede` SET point=('$this->point') WHERE studienr=('$this->studienr')";
        $result = mysqli_query($db, $sqli);
    }

    function studienr_exists(){
        include("./config/db_connect.php"); // Forbinder til databasen.
        $sqli = "SELECT * FROM `konstituerede` WHERE studienr=('$this->studienr');";
        $result = mysqli_query($db, $sqli);
        if (mysqli_fetch_array($result) == null){
            return false;
        }
   
        $sqli = "SELECT * FROM `$this->studienr`";
        $result = mysqli_query($db, $sqli);
        if(!$result){
            $tableSQL = "CREATE TABLE `$this->studienr`(
                `point_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `aktivitet` VARCHAR(255) NOT NULL,
                `point` VARCHAR(255) NOT NULL,
                `kommentar` VARCHAR(255) NOT NULL,
                `dato` VARCHAR(255) NOT NULL
            );";
        $result = mysqli_query($db, $tableSQL);
        console_log($db -> error);
        }
    return true;
    }
}

function studienr_exists($studienr){
    include("./config/db_connect.php"); // Forbinder til databasen.
    $sqli = "SELECT * FROM `konstituerede` WHERE studienr=('$studienr');";
    $result = mysqli_query($db, $sqli);;
    if (mysqli_fetch_array($result) == null){
        return false;
    }
    return true;
    }

function add_konstiueret($studienr, $navn, $email, $telefonnr){
    include("./config/db_connect.php"); // Forbinder til databasen.
    //setup sql query
    $sql = "INSERT INTO `konstituerede`( `studienr`, `navn`, `email`, `telefonnr`, `point`) VALUES (
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