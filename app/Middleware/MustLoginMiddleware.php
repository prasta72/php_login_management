<?php 
namespace Prastadev\PHP\MVC\Middleware;

use Prastadev\PHP\MVC\App\Render;
use Prastadev\PHP\MVC\Config\Database;
use Prastadev\PHP\MVC\Repository\SessionRepository;
use Prastadev\PHP\MVC\Repository\UserRepository;
use Prastadev\PHP\MVC\Service\SessionService;

class MustLoginMiddleware implements Middleware
{
    private SessionService $sesionService;

    public function __construct()
    {
        $userRepo = new UserRepository(Database::getConnection());
        $sesionRepo = new SessionRepository(Database::getConnection());
        $this->sesionService = new SessionService($sesionRepo , $userRepo);
    }
    public function before(): void
    {
        $user = $this->sesionService->current();
        if($user == null){
            Render::redirect('/users/login');
        }
    }
}





?>