<?php
 

require_once("{$_SERVER['DOCUMENT_ROOT']}/router.php");
// ##################################################
// ##################################################
// ##################################################

// Static GET
// In the URL -> http://localhost
// The output -> Index
get('/', '/views/home.php');
get('', '/views/home.php');

any('/search', '/views/search.php');

any('/admin', 'adminhome.php');

any('./addpoint', './admin/addpoint.php');

get('/leaderboard','/views/leaderboard.php');

any('/404','/views/404.php');
