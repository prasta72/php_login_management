<?php 
namespace Prastadev\PHP\MVC\App;

use PHPUnit\Framework\TestCase;
use Prastadev\PHP\MVC\Model\UserLoginRequest;
use Prastadev\PHP\MVC\Service\UserService;

class ViewTest extends TestCase
{

    private UserService $service;
    public function testRender(){
        Render::render('home/index',[
            'title' => 'php login management'
        ]);

        self::expectOutputRegex('[body]');
        self::expectOutputRegex('[php login management]');
        self::expectOutputRegex('[html]');
        self::expectOutputRegex('[Login]');
        self::expectOutputRegex('[Register]');
    }

    public function testLogin()
    {
        Render::render('user/Login',[
            "title" => "Login Page"
        ]);

        self::expectOutputRegex('[body]');
        self::expectOutputRegex('[Login]');
        self::expectOutputRegex('[html]');
        self::expectOutputRegex('[Sign]');
    }

   
}




?>