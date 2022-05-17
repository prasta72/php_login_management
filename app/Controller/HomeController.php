<?php 
namespace Prastadev\PHP\MVC\Controller;
use Prastadev\PHP\MVC\App\Render;

class HomeController
{
    public function index(){
        $model = [
            "title" =>"belajar php",
            "content" => "belajar php MVC "
        ];

        Render::render("home/index",$model);
    }
    public function hello(){
        echo "homeController.hello()";
    }
    public function world(){
        echo "homeController.world()";
    }

    public function getPreg(string $name,string $hoby,string $keren){
        echo "$name:$hoby:$keren";
    }
}

 ?>