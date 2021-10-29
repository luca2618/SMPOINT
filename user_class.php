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
        //require_once("db_connect.php"); // Forbinder til databasen.
        include("db_connect.php"); // Forbinder til databasen.
        $this->studienr = $studienr;
        $sqli = "SELECT * FROM `users` WHERE studienr=('$studienr')";
        $result = mysqli_query($db, $sqli);
        $data= mysqli_fetch_array($result); 
        
        $this->id = $data['id'];
        $this->point = $data['point'];
        $this->navn = $data['navn'];
        $this->telefonnr = $data['telefonnr'];  
        $this->email = $data['email'];
    }

    function addpoint($points) {
        include("db_connect.php"); // Forbinder til databasen.
        $this->point = $this->point+$points;
        $sqli = "UPDATE `users` SET point=('$this->point') WHERE studienr=('$this->studienr')";
        $result = mysqli_query($db, $sqli);

        $aktivitet = "";
        $kommentar = "";
        $dato = date('d/m/Y');

        $insertSQL = "INSERT INTO `$this->studienr` (`aktivitet`, `point`, `kommentar`, `dato`) 
        VALUES ('$aktivitet', '$points', '$kommentar' , '$dato')";
        $result = mysqli_query($db, $insertSQL);

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
        


    }


}





function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
  }

?>