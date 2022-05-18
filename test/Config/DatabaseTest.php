<?php 

namespace Prastadev\PHP\MVC\Config;

use PHPUnit\Framework\TestCase;

require_once __DIR__ ."/../../test/Config/DatabaseTest.php";
use Prastadev\PHP\MVC\Config\Database;

class DatabaseTest extends TestCase
{
    public function testGetConnectin(){
        $connection = Database::getConnection();

        self::assertNotNull($connection);
    }
    public function testGetConnectinSingletone(){
        $connection = Database::getConnection();
        $connection2 = Database::getConnection();
        
        self::assertSame($connection,$connection2);
    }

}


?>