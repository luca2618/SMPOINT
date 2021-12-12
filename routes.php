<?php
 

require_once("{$_SERVER['DOCUMENT_ROOT']}/router.php");
// ##################################################
// ##################################################
// ##################################################

// Static GET
// In the URL -> http://localhost
// The output -> Index
get('/', 'main.php');
get('', 'main.php');

get('/search', 'search.php');
post('/search', 'search.php');

get('/addpoint', 'addpoint.php');
post('/addpoint', 'addpoint.php');

any('/404','404.php');
