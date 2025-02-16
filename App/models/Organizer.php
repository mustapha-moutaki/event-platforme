<?php
namespace App\Models;

use App\core\Database;
use App\core\BaseModel;
use PDO;
use PDOException;



class Organizer extends BaseModel
{
    protected $table = 'events';

    // public function getEventsByOrganizer($organizerId, $status = null){
    //     $conditions = ['organizer_id' => $organizerId];
    //     if ($status) {
    //         $conditions['status'] = $status;
    //     }
        
    //     return $this->findAll($conditions, 'created_at DESC');
    // }
    public function getEventsByOrganizer($organizerId, $status = null){

        $conditions = ['organizer_id' => $organizerId];


        if ($status) {
            $conditions['status'] = $status;
        }

        $query = "SELECT 
                    e.*, 
                    c.name as category_name,
                    v.ville as ville_name 
                  FROM {$this->table} e
                  LEFT JOIN categories c ON e.category_id = c.id
                  LEFT JOIN ville v ON e.ville_id = v.id
                  WHERE e.organizer_id = :organizer_id";


        if ($status) {
            $query .= " AND e.status = :status";
        }


        $query .= " ORDER BY e.created_at DESC";


        $stmt = $this->db->prepare($query);


        $stmt->bindValue(':organizer_id', $organizerId, PDO::PARAM_INT);
        if ($status) {
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        }


        $stmt->execute();


        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function createEvent($data)
    {
        
        $requiredFields = ['title', 'description', 'date', 'location', 'price', 'capacity', 'organizer_id', 'category_id'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new \InvalidArgumentException("Missing required field: $field");
            }
        }

        
        if (!isset($data['status'])) {
            $data['status'] = 'draft';
        }

        return $this->create($data);
    }



    public function updateEvent($eventId, $organizerId, $data)
    {
        
        $event = $this->findById($eventId);
        if (!$event || $event['organizer_id'] != $organizerId) {
            return false;
        }

        return $this->update($eventId, $data);
    }

    public function deleteEvent($eventId, $organizerId)
    {
        
        $event = $this->findById($eventId);
        if (!$event || $event['organizer_id'] != $organizerId) {
            return false;
        }

        return $this->delete($eventId);
    }

    public function getEventStats($eventId, $organizerId)
    {
        
        $event = $this->findById($eventId);
        if (!$event || $event['organizer_id'] != $organizerId) {
            return false;
        }

        $query = "SELECT 
                    e.title,
                    e.capacity,
                    COUNT(r.id) as total_reservations,
                    SUM(CASE WHEN r.status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_reservations,
                    SUM(CASE WHEN r.status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_reservations,
                    SUM(CASE WHEN r.payment_status = 'completed' THEN 1 ELSE 0 END) as paid_reservations
                 FROM {$this->table} e
                 LEFT JOIN reservations r ON e.id = r.event_id
                 WHERE e.id = :event_id
                 GROUP BY e.id";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute(['event_id' => $eventId]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function addTagToEvent($tagId, $eventId)
    {
        $stmt = $this->db->prepare("INSERT INTO event_tags (event_id, tag_id) VALUES (:event_id, :tag_id)");
        $stmt->execute(['event_id' => $eventId, 'tag_id' => $tagId]);
    }

    public function addSponsorToEvent($sponsorId, $eventId)
    {
        $stmt = $this->db->prepare("INSERT INTO event_sponsors (event_id, sponsor_id) VALUES (:event_id, :sponsor_id)");
        $stmt->execute(['event_id' => $eventId, 'sponsor_id' => $sponsorId]);
    }

    public function getEventWithCategory($eventId)
    {
        $query = "SELECT e.*, c.name as category_name 
                 FROM {$this->table} e 
                 LEFT JOIN categories c ON e.category_id = c.id 
                 WHERE e.id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $eventId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
  
    public function countEventsByOrganizer($organizerId)
{
    $query = "SELECT COUNT(*) as event_count FROM {$this->table} WHERE organizer_id = :organizer_id";
    $stmt = $this->db->prepare($query);
    $stmt->execute(['organizer_id' => $organizerId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result['event_count']; 
}
    
}