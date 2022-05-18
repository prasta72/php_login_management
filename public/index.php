<?php
require_once __DIR__ . "/../vendor/autoload.php";

use Prastadev\PHP\MVC\App\Router;
use Prastadev\PHP\MVC\Controller\HomeController;



Router::add("GET","/", HomeController::class, 'index',[]);
Router::add("GET","/users/register", HomeController::class, 'register',[]);

Router::run();







?>