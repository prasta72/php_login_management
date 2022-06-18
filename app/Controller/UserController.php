<?php 
namespace Prastadev\PHP\MVC\Controller;

use Exception;
use PHPUnit\Util\Xml\Validator;
use Prastadev\PHP\MVC\App\Render;
use Prastadev\PHP\MVC\Config\Database;
use Prastadev\PHP\MVC\Exception\ValidationException;
use Prastadev\PHP\MVC\Model\UserRegisterRequest;
use Prastadev\PHP\MVC\Repository\UserRepository;
use Prastadev\PHP\MVC\Service\UserService;
use Prastadev\PHP\MVC\Model\UserLoginRequest;
use Prastadev\PHP\MVC\Model\UserUpdatePasswordRequest;
use Prastadev\PHP\MVC\Repository\SessionRepository;
use Prastadev\PHP\MVC\Service\SessionService;
use Prastadev\PHP\MVC\Model\UserUpdateProfileRequest;

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

    public function updatePassword()
    {
        $result = $this->seseionService->current();


        Render::render('user/Password',[
            "title" => "update password",
            "id" => $result->id
        ]);
    }

    public function postUpdatePassword()
    {
        $result = $this->seseionService->current();
        $request = new UserUpdatePasswordRequest();
        $request->id = $result->id;
        $request->name = $result->name;
        $request->oldpassword = $_POST['oldPassword'];
        $request->password = $_POST['newPassword'];
        $request->email = $result->email;
        $request->no_hp = $result->no_hp;

        try{
            $this->userService->updatePassword($request);
            Render::redirect('/');
        }
       catch(ValidationException $exception){
        Render::render('user/Password',[
            "title" => "userPassword",
            "id" => $result->id,
            "error" => $exception->getMessage()
        ]);
       }
       
    }

    public function updateProfile()
    {
        $result = $this->seseionService->current();
        
        Render::render('user/Profile',[
            "title" => "update Profile",
            "id" => $result->id,
            "name" => $result->name,
            "email" => $result->email,
            "no_hp" => $result->no_hp
        ]);
    }

    public function postUpdateProfile()
    {
        $result = $this->seseionService->current();
        $request = new UserUpdateProfileRequest();
        $request->id = $_POST['id'];
        $request->name = $_POST['name'];
        $request->password = $result->password;
        $request->email = $_POST['email'];
        $request->no_hp = $_POST['no_hp'];

        try
        {
            $this->userService->Update($request);
            Render::redirect('/');
        }
        catch(ValidationException $exception)
        {
            Render::render('user/profile',[
                "title" => "userprofile",
                "id" => $result->id,
                "name" => $result->name,
                "email" => $result->email,
                "no_hp" => $result->no_hp,
                "error" => $exception->getMessage()
            ]);
        }
       
    }





}


?>