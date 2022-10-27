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
get('/h', 'home.php');
get('', 'home.php');

any('/search', 'Search.php');

any('/meetings', 'meetings.php');

get('/leaderboard','leaderboard.php');

get('/kontakt','kontakt.php');

any('/checkin', 'checkin.php');

any('/aktivitetsliste_skabelon','skabeloner/aktivitetsliste_skabelon.csv');
any('/aktivitetstype_skabelon','skabeloner/aktivitetstype_skabelon.csv');
any('/tilmelding_skabelon','skabeloner/tilmelding_skabelon.csv');
any('/modeliste_skabelon','skabeloner/mødeliste_skabelon.csv');

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