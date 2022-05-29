<?php 
namespace Prastadev\PHP\MVC\Service;

use PHPUnit\Framework\TestCase;
use Prastadev\PHP\MVC\Config\Database;
use Prastadev\PHP\MVC\Domain\User;
use Prastadev\PHP\MVC\Exception\ValidationException;
use Prastadev\PHP\MVC\Model\UserLoginRequest;
use Prastadev\PHP\MVC\Model\UserRegisterRequest;
use Prastadev\PHP\MVC\Repository\UserRepository;

class UserServiceTest extends TestCase
{
    private UserService $userService;

    private UserRepository $userRepo;

    public function setUp():void{

        $con = Database::getConnection();
        $this->userRepo = new UserRepository($con);
        $this->userService = new UserService($this->userRepo);

        $this->userRepo = new UserRepository(Database::getConnection());
        $this->userRepo->delete();

    }


    public function testRegisterSucces()
    {
        $request = new UserRegisterRequest();
        $request->id = "keren";
        $request->name = "manukfacturei";
        $request->password = "rahasia";
        $request->email = "Prasta72345";
        $request->no_hp = "087787654";

        $respon = $this->userService->register($request);
        
        self::assertEquals($request->id,$respon->user->id);
        self::assertEquals($request->name,$respon->user->name);
        self::assertNotEquals($request->password,$respon->user->password);
    }


    public function testRegistrationFailed(){

        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->id = "";
        $request->name = "";
        $request->password = "";
        $request->email = "";
        $request->no_hp = "";

       $this->userService->register($request);
    }

    public function testRegistratrionDuplicate(){
        $user = new User();
        $user->id = "ekojuliatono";
        $user->name = "ekojul123";
        $user->password = "rahasiasekali";
        $user->email = "Prasta72345";
        $user->no_hp = "087787654";

        $this->userRepo->save($user);

        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->id = "ekojuliatono";
        $request->name = "ekojul123";
        $request->password = "rahasiasekali";
        $request->email = "Prasta72345";
        $request->no_hp = "087787654";

        $respon = $this->userService->register($request);

    }


    public function testLoginNotfound(){

        $this->expectException(ValidationException::class);
        $request = new UserLoginRequest();
        $request->id = "jack";
        $request->password = "emili";

        $this->userService->login($request);


    }
    public function testLoginWrongpassword(){

        $user =new User();
        $user->id = "jack";
        $user->name = "blacjack";
        $user->password = password_hash("blackjack", PASSWORD_BCRYPT);
        $user->email = "Prasta72345";
        $user->no_hp = "087787654";

        $this->userRepo->save($user);

        $this->expectException(ValidationException::class);
        $request = new UserLoginRequest();
        $request->id = "jack";
        $request->password = "emili";

        $this->userService->login($request);
        
    }
    public function testLoginSucces(){

        $user =new User();
        $user->id = "jack";
        $user->name = "blacjack";
        $user->password = password_hash("blackjack", PASSWORD_BCRYPT);
        $user->email = "Prasta72345";
        $user->no_hp = "087787654";

        $this->userRepo->save($user);

        $request = new UserLoginRequest();
        $request->id = "jack";
        $request->password = "blackjack";

        $respon = $this->userService->login($request);

        
        self::assertEquals($request->id,$respon->user->id);
        self::assertTrue(password_verify($request->password,$respon->user->password));
    }
  


}





?>