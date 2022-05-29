<?php 
namespace Prastadev\PHP\MVC\Repository;

use phpDocumentor\Reflection\Types\Null_;
use Prastadev\PHP\MVC\Domain\Session;

use function PHPUnit\Framework\exactly;

class SessionRepository
{

    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }



    public function save(Session $session):Session
    {
        $statement = $this->connection->prepare("INSERT INTO sessions(id, id_user) VALUES (?, ?)");
        $statement->execute([$session->id, $session->userId]);

        return $session;
    }
    public function findById(string $id): ?Session
    {
        $statement = $this->connection->prepare("SELECT id, id_user FROM sessions  WHERE id = ?");
        $statement->execute([$id]);
        try{
            if($row = $statement->fetch()){
                $sessions= new Session();
                $sessions->id = $row['id'];
                $sessions->userId = $row['id_user'];
                return $sessions;
                
            }else{
                return null;
            }

        }finally
        {
          $statement->closeCursor();

        }
    }
    public function deleteById(string $id):void
    {
        $statement = $this->connection->prepare("DELETE FROM sessions WHERE id = ?");
        $statement->execute([$id]);

    }
    public function deleteAll():void
    {
    
        $this->connection->exec("DELETE FROM sessions");
    }
}


?>