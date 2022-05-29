<?php 
namespace Prastadev\PHP\MVC\Controller;

use Prastadev\PHP\MVC\App\Render;
use Prastadev\PHP\MVC\Config\Database;
use Prastadev\PHP\MVC\Exception\ValidationException;
use Prastadev\PHP\MVC\Model\UserRegisterRequest;
use Prastadev\PHP\MVC\Repository\UserRepository;
use Prastadev\PHP\MVC\Service\UserService;
use Prastadev\PHP\MVC\Model\UserLoginRequest;
use Prastadev\PHP\MVC\Repository\SessionRepository;
use Prastadev\PHP\MVC\Service\SessionService;

class UserController
{

    private UserService $userService;
    private SessionService $seseionService;


    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepo = new UserRepository($connection);
        $this->userService = new UserService($userRepo);
        $sesionRepo = new SessionRepository($connection);
        $this->seseionService = new SessionService($sesionRepo,$userRepo);
    }
    public function register()
    {
        Render::render('user/Register', [
            "title" =>"register new user"

        ]);
    }

    public function postRegister(){
        $requst = new UserRegisterRequest();
        $requst->id = $_POST['id'];
        $requst->name = $_POST['name'];
        $requst->password = $_POST['password'];
        $requst->email = $_POST['email'];
        $requst->no_hp = $_POST['no_hp'];

        try{
            $this->userService->register($requst);
            Render::redirect('/');

        }catch(ValidationException $exeption){
            Render::render('user/Register', [
                "title" =>"register new user",
                "error" => $exeption->getMessage()
            ]);
        }
    }

    public function login(){
        Render::render('user/Login',[
            "title" => "Login Page"
        ]);
    }


    public function postLogin(){
        $request = new UserLoginRequest();
        $request->id = $_POST['id'];
        $request->password = $_POST['password'];
        
        try{
           $result =  $this->userService->login($request);
           $this->seseionService->create($result->user->id);

           Render::redirect('/');
            // Render::render('home/dasboard',[
            //     "title" => "Dasboard",
            //     "user" => $result->user->id
            // ]);

        }catch(ValidationException $exception){
            Render::render('user/Login',[
                "title" => "Login Page",
                "error" => $exception->getMessage()
            ]);
        }
    }

    public function logOut(){
       $result =  $this->seseionService->current();
       if($result != null){
           $this->seseionService->destroy();
           Render::redirect('/');
       }
    }
}


?>