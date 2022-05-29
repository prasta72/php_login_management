<?php 
namespace Prastadev\PHP\MVC\Controller;
use Prastadev\PHP\MVC\App\Render;
use Prastadev\PHP\MVC\Config\Database;
use Prastadev\PHP\MVC\Repository\SessionRepository;
use Prastadev\PHP\MVC\Repository\UserRepository;
use Prastadev\PHP\MVC\Service\SessionService;

class HomeController
{

    private SessionService $sesionService;

    public function __construct()
    {
        $userRepo = new UserRepository(Database::getConnection());
        $sesionRepo = new SessionRepository(Database::getConnection());
        $this->sesionService = new SessionService($sesionRepo, $userRepo);
    }
    public function index()
    {
        $user = $this->sesionService->current();
        if($user == null){
            Render::render('home/index',[
                "title" => "PHP Login Management"
            ] );
        }else{
            Render::render('home/dasboard',[
                "title" => "PHP Login Management",
                "user" => [
                    "name" => $user->name
                ]
            ] );
        }
        
    }

    public function dasboard()
    {
        Render::render('home/dasboard',[
            "title" => "PHP Login Management"
        ] );
    }
   
}

 ?>