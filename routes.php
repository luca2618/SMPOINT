<?php
 

require_once("{$_SERVER['DOCUMENT_ROOT']}/router.php");
// ##################################################
// ##################################################
// ##################################################

// Static GET
// In the URL -> http://localhost
// The output -> Index
//main and public routes
get('/', 'home.php');
get('/home', 'home.php');
get('', 'home.php');

any('/search', 'search.php');

get('/leaderboard','leaderboard.php');

get('/kontakt','kontakt.php');

any('/checkin', 'checkin.php');

//admin routes

any('/login', 'user-authentication/login_page.php');
get('/logout', 'user-authentication/logout.php');
any('/signup', 'user-authentication/signup.php');
any('/addpoint', 'addpoint.php');

any('/manager', 'manager.php');
any('/opsetning', 'opsætning.php');

//error routes
any('/404','404.php');



?>