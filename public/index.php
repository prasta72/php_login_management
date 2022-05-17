<?php
require_once __DIR__ . "/../vendor/autoload.php";

use Prastadev\PHP\MVC\App\Router;
use Prastadev\PHP\MVC\Controller\HomeController;
use Prastadev\PHP\MVC\Controller\ProductController;
use Prastadev\PHP\MVC\Midellware\AuthMidleware;

Router::add("GET","/products/([0-9a-zA-Z]*)/categories/([0-9a-zA-Z]*)", ProductController::class, 'categories');
Router::add("GET", "/prasta/([0-9a-zA-Z]*)/hobby/([0-9a-zA-Z]*)/belajar/([0-9a-zA-Z]*)" , HomeController::class , 'getPreg');
Router::add("GET","/", HomeController::class, 'index');
Router::add("GET","/hello", HomeController::class, 'hello',[AuthMidleware::class]);
Router::add("GET","/world", HomeController::class, 'world',[AuthMidleware::class]);

Router::run();







?>