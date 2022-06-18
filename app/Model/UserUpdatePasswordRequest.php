<?php 
namespace Prastadev\PHP\MVC\Model;

class UserUpdatePasswordRequest
{
    public ?string $oldpassword =null;
    public ?string $id = null;
    public ?string $name = null;
    public ?string $password = null;
    public ?string $email = null;
    public ?string $no_hp = null;
}
?>