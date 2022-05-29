<?php
require_once __DIR__ . "/../vendor/autoload.php";

use Prastadev\PHP\MVC\App\Router;
use Prastadev\PHP\MVC\Config\Database;
use Prastadev\PHP\MVC\Controller\HomeController;
use Prastadev\PHP\MVC\Controller\UserController;
use Prastadev\PHP\MVC\Midellware\MustLoginMiddleware;
use Prastadev\PHP\MVC\Midellware\MustNotloginMiddleware;

Database::getConnection('prod');

Router::add("GET","/", HomeController::class, 'index',[]);
Router::add("GET","/home/dasboard", HomeController::class, 'dasboard',[MustNotloginMiddleware::class]);
Router::add("GET","/users/register", UserController::class, 'register',[MustNotloginMiddleware::class]);
Router::add("POST","/users/register", UserController::class, 'postRegister',[MustNotloginMiddleware::class]);
Router::add("GET","/users/login", UserController::class, 'login',[MustNotloginMiddleware::class]);
Router::add("POST","/users/login", UserController::class, 'postLogin',[MustNotloginMiddleware::class]);
Router::add("GET","/users/logout", UserController::class, 'logOut',[MustLoginMiddleware::class]);

Router::run();







?>