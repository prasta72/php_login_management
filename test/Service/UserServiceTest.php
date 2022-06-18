<?php 
namespace Prastadev\PHP\MVC\Service;

use PHPUnit\Framework\TestCase;
use Prastadev\PHP\MVC\Config\Database;
use Prastadev\PHP\MVC\Domain\User;
use Prastadev\PHP\MVC\Exception\ValidationException;
use Prastadev\PHP\MVC\Model\UserLoginRequest;
use Prastadev\PHP\MVC\Model\UserRegisterRequest;
use Prastadev\PHP\MVC\Model\UserUpdatePasswordRequest;
use Prastadev\PHP\MVC\Model\UserUpdateProfileRequest;
use Prastadev\PHP\MVC\Repository\SessionRepository;
use Prastadev\PHP\MVC\Repository\UserRepository;
use SessionHandler;

class UserServiceTest extends TestCase
{
    private UserService $userService;

    private UserRepository $userRepo;

    private SessionRepository $sesionRepo;
    public function setUp():void{

        $con = Database::getConnection();
        $this->userRepo = new UserRepository($con);
        $this->userService = new UserService($this->userRepo);
        $this->sesionRepo = new SessionRepository(Database::getConnection());

        $this->userRepo = new UserRepository(Database::getConnection());
        $this->sesionRepo->deleteAll();
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

    public function testUpdateSuccess()
    {
        $user = new User();
        $user->id = "eko";
        $user->name = "Eko";
        $user->password = password_hash("eko", PASSWORD_BCRYPT);
        $user->email = "prastakeren@@@";
        $user->no_hp = "087678765";
        $this->userRepo->save($user);

        $request = new UserUpdateProfileRequest();
        $request->id = "eko";
        $request->name = "Budi";
        $request->password = $user->password;
        $request->email = "dwiutama@@";
        $request->no_hp = "098987";

        $this->userService->update($request);

        $result = $this->userRepo->findById($user->id);

        self::assertEquals($request->name, $result->name);
    }

    public function testUpdateValidationError()
    {
        $this->expectException(ValidationException::class);

        $request = new UserUpdateProfileRequest();
        $request->id = "";
        $request->name = "";

        $this->userService->update($request);
    }

    public function testUpdateNotFound()
    {
        $this->expectException(ValidationException::class);

        $request = new UserUpdateProfileRequest();
        $request->id = "eko";
        $request->name = "Budi";

        $this->userService->update($request);
    }

    public function testUserUpdatePassword(){
        $user = new User();
        $user->id = "eko";
        $user->name = "Eko";
        $user->password = password_hash("eko", PASSWORD_BCRYPT);
        $user->email = "prastakeren@@@";
        $user->no_hp = "087678765";
        $this->userRepo->save($user);

        $request = new UserUpdatePasswordRequest();
        $request->id = "eko";
        $request->name = "Budi";
        $request->oldpassword = $user->password;
        $request->password = $user->password;
        $request->email = "dwiutama@@";
        $request->no_hp = "098987";

        $this->userService->updatePassword($request);

        $result = $this->userRepo->findById($user->id);

        self::assertEquals($request->password, $result->password);
    }

}





?>