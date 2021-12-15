<?php
require_once 'users/init.php';
?>

<html>
    <head>
        <link rel="stylesheet" href="./navbar/Navbar.css"> <!-- Stylesheet -->
        <script src="./navbar/Navbar.js" defer></script> <!-- Kører javascript efter dette script. -->
    </head>
        <!-- Liste med elementer i navbaren. -->
        <ul class="navbar">
            <li class="nav-item">
                <a class="navbar-link" href="./home">Home</a>
            </li>
            <li class="nav-item">
                <a class="navbar-link" href="./search">Search</a>
            </li>
        <?php
        if(isAdmin()){
        ?>
            <li class="nav-item">
                <a class="navbar-link" href="./addpoint">Add points</a>
            </li>
        <?php
        }
        ?>
            <li class="nav-item">
                <a class="navbar-link" href="./leaderboard">Leaderboard</a>
            </li>
            <?php
            if(!isset($_SESSION['user_id'])){ // De næste inde i dette if-statement er kun vist, hvis man ikke er logget ind.
                ?>
                <li class="nav.item" style="float:right">
                    <a class="navbar-link" href="./index">Admin</a>
                </li>
                <?php 
            }else{ ?>
                <li class="nav.item" style="float:right">
                    <a class="navbar-link" href="./index">Admin</a>
                </li>
            <?php } ?>
        </ul>

    </body>
    
</html>