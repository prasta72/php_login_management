<?php 
namespace Prastadev\PHP\MVC\Repository;

use Prastadev\PHP\MVC\Domain\User;

class UserRepository
{

    private \PDO $connection;

    public function __construct(\PDO $con)
    {
        $this->connection = $con;
    }
    public function save(User $user): User{
        $statement = $this->connection->prepare("INSERT INTO users(id, name, password, email, no_hp) VALUES (?, ?, ?, ?, ?)");
        $statement->execute([
            $user->id, $user->name, $user->password , $user->email, $user->no_hp
        ]);
        return $user;
    }

    public function findById(string $id): ?User{
        $statement = $this->connection->prepare("SELECT id, name, password, email, no_hp FROM users WHERE id = ?");
        $statement->execute([$id]);

        try{
            if($row = $statement->fetch()){
                $user = new User();
    
                $user->id = $row['id'];
                $user->name = $row['name'];
                $user->password = $row['password'];
                $user->email = $row['email'];
                $user->no_hp = $row['no_hp'];
                return $user;
            }else
            {
                return null;
            }

        }finally
        {
            $statement->closeCursor();
        }

       
    }

    public function delete(){
        $this->connection->exec("DELETE  FROM users");
    }

    public function update(User $user):User
    {
        $statement = $this->connection->prepare("UPDATE users SET name = ?, password = ?, email = ?, no_hp = ? WHERE id = ?");
        $statement->execute([
            $user->name,
            $user->password,
            $user->email,
            $user->no_hp,
            $user->id
        ]);

        return $user;
    
    }

}




?>