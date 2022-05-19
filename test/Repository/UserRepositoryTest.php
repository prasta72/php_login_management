<?php 
namespace Prastadev\PHP\MVC\Repository;

use PHPUnit\Framework\TestCase;
use Prastadev\PHP\MVC\Config\Database;
use Prastadev\PHP\MVC\Domain\User;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepo;

    protected function setUp():void{
        $this->userRepo = new UserRepository(Database::getConnection());
        $this->userRepo->delete();
    }

    public function testSaveSucces()
    {
        $user = new User();
        $user->id = "prasta";
        $user->name = "prastakeren";
        $user->password = "prasta12345";
        $user->email = "prasta72@gmail.com";
        $user->no_hp = "o36321600";

        $this->userRepo->save($user);
        $result = $this->userRepo->findById($user->id);

        self::assertEquals($user->id, $result->id);
        self::assertEquals($user->name, $result->name);
        self::assertEquals($user->password, $result->password);
    }



}


?>