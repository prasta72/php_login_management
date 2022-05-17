<?php 

namespace Prastadev\PHP\MVC\Test;
use PHPUnit\Framework\TestCase;


class RegexTest extends TestCase
{
    public function testRegex()
    {
        $path = "/products/12345/categories/abcde";
        $patern = "#^/products/([0-9a-zA-Z]*)/categories/([0-9a-zA-Z]*)$#";

        $result = preg_match($patern,$path,$variable);

        self::assertEquals(1,$result);

        var_dump($variable);

        array_shift($variable);

        var_dump($variable);

    }
}





?>