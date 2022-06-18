<?php 
namespace Prastadev\PHP\MVC\Controller;


use Prastadev\PHP\MVC\Service\UserService;
use PHPUnit\Framework\TestCase;
use Prastadev\PHP\MVC\App\Render;
use Prastadev\PHP\MVC\Config\Database;
use Prastadev\PHP\MVC\Domain\Session;
use Prastadev\PHP\MVC\Domain\User;
use Prastadev\PHP\MVC\Exception\ValidationException;
use Prastadev\PHP\MVC\Model\UserLoginRequest;
use Prastadev\PHP\MVC\Model\UserRegisterRequest;
use Prastadev\PHP\MVC\Repository\SessionRepository;
use Prastadev\PHP\MVC\Repository\UserRepository;
use Prastadev\PHP\MVC\Service\SessionService;

 class UserCOntrollerTest extends TestCase
 {

    private UserController $userController;

    private UserRepository $userRepo;

    private SessionRepository $sesionRepo;

    public function setUp():void{

        $con = Database::getConnection();
        $userRepository = new UserRepository($con);
        $this->userController = new UserController();

        $this->userRepo = new UserRepository(Database::getConnection());
        $this->userRepo->delete();
        $this->sesionRepo = new SessionRepository($con);

    }
    public function testRegister(){
        $this->userController->register();

        $this->expectOutputRegex("[Register]");
        $this->expectOutputRegex("[id]");
        $this->expectOutputRegex("[name]");
        $this->expectOutputRegex("[password]");

    }
    public function testPostRegisterSucces(){
         
    }
    public function testPostRegisterFailed(){

        $_POST['id'] = "";
        $_POST['password'] = "password";
        $_POST['name'] = "ike";
        $_POST['email'] = "ike";
        $_POST['no_hp'] = "89876";

        $this->userController->postRegister();
        $this->expectOutputRegex("[Register]");
        $this->expectOutputRegex("[id]");
        $this->expectOutputRegex("[name]");
        $this->expectOutputRegex("[password]");
        $this->expectOutputRegex("[id,name,password can not blank]");
    }

    public function testPostRegisterDuplicate(){
        $user = new User();
        $user->id = "ikebozi";
        $user->name = "password";
        $user->password = "ikebozi";
        $user->email = "ikebozi";
        $user->no_hp = "ikebozi";


        $this->userRepo->save($user);



        $_POST['id'] = "ikebozi";
        $_POST['password'] = "password";
        $_POST['name'] = "ike";
        $_POST['email'] = "ike";
        $_POST['no_hp'] = "89876";

        $this->userController->postRegister();
        $this->expectOutputRegex("[Register]");
        $this->expectOutputRegex("[id]");
        $this->expectOutputRegex("[name]");
        $this->expectOutputRegex("[password]");
        $this->expectOutputRegex("[user already exits]");
    }


    public function testLogin()
    {
        Render::render('user/Login',[
            "title" => "Login Page"
        ]);


        self::expectOutputRegex('[body]');
        self::expectOutputRegex('[Login]');
        self::expectOutputRegex('[html]');
        self::expectOutputRegex('[Sign]');
    }
    public function testPostLoginSucces(){

       $_POST['id'] = "izuna";
       $_POST['password'] = "keren";

       $this->userController->postLogin();
       self::expectOutputRegex('[body]');
       self::expectOutputRegex('[profile]');
       self::expectOutputRegex('[html]');
       self::expectOutputRegex('[izuna]');
       self::expectOutputRegex('[Password]');
       
    }
    public function testPostLoginFailed(){

        $_POST['id'] = "izuna";
        $_POST['password'] = "keren123";
 
        $this->userController->postLogin();
     
        self::expectOutputRegex('[body]');
        self::expectOutputRegex('[Login]');
        self::expectOutputRegex('[html]');
        self::expectOutputRegex('[Sign]');
        self::expectOutputRegex("[id or password wrong]");

     }
     public function testLogout()
     {
         $user = new User();
         $user->id = "eko";
         $user->name = "Eko";
         $user->password = "rahasia";
         $user->email = "prasta657@dafagjh";
         $user->no_hp = "08987654";
         $this->userRepo->save($user);

         $session = new Session();
         $session->id = uniqid();
         $session->userId = $user->id;
         $this->sesionRepo->save($session);

         $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

         $this->userController->logOut();

         $this->expectOutputRegex("[Location: /]");
         $this->expectOutputRegex("[PRASTA_KEREN: ]");
     }

    //  public function testUpdateProfile(){

    //     $data = new SessionService($ses);

    //  }

 }




?>