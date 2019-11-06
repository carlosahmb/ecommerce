<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;

$app = new Slim();

$app->config('debug', true);

require_once("siteRotas.php");
require_once("adminRotas.php");
require_once("admin-usersRotas.php");
require_once("admin-categoriesRotas.php");
require_once("admin-productsRotas.php");
require_once("functions.php");
require_once("admin-ordersRotas.php");



$app->run();

 ?>