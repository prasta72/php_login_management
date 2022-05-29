<?php 
namespace Prastadev\PHP\MVC\Repository;

use PHPUnit\Framework\TestCase;
use Prastadev\PHP\MVC\Config\Database;
use Prastadev\PHP\MVC\Domain\Session;
use Prastadev\PHP\MVC\Domain\User;

class SessionRepositoryTest extends TestCase
{
    
    private SessionRepository $sesionRepo;

    private UserRepository $userRepo;
    public function setUp():void
    {
        $this->userRepo = new UserRepository(Database::getConnection());
        $this->sesionRepo = new SessionRepository(Database::getConnection());
        $this->sesionRepo->deleteAll();
        $this->userRepo->delete();

        $user = new User();
        $user->id = "prasta";
        $user->name = "prastakeren";
        $user->password = "prasta12345";
        $user->email = "prasta72@gmail.com";
        $user->no_hp = "o36321600";

    }

    public function testSaveSucces(){
        $session = new Session();
        $session->id = "loli";
        $session->userId = "izuna";

        $this->sesionRepo->save($session);
        $user = $this->sesionRepo->findById($session->id);

        self::assertEquals($session->id, $user->id);
        self::assertEquals($session->userId, $user->userId);
    }

}



?>