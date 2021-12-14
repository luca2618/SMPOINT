<?php
 

require_once("{$_SERVER['DOCUMENT_ROOT']}/router.php");
// ##################################################
// ##################################################
// ##################################################

// Static GET
// In the URL -> http://localhost
// The output -> Index
get('/', 'home.php');
get('', 'home.php');

any('/search', 'search.php');

any('/addpoint', 'addpoint.php');

get('/leaderboard','leaderboard.php');

any('/404','404.php');
