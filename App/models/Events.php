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
    public function getAllEvents($id) {
        try {
            $sql = $this->pdo->prepare("SELECT e.id, title, description, date, location, e.status AS staatus,
                price, capacity, c.name AS name_category, u.username AS organisateur,
                is_featured, e.created_at as creation 
                FROM events e 
                LEFT JOIN categories c ON e.category_id = c.id 
                LEFT JOIN users u ON e.organizer_id = u.id 
                WHERE e.id = :id");
            
            $sql->execute(['id' => $id]);
            
            return $sql->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    public function getEvents() {
        try {
            $sql = $this->pdo->query("SELECT e.id, title, description, date, location, e.status AS staatus,
                price, capacity, c.name AS name_category, u.username AS organisateur,
                is_featured, e.created_at as creation 
                FROM events e 
                LEFT JOIN categories c ON e.category_id = c.id 
                LEFT JOIN users u ON e.organizer_id = u.id 
                ");
            
          
            
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    
    public function getCommentsByEventId($eventId){
        try{
            $sql = $this->pdo->prepare("SELECT c.id,c.content,c.user_id as IdUser, c.created_at, u.username AS voyeur
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
        $query = "SELECT organizer_id FROM events WHERE id = :event_id";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute(['event_id' => $eventId]);
    $organizerId = $stmt->fetchColumn();

    // Ajouter une notification pour l'organisateur
    $query = "INSERT INTO notifications (user_id, title, message, type) 
              VALUES (:user_id, 'Nouveau commentaire', 'Un participant a commenté votre événement.', 'system_alert')";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute(['user_id' => $organizerId]);
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
        
        if ($result && isset($result['event_id'])) {
            return $result['event_id']; 
        } else {
            return null; 
        }
    }


    
public function getUserIdByCommentId($commentId) {
    $query = "SELECT user_id FROM comments WHERE id = :comment_id";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute(['comment_id' => $commentId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && isset($result['user_id'])) {
        return $result['user_id']; 
    } else {
        return null; 
    }
}

public function reportComment($commentId, $reporterId, $reason) {
    $query = "INSERT INTO reports (comment_id, reporter_id, reason) 
              VALUES (:comment_id, :reporter_id, :reason)";
    $stmt = $this->pdo->prepare($query);
    return $stmt->execute([
        'comment_id' => $commentId,
        'reporter_id' => $reporterId,
        'reason' => $reason
    ]);
}

public function getReportedComments() {
    $query = "SELECT r.id as report_id, r.reason, r.status, r.created_at as report_date, 
                     c.id as comment_id, c.content, c.created_at as comment_date, 
                     u.username as comment_author
              FROM reports r
              JOIN comments c ON r.comment_id = c.id
              JOIN users u ON c.user_id = u.id
              ORDER BY r.created_at DESC";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    
}