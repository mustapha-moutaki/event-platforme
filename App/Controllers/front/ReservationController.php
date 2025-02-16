<?php

namespace App\Controllers\Front;

session_start();

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Reservation;
use App\Models\Event;
use App\Core\Database;
use App\Core\View;


class ReservationController extends Controller
{
    private $reservationModel;
    private $eventModel;
    private $view;

    public function __construct()
    {
        $this->reservationModel = new Reservation();
        $this->eventModel = new Event();
        $this->view = new View();
    }


    public function showReservationForm($eventId)
    {
        echo "Event ID: " . $eventId;
        
        if (!Auth::userId()) {
            header("Location: /login");
            exit;
        }
    
        // Fetch the event details
        $event = $this->eventModel->findById($eventId);
    
        if (!$event) {
            header("Location: /");
            exit;
        }
    
        $this->view->render('reservations/reserve.twig', [
            'event' => $event,
        ]);
    }


    public function reserveTicket()
    {
        // Ensure the user is authenticated
        if (!Auth::userId()) {
            header("Location: /login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventId = $_POST['event_id'];
            $participantId = Auth::UserId();
            $quantity = intval($_POST['quantity']);

            // Validate inputs
            if ($eventId <= 0 || $quantity <= 0) {
                $_SESSION['error'] = "Invalid input data.";
                header("Location: /events/details/" . $eventId);
                exit;
            }

            // Fetch the event to check availability
            $event = $this->eventModel->findById($eventId);

            if (!$event) {
                $_SESSION['error'] = "Event not found.";
                header("Location: /");
                exit;
            }

            // Check if there are enough available spots
            $reservedTickets = $this->reservationModel->getReservedTicketsCount($eventId);
            $availableSpots = $event['capacity'] - $reservedTickets;

            if ($quantity > $availableSpots) {
                $_SESSION['error'] = "Not enough available spots.";
                header("Location: /events/details/" . $eventId);
                exit;
            }

            // Create the reservation
            try {
                $reservationId = $this->reservationModel->createReservation([
                    'event_id' => $eventId,
                    'participant_id' => $participantId,
                    'quantity' => $quantity,
                    'status' => 'confirmed', // Default status
                    'payment_status' => 'pending', // Default payment status
                ]);

                if ($reservationId) {
                    $_SESSION['success'] = "Reservation successful!";
                    header("Location: /events/details/" . $eventId);
                    exit;
                } else {
                    $_SESSION['error'] = "Failed to create reservation.";
                    header("Location: /events/details/" . $eventId);
                    exit;
                }
            } catch (\Exception $e) {
                $_SESSION['error'] = "An error occurred: " . $e->getMessage();
                header("Location: /events/details/" . $eventId);
                exit;
            }
        } else {
            header("Location: /");
            exit;
        }
    }


    public function showUserReservations()
    {
        // Ensure the user is authenticated
        if (!Auth::userId()) {
            header("Location: /login");
            exit;
        }

        $participantId = Auth::UserId();
        $reservations = $this->reservationModel->getReservationsByParticipant($participantId);

        $this->view->render('reservations/userReservations.twig', [
            'reservations' => $reservations,
        ]);
    }


    public function cancelReservation($reservationId)
    {
        // Ensure the user is authenticated
        if (!Auth::userId()) {
            header("Location: /login");
            exit;
        }

        $participantId = Auth::UserId();

        // Check if the reservation belongs to the user
        $reservation = $this->reservationModel->findById($reservationId);

        if (!$reservation || $reservation['participant_id'] !== $participantId) {
            $_SESSION['error'] = "Reservation not found or access denied.";
            header("Location: /reservations");
            exit;
        }

        // Cancel the reservation
        if ($this->reservationModel->cancelReservation($reservationId)) {
            $_SESSION['success'] = "Reservation canceled successfully.";
        } else {
            $_SESSION['error'] = "Failed to cancel reservation.";
        }

        header("Location: /reservations");
        exit;
    }
}