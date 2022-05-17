<?php 

namespace Prastadev\PHP\MVC\Midellware;


class AuthMidleware implements Midellware
{
    public function before(): void
    {
        session_start();
        if(!isset($_SESSION['user'])){
            header('Location: /Login');
            exit();
        }
    }
}






?>