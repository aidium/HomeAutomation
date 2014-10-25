<?php

session_start();

require_once 'HTTP/OAuth/Consumer.php';

define('PUBLIC_KEY', 'FEHUVEW84RAFR5SP22RABURUPHAFRUNU');
define('PRIVATE_KEY', 'ZUXEVEGA9USTAZEWRETHAQUBUR69U6EF');

define('URL', 'http://api.telldus.com'); //https should be used in production!
define('REQUEST_TOKEN', constant('URL').'/oauth/requestToken');
define('AUTHORIZE_TOKEN', constant('URL').'/oauth/authorize');
define('ACCESS_TOKEN', constant('URL').'/oauth/accessToken');
define('REQUEST_URI', constant('URL').'/json');

define('BASE_URL', 'http://'.$_SERVER["SERVER_NAME"].dirname($_SERVER['REQUEST_URI']));

define('TELLSTICK_TURNON', 1);
define('TELLSTICK_TURNOFF', 2);

$_SESSION['accessToken'] = '0cfc7371bf1ef7767f0fcaa817f4e2a204ff74245';
$_SESSION['accessTokenSecret'] = '53959314da56a6d97a1baa2dc1d653d4';

?>