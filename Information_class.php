
<?php

// Funktion der gemmer opslag til forummet i database som BLOB
function gem_opslag($opslag){
    global $db;

    // php-objekt bliver omkodet til BLOB-element der kan indsættes i database.
    // BLOB er smart, fordi det kan indeholde billeder, hvilket betyder at man kan gemme billeder i databasen.
    $opslag = serialize($opslag);
    $opslag = base64_encode($opslag);
    
    // Indsætter opslag i databasen.
    $insertSQL = "INSERT INTO `information` (`opslag`) VALUES ('$opslag')";
    $result = mysqli_query($db, $insertSQL);
    if(!$result){
        die("Couldn't query insert-statement");
    }
}

// parent-klasse til opslag.
class Opslag {
    public $titel;
    public $tekst;
    public $image;
    public $image_type;

    // Klassens constructor.
    function __construct($titel, $tekst, $image, $image_type) {
        $this->titel = $titel;
        $this->tekst = $tekst;
        $this->image = $image;
        $this->image_type = $image_type;
        //$this->tilføjelse = null;
        $this->dato = date('d/m/Y');
        
    }

    // Display-funktion der viser opslaget på forummet.
    public function display($opslags_id) {
        
            echo("
            <div class=\"opslagsbox\">
                <h1 class=\"titel\">{$this->titel}</h1>
                <p class=\"tekst\">{$this->tekst}</p>
                ");

                if(isset($this->image))
                {
                // Enable output buffering
                ob_start();
                echo($this->image);
                // Capture the output
                $imagedata = ob_get_contents();
                // Clear the output buffer
                ob_end_clean();

                echo '<img src="data:'.$this->image_type.';base64,'.base64_encode($imagedata).'">';
                }

                //output dato
                echo("<p> Dato:{$this->dato}</p>");

            
            
    }
}

class Information extends Opslag {
    public $gruppe;

    function __construct($titel, $tekst, $image, $image_type, $gruppe){
        parent::__construct($titel, $tekst, $image, $image_type);
        $this->gruppe = $gruppe;
    }

    function display($opslags_id){
        if($this->gruppe == -1 || (isset($_SESSION['user_id']) && $_SESSION['role'] >= $this->gruppe)){
            parent::display($opslags_id);
            return true;
        } else {
            return false;
        }
    }


}


class Turnering extends Opslag{
    public $min_alder; // Nye variabler som kun gælder for turneringer.
    public $max_alder;

    function __construct($titel, $tekst, $image, $image_type, $gruppe, $min_alder, $max_alder){
        //passerer de normale argumenter videre til basis konstruktøren
        parent::__construct($titel, $tekst, $image, $image_type, $gruppe); // Kører parent-constructor
        $this->min_alder = $min_alder;
        $this->max_alder = $max_alder;
    }

    function display($opslags_id) {
        if(!isset($_SESSION['user_id'])){
            return false;
        } else {
            $birthday = $_SESSION['birthday'];
            // del op i array hvor [0] er dag, [1] er måned, og [2] er årtals
            $birthday = explode("/",$birthday);
            
            //del nuværende dato for posten op i array på samme måde
            $dato_opdelt = explode("/", $this->dato);

            // Opslaget skal kun vises, hvis brugeren er inde i aldersgruppen for turneringen.
            $alder = $dato_opdelt[2]-$birthday[2];
            if ($dato_opdelt[1]==$birthday[1]){
                if ($dato_opdelt[0]>$birthday[0]){
                    $alder = $alder-1;
                }
            } elseif ($dato_opdelt[1]<$birthday[1]){
                $alder = $alder-1;
            }
            if (($alder>=$this->min_alder)&&($alder<=$this->max_alder)){
                parent::display($opslags_id);
                return true;
                } else {
                    return false;
                }
            
        }
    }
}

class Event extends Opslag {
    public $gruppe;
    public $tilmeldte;

