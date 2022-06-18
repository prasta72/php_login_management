<?php
namespace Prastadev\PHP\MVC\Service;

use Exception;
use Prastadev\PHP\MVC\Config\Database;
use Prastadev\PHP\MVC\Domain\User;
use Prastadev\PHP\MVC\Model\UserRegisterRequest;
use Prastadev\PHP\MVC\Model\UserRegisterResponse;
use Prastadev\PHP\MVC\Repository\UserRepository;
use Prastadev\PHP\MVC\Exception\ValidationException;
use Prastadev\PHP\MVC\Model\UserLoginRequest;
use Prastadev\PHP\MVC\Model\UserLoginResponse;
use Prastadev\PHP\MVC\Model\UserUpdatePasswordRequest;
use Prastadev\PHP\MVC\Model\UserUpdatePasswordResponse;
use Prastadev\PHP\MVC\Model\validateUserUpdateProfileRequest;
use Prastadev\PHP\MVC\Model\UserUpdateProfileRequest;
use Prastadev\PHP\MVC\Model\UserUpdateProfileResponse;

class UserService
{
    private UserRepository $userRepo;

    public function __construct(UserRepository $userepository)
    {
        $this->userRepo = $userepository;
    }

    public function register(UserRegisterRequest $request):UserRegisterResponse
    {
        $this->validateUserRegisterRequest($request);

        try{

            Database::beginTransaction();
            
            $user = $this->userRepo->findById($request->id);

            if($user != null){
                throw new ValidationException("user already exits");
            }

            $user = new User();
            $user->id = $request->id;
            $user->name = $request->name;
            $user->password = password_hash($request->password,PASSWORD_BCRYPT);
            $user->email = $request->email;
            $user->no_hp = $request->no_hp;

            $this->userRepo->save($user);

            $respon = new UserRegisterResponse();
            $respon->user = $user;
            Database::commitTransaction();
            return $respon;
        }catch(Exception $exception){
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validateUserRegisterRequest(UserRegisterRequest $request){
        if($request->id == null || $request->name == null || $request->password == null || 
        trim($request->id) == "" || trim($request->name) == "" || trim($request->password) == ""){
            throw new ValidationException('id,name,password can not blank');
        }
    }
   

    public function login(UserLoginRequest $requsest): UserLoginResponse{
        $this->validateUserLoginRequest($requsest);

        $user = $this->userRepo->findById($requsest->id);

        if($user == null){
            throw new ValidationException('id or password wrong');
        }

        if(password_verify($requsest->password,$user->password)){
            $respon = new UserLoginResponse();
            $respon->user = $user;

            return $respon;
        }else{
            throw new ValidationException('id or password wrong');
        }

    }

    public function Update(UserUpdateProfileRequest $request) : UserUpdateProfileResponse
    {
        $this->validateUserUpdateProfileRequest($request);

       
        try
        {
            Database::beginTransaction();

            $users = $this->userRepo->findById($request->id);

            if($users == null){
                throw new ValidationException("User Not found");
            }

            $user = new User;
            $user->id = $request->id;
            $user->name = $request->name;
            $user->password = $request->password;
            $user->email = $request->email;
            $user->no_hp = $request->no_hp;
    
            $this->userRepo->update($user);
    
            $respon = new UserUpdateProfileResponse();
            $respon->user = $user;

            Database::commitTransaction();
            return $respon;
        }
        catch(Exception $exception){
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    public function updatePassword(UserUpdatePasswordRequest $request) :UserUpdatePasswordResponse{
        $this->validateUserUpdatePasswordRequest($request);

        try{
            Database::beginTransaction();
            $result =  $this->userRepo->findById($request->id);

            if($result == null){
                throw new ValidationException('user not found');
            }

            $password = password_verify($result->password,$request->oldpassword);
            if($password){
                throw new ValidationException('password not found');
            }

            $user = new User;
            $user->id = $request->id;
            $user->name = $request->name;
            $user->password = password_hash($request->password,PASSWORD_BCRYPT);
            $user->email = $request->email;
            $user->no_hp = $request->no_hp;

            $this->userRepo->update($user);
            $respon = new UserUpdatePasswordResponse();
            $respon->user = $user;

            Database::commitTransaction();

            return $respon;

        }catch(Exception $exception)
        {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validateUserUpdateProfileRequest(UserUpdateProfileRequest $request){
        if($request->id == null || $request->name == null || $request->password == null || 
        trim($request->id) == "" || trim($request->name) == "" || trim($request->password) == "" || $request->email == null || $request->no_hp == null){
            throw new ValidationException('id,name,password can not blank');
        }
    }

    private function validateUserUpdatePasswordRequest(UserUpdatePasswordRequest $request){
        if($request->password == null || $request->oldpassword == null){
            throw new ValidationException('password and old password cant null');
        }
    }


  

    public function validateUserLoginRequest(UserLoginRequest $request){
        if($request->id == null || $request->password == null || 
        trim($request->id) == "" || trim($request->password) == ""){
            throw new ValidationException('id,password can not blank bro ccemon baby');
        }
    }
    
}







?>