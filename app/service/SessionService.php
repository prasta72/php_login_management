<?php 
 namespace Prastadev\PHP\MVC\Service;

use Prastadev\PHP\MVC\Domain\Session;
use Prastadev\PHP\MVC\Domain\User;
use Prastadev\PHP\MVC\Repository\SessionRepository;
use Prastadev\PHP\MVC\Repository\UserRepository;

 class SessionService
 {
     private SessionRepository $sesionRepo;
     private UserRepository $userRepo;
     public static string $COOKIE_NAME = "PRASTA_KEREN";

     public function __construct(?SessionRepository $sesion, UserRepository $userRepo)
     {
       $this->sesionRepo = $sesion;
       $this->userRepo = $userRepo;
     }
     public function create(string $userId): Session
     {
        $sesion = new Session();
        $sesion->id = uniqid();
        $sesion->userId = $userId;

        $this->sesionRepo->save($sesion);
        setcookie(self::$COOKIE_NAME, $sesion->id, time() + (60 * 60 * 24 *30), "/");

        return $sesion;
     }
     public function destroy():void
     {
        $sesionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
        echo $sesionId;
        $this->sesionRepo->deleteById($sesionId);
        setcookie(self::$COOKIE_NAME, '', 1, "/");
     }
     public function current(): ?User
     {
        $sesionId = $_COOKIE[self::$COOKIE_NAME] ?? '';

        $sesion = $this->sesionRepo->findById($sesionId);

        if($sesion == null){
            return null;
        }

        return $this->userRepo->findById($sesion->userId);
     }
 }




?>