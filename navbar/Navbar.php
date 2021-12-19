<html>
    <head>
        <link rel="stylesheet" href="./navbar/Navbar.css"> <!-- Stylesheet -->
        <script src="./navbar/Navbar.js" defer></script> <!-- Kører javascript efter dette script. -->
    </head>
        <!-- Liste med elementer i navbaren. -->
        <ul class="navbar">
            <li class="nav-item">
                <a class="navbar-link" href="./">Home</a>
            </li>
            <li class="nav-item">
                <a class="navbar-link" href="./search">Search</a>
            </li>
            <li class="nav-item">
                <a class="navbar-link" href="./leaderboard">Leaderboard</a>
            </li>
            <li class="nav-item">
                <a class="navbar-link" href="./kontakt">Kontakt</a>
            </li>
            <li class="nav-item">
                <a class="navbar-link" href="./checkin">Check in</a>
            </li>

            <?php
            if(!isset($_SESSION['id'])){ // De næste inde i dette if-statement er kun vist, hvis man ikke er logget ind.
                ?>
                <li class="nav.item" style="float:right">
                    <a class="navbar-link" href="./login">Login</a>
                </li>
                <?php
            } else { // De næste er kun vist, hvis man er logget ind.
                    ?>
                <li class="nav.item" style="float:right">
                    <a class="navbar-link" href="./logout">Logout</a>
                </li>

                <li class="nav-item">
                <a class="navbar-link" href="./addpoint">Add points</a>
                </li>

                <li class="nav-item">
                <a class="navbar-link" href="./manager">Manage</a>
                </li>
                
                <?php } ?>

        </ul>

    </body>
    
</html>