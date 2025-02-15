<?php
namespace App\Models;
use App\core\Database;
use PDO;
use PDOException;

class Events{
    private $pdo;
    public function __construct(){
    $this->pdo=Database::getConnection();
    }
    public function getAllEvents(){
        try{
        $sql=$this->pdo->query("SELECT e.id,title,description,date,location,e.status,
        price,capacity,c.name AS name_category , u.username AS organisateur ,
        is_featured,e.created_at as creation from events e LEFT JOIN categories c on e.category_id=c.id 
         LEFT JOIN users u on e.organizer_id=u.id ");
         return $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            die($e->getMessage());
        }
    }
    public function getCommentsByEventId($eventId){
        try{
            $sql = $this->pdo->prepare("SELECT c.id,c.content, c.created_at, u.username
                                        FROM comments c
                                        LEFT JOIN users u ON c.user_id = u.id
                                        WHERE c.event_id = :event_id
                                        ORDER BY c.created_at DESC");
            $sql->execute(['event_id' => $eventId]);
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    public function addComment($eventId, $userId, $content){
        try{
            $sql = $this->pdo->prepare("INSERT INTO comments (event_id, user_id, content)
                                        VALUES (:event_id, :user_id, :content)");
            $sql->execute([
                'event_id' => $eventId,
                'user_id' => $userId,
                'content' => $content
            ]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function updateComment($commentId, $content) {
        try {
            $sql = $this->pdo->prepare("UPDATE comments SET content = :content WHERE id = :comment_id");
            $sql->execute([
                'content' => $content,
                'comment_id' => $commentId
            ]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    
    public function deleteComment($commentId) {
        try {
            $sql = $this->pdo->prepare("DELETE FROM comments WHERE id = :comment_id");
            $sql->execute(['comment_id' => $commentId]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function updateStatus($eventId, $newStatus) {
        try {
            $sql = "UPDATE events SET status = :status WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'status' => $newStatus,
                'id' => $eventId
            ]);
            return true;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
  
    public function getEventIdByCommentId($commentId) {
        $query = "SELECT event_id FROM comments WHERE id = :comment_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['comment_id' => $commentId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['event_id']; // Retourne l'ID de l'événement
    }
    
        
    
}