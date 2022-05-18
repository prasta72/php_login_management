<?php 
namespace Prastadev\PHP\MVC\Controller;
use Prastadev\PHP\MVC\App\Render;

class HomeController
{
    public function index()
    {
        Render::render('home/index',[
            "title" => "PHP Login Management"
        ] );
    }
    public function register()
    {
        Render::render('home/register',[
            "title" => "Register"
        ] );
    }
}

 ?>