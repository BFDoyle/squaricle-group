<?php
/* Prints the HTML of one page: php tools/render.php careers.php. Used by generate-seo.php. */
chdir(dirname(__DIR__));
$_SERVER['REQUEST_URI'] = '/';
$_GET = [];
require $argv[1];
