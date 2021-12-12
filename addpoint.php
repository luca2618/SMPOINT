<head>
        <link rel="stylesheet" href="./style/Stylesheet.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script> <!-- Tilføjer javascript-library "jqeury" -->
        <script src="https://requirejs.org/docs/release/2.3.5/minified/require.js" defer></script>
</head>

<?php
$myfile = fopen("point_liste.txt", "r");
$txt = fread($myfile,filesize("point_liste.txt"));
fclose($myfile);
include("./navbar/Navbar.php"); // Indkluderer navbar.
?>

<script>
            // Funktion der ændrer layoutet på siden, hver gang man ændrer aktiviteten.
            function changeform(){
                var aktivitet = document.getElementById('aktivitet').value;
                var txt = <?php echo json_encode($txt); ?>;
                var prefill_elements = document.getElementsByClassName("prefill");

                aktivitet_values_array = csvToArray(txt);
                
                var hide = false;
                
                for (aktivitet_index in aktivitet_values_array){
                    
                    //console.log(aktivitet_values_array[aktivitet_index]["Aktivitet"]);
                    if (aktivitet === aktivitet_values_array[aktivitet_index]["Aktivitet"]) {
                        hide =true;
                    }

                    

                }

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
                    }

                

            }

            function csvToArray(str) {
                delimiter = ","
                //https://sebhastian.com/javascript-csv-to-array/
                // slice from start of text to the first \n index
                // use split to create an array from string by delimiter
                const headers = str.slice(0, str.indexOf("\n")).split(delimiter);

                // slice from \n index + 1 to the end of the text
                // use split to create an array of each csv value row
                const rows = str.slice(str.indexOf("\n") + 1).split("\n");

                // Map the rows
                // split values from each row into an array
                // use headers.reduce to create an object
                // object properties derived from headers:values
                // the object passed as an element of the array
                const arr = rows.map(function (row) {
                    const values = row.split(delimiter);
                    const el = headers.reduce(function (object, header, index) {
                    object[header] = values[index];
                    return object;
                    }, {});
                    return el;
                });

                // return the array
                return arr;
                }
            </script>



<form action="" method="post">
  <label for="studienr">Studienr:</label>
  <input type="text" id="studienr" name="studienr" required><br><br>

  <label for="aktivitet">Aktivitet:</label>
  <input list="aktivitets_list" id="aktivitet" name="aktivitet" required autocomplete="off" onkeyup="changeform();"><br><br>
  <datalist id="aktivitets_list">
  <option value="Studierådsmøde">
  <option value="Referant">
  </datalist>

  <label for="kommentar" class="prefill">Kommentar:</label>
  <input type="text" id="kommentar" name="kommentar" class="prefill"><br class="prefill"><br class="prefill">

  <label for="points" class="prefill">Points:</label>
  <input type="text" id="points" name="points" required autocomplete="off" class="prefill"><br class="prefill"><br class="prefill">

  <label for="password">Password:</label>
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