    function __construct($titel, $tekst, $image, $image_type, $gruppe){
        parent::__construct($titel, $tekst, $image, $image_type);
        $this->gruppe = $gruppe;
        $this->tilmeldte = [];
        
        
    }

    function display($opslags_id){
    if($this->gruppe == -1 || (isset($_SESSION['user_id']) && $_SESSION['role'] >= $this->gruppe)){
            parent::display($opslags_id);
        //Hvis kun tildmeldingsknappen, hvis personen er logget ind
        if(isset($_SESSION['username'])){
            if(!in_array($_SESSION['username'], $this->tilmeldte)){ // Kan kun tilmeldes, hvis man ikke allerede er tilmeldt.
                if(isset($_REQUEST["tilmeld"])){
                    if($_REQUEST["tilmeld"]==$opslags_id){
                        // Tilføjer username til liste af tilmeldte
                        array_push($this->tilmeldte, $_SESSION['username']);
                        $erstatning=$this;
                        global $db;
                        // php-objekt bliver omkodet til BLOB-element der kan indsættes i database.
                        // BLOB er smart, fordi det kan indeholde billeder, hvilket betyder at man kan gemme billeder i databasen.
                        $erstatning = serialize($erstatning);
                        $erstatning = base64_encode($erstatning);
            
                        // Opdaterer opslag i databasen.
                        $insertSQL = "UPDATE information SET opslag='$erstatning' WHERE id='$opslags_id'";
                        $result = mysqli_query($db, $insertSQL);
                        if(!$result){
                            die("Couldn't query insert-statement");
                        }
                        // Refresher siden så knap ændres fra tilmeld til frameld.
                        echo '<script type="text/javascript">location.reload(true);</script>';
                    }
                }
                // Knap der tilmelder til event.
                echo("<br>
                <form action=\"\" method=\"POST\">
                <button type=\"submit\" name=\"tilmeld\" value=\"{$opslags_id}\">Tilmeld</button>
                </form>");
            } else {
                if(isset($_REQUEST["frameld"])){
                    if($_REQUEST["frameld"]==$opslags_id){
                        // Fjerner username fra liste med tilmeldte
                        $key = array_search($_SESSION['username'], $this->tilmeldte);
                        unset($this->tilmeldte[$key]);

                        $erstatning=$this;
                        global $db;
                        // php-objekt bliver omkodet til BLOB-element der kan indsættes i database.
                        // BLOB er smart, fordi det kan indeholde billeder, hvilket betyder at man kan gemme billeder i databasen.
                        $erstatning = serialize($erstatning);
                        $erstatning = base64_encode($erstatning);
            
                        // Opdaterer opslag i databasen.
                        $updateSQL = "UPDATE information SET opslag='$erstatning' WHERE id='$opslags_id';";
                        $result = mysqli_query($db, $updateSQL);
                        if(!$result){
                            die("Couldn't query update-statement");
                        }
                        // Refresher siden så knap ændres fra frameld til tilmeld.
                        echo '<script type="text/javascript">location.reload(true);</script>';
                    }
                }
                // Knap der framelder en fra event
                echo("<br>
                <form action=\"\" method=\"POST\">
                <button type=\"submit\" name=\"frameld\" value=\"{$opslags_id}\">Frameld</button>
                </form>");
            
            }
    }
    // Skriver på siden en liste med alle de tilmeldte.
        $i = 0;
        $tilmeldteString = "";
        foreach($this->tilmeldte as $navn){
            if($i != 0){
                $key = array_search($navn, $this->tilmeldte);
                $tilmeldteString.= ", " . $this->tilmeldte[$key];
                } else {
                $key = array_search($navn, $this->tilmeldte);
                $tilmeldteString .= $this->tilmeldte[$key];
                }
                    $i++;
        }
        echo ("<p>Tilmeldte:{$tilmeldteString}</p>");
            return true;
        } else {
            return false;
        } 
    }
}
?>