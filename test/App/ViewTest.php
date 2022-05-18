<?php 
namespace Prastadev\PHP\MVC\App;

use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
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
}



?>