<?php

namespace App\Models;

use App\Core\Database;
use App\core\BaseModel;
use PDO;
use PDOException;

class Event extends BaseModel
{
    protected $table = 'events';

    public function createEvent($data)
    {
        $requiredFields = ['title', 'description', 'date', 'price', 'capacity', 'category_id', 'organizer_id', 'image', 'region_id', 'ville_id'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new \InvalidArgumentException("Missing required field: $field");
            }
        }
    
        // Add 'url' field if it exists in $data
        $query = "INSERT INTO {$this->table} (title, description, date, price, capacity, category_id, organizer_id, status, image, region_id, ville_id, event_type, price_type, url) 
                  VALUES (:title, :description, :date, :price, :capacity, :category_id, :organizer_id, :status, :image, :region_id, :ville_id, :event_type, :price_type, :url)";
    
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'date' => $data['date'],
            'price' => $data['price'],
            'capacity' => $data['capacity'],
            'category_id' => $data['category_id'],
            'organizer_id' => $data['organizer_id'],
            'status' => $data['status'],
            'image' => $data['image'],
            'region_id' => $data['region_id'],
            'ville_id' => $data['ville_id'],
            'event_type' => $data['event_type'],  // New field
            'price_type' => $data['price_type'],  // New field
            'url' => $data['url'] ?? null, // New field, default to null if not set
        ]);
    
        return $this->db->lastInsertId();
    }
    
    

    public function updateEvent($id, $organizerId, $data)
    {
        $event = $this->findById($id);
        if (!$event || $event['organizer_id'] != $organizerId) {
            return false; 
        }
    
        
        $query = "UPDATE {$this->table} SET 
                  title = :title, 
                  description = :description, 
                  date = :date, 
                
                  price = :price, 
                  capacity = :capacity, 
                  category_id = :category_id, 
                  region_id = :region_id, 
                  ville_id = :ville_id" .
                  (isset($data['image']) ? ", image = :image" : "") . 
                  " WHERE id = :id";
    
        $stmt = $this->db->prepare($query);
    
        // Bind parameters
        $params = [
            'title' => $data['title'],
            'description' => $data['description'],
            'date' => $data['date'],
            'price' => $data['price'],
            'capacity' => $data['capacity'],
            'category_id' => $data['category_id'],
            'region_id' => $data['region_id'],
            'ville_id' => $data['ville_id'],
            'id' => $id,
        ];
    
        // Include image if it's set
        if (isset($data['image'])) {
            $params['image'] = $data['image'];
        }
    
    
        return $stmt->execute($params);
    }
    

    public function deleteEvent($id, $organizerId)
    {
        $event = $this->findById($id);
        if (!$event || $event['organizer_id'] != $organizerId) {
            return false;
        }

        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    public function addTagToEvent($tagId, $eventId)
    {
        $stmt = $this->db->prepare("INSERT INTO event_tags (event_id, tag_id) VALUES (:event_id, :tag_id)");
        $stmt->execute(['event_id' => $eventId, 'tag_id' => $tagId]);
    }



    // public function getCitiesByRegion($regionId) {
    //     $query = "SELECT id, ville as name FROM ville WHERE region = :region_id ORDER BY ville";
    //     $stmt = $this->db->prepare($query);
    //     $stmt->execute(['region_id' => $regionId]);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    public function clearTags($eventId)
    {
        $stmt = $this->db->prepare("DELETE FROM event_tags WHERE event_id = :event_id");
        $stmt->execute(['event_id' => $eventId]);
    }

    public function addSponsorToEvent($sponsorId, $eventId)
    {
        $stmt = $this->db->prepare("INSERT INTO event_sponsors (event_id, sponsor_id) VALUES (:event_id, :sponsor_id)");
        $stmt->execute(['event_id' => $eventId, 'sponsor_id' => $sponsorId]);
    }

    public function clearSponsors($eventId)
    {
        $stmt = $this->db->prepare("DELETE FROM event_sponsors WHERE event_id = :event_id");
        $stmt->execute(['event_id' => $eventId]);
    }
    
    public function getAllEvents()
    {
        $query = "SELECT e.*, c.name AS category_name 
                FROM {$this->table} e 
                JOIN categories c ON e.category_id = c.id 
                WHERE e.status = 'active' 
                ORDER BY e.date ASC"; 

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }


    public function getAllCities(){
        $query="SELECT ville FROM ville";
        $stmt= $this->db->prepare($query);
        $stmt->execute();
        return $stmt -> fetchAll(PDO::FETCH_ASSOC);
    }
        
}
