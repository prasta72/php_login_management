<?php 
namespace Prastadev\PHP\MVC\Service;

use PHPUnit\Framework\TestCase;
use Prastadev\PHP\MVC\Config\Database;
use Prastadev\PHP\MVC\Exception\ValidationException;
use Prastadev\PHP\MVC\Model\UserRegisterRequest;
use Prastadev\PHP\MVC\Repository\UserRepository;

class UserServiceTest extends TestCase
{
    private UserService $userService;

    private UserRepository $userRepo;

    public function setUp():void{

        $con = Database::getConnection();
        $repo = new UserRepository($con);
        $this->userService = new UserService($repo);

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

        $respon = $this->userService->register($request);
    }


}





?>