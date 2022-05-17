<?php 
namespace Prastadev\PHP\MVC\App;

class Render
{
    static public function render(string $data, $model)
    {
        require __DIR__ ."/../view/$data" .".php";
    }
}

?>