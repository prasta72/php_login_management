<?php 
namespace Prastadev\PHP\MVC\Controller;

use PHPUnit\Framework\TestCase;
use Prastadev\PHP\MVC\Config\Database;
use Prastadev\PHP\MVC\Domain\Session;
use Prastadev\PHP\MVC\Domain\User;
use Prastadev\PHP\MVC\Repository\SessionRepository;
use Prastadev\PHP\MVC\Repository\UserRepository;
use Prastadev\PHP\MVC\Service\SessionService;

class HomeControllerTest extends TestCase
{
    private HomeController $homeController;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp():void
    {
        $this->homeController = new HomeController();
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->delete();
    }

    public function testGuest()
    {
        $this->homeController->index();

        $this->expectOutputRegex("[Login Management]");
    }

    public function testUserLogin()
    {
        $user = new User();
        $user->id = "eko";
        $user->name = "Eko";
        $user->password = "rahasia";
        $user->email = "prasta657@dafagjh";
        $user->no_hp = "08987654";
        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->userId = $user->id;
        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->homeController->index();

        $this->expectOutputRegex("[Hello Eko]");
    }
}




?>