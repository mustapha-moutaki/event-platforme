<?php
namespace App\Models;

use App\core\Database;
use App\core\BaseModel;
use PDO;
use PDOException;



class Organizer extends BaseModel
{
    protected $table = 'events';

    public function getEventsByOrganizer($organizerId, $status = null)
    {
        $conditions = ['organizer_id' => $organizerId];
        if ($status) {
            $conditions['status'] = $status;
        }
        
        return $this->findAll($conditions, 'created_at DESC');
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
  
    
    
}