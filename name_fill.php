
<?php 
include_once("./navbar/Navbar.php"); // Indkluderer navbar.
include_once("./config/db_connect.php"); // Forbinder til databasen.
include_once("user_class.php");
//variables for meeting form dates and stuff
$today = date('Y-m-d');

$konstituerede = fetch_konstituerede();
?>

<script>
            // Funktion der ændrer layoutet på siden, hver gang man ændrer navnet.
            function Updatestudienr(){
                var navn_værdi = document.getElementById('navn').value;
                var konstituerede = <?php echo json_encode($konstituerede); ?>;
                
                for (i in konstituerede){
                    if (navn_værdi === konstituerede[i]['navn']) {
                        document.getElementById("studienr").value = konstituerede[i]['studienr'];
                    }

                }
            }

            function Updatenavn(){
                var studienr_værdi = document.getElementById('studienr').value;
                var konstituerede = <?php echo json_encode($konstituerede); ?>;
                
                for (i in konstituerede){
                    if (studienr_værdi === konstituerede[i]['studienr']) {
                        document.getElementById("navn").value = konstituerede[i]['navn'];
                    }

                }
            }
            </script>