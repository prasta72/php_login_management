<?php 
namespace Prastadev\PHP\MVC\Service;

use PHPUnit\Framework\TestCase;
use Prastadev\PHP\MVC\Config\Database;
use Prastadev\PHP\MVC\Domain\Session;
use Prastadev\PHP\MVC\Domain\User;
use Prastadev\PHP\MVC\Repository\SessionRepository;
use Prastadev\PHP\MVC\Repository\UserRepository;

function setcookie(string $name,string $value){
    echo "$name: $value";
}


class SessionServiceTest extends TestCase
{
    private SessionService $sesionService;
    private SessionRepository $sesionRepo;
    private UserRepository $userRepo;



    public function setUp():void{
        $this->sesionRepo = new SessionRepository(Database::getConnection());
        $this->userRepo = new UserRepository(Database::getConnection());
        $this->sesionService = new SessionService($this->sesionRepo, $this->userRepo);

        $this->sesionRepo->deleteAll();
        $this->userRepo->delete();

        $user = new User();
        $user->id = "prasta";
        $user->name = "prastakeren";
        $user->password = "prasta12345";
        $user->email = "prasta72@gmail.com";
        $user->no_hp = "o36321600";

        $this->userRepo->save($user);
    }

   public function testCreateSesion(){
       $sesion = $this->sesionService->create("prasta");
       $this->expectOutputRegex("[PRASTA_KEREN: $sesion->id]");

       $result = $this->sesionRepo->findById($sesion->id);

       self::assertEquals("prasta" , $result->userId);

   }


   public function testDestroy()
   {
    $session = $this->sesionService->create("prasta");
    $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;


    $this->sesionService->destroy();
    $this->expectOutputRegex("[PRASTA_KEREN: ]");

    $result = $this->sesionRepo->findById($session->id);
    self::assertNull($result);
   }

   public function testCurrent()
   {
        $sesion = new Session();
        $sesion->id = uniqid();
        $sesion->userId = "prasta";

        $this->sesionRepo->save($sesion);
        $_COOKIE[SessionService::$COOKIE_NAME] = $sesion->id;

        $user = $this->sesionService->current();
        self::assertEquals($sesion->userId, $user->id);
   }
}



?>