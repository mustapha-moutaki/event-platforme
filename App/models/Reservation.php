<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Reservation
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }


    public function createReservation($data)
    {
        $query = "INSERT INTO reservations (event_id, participant_id, quantity, status, payment_status) 
                  VALUES (:event_id, :participant_id, :quantity, :status, :payment_status)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'event_id' => $data['event_id'],
            'participant_id' => $data['participant_id'],
            'quantity' => $data['quantity'],
            'status' => $data['status'],
            'payment_status' => $data['payment_status'],
        ]);

        return $this->db->lastInsertId();
    }


    public function getReservedTicketsCount($eventId)
    {
        $query = "SELECT SUM(quantity) AS total FROM reservations WHERE event_id = :event_id AND status = 'confirmed'";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['event_id' => $eventId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }


    public function getReservationsByParticipant($participantId)
    {
        $query = "SELECT r.*, e.title AS event_title, e.date AS event_date 
                  FROM reservations r 
                  JOIN events e ON r.event_id = e.id 
                  WHERE r.participant_id = :participant_id 
                  ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['participant_id' => $participantId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function findById($reservationId)
    {
        $query = "SELECT * FROM reservations WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $reservationId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function cancelReservation($reservationId)
    {
        $query = "UPDATE reservations SET status = 'cancelled' WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $reservationId]);
    }
}