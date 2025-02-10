<?php

namespace App\Controllers\front;

use App\core\View;
use App\Models\Organizer;
use App\core\Auth;

class EventController 
{
    private $organizer;
    private $view;

    public function __construct() 
    {
        $this->organizer = new Organizer();
        $this->view = new View();
    }

    public function showCreateForm() 
    {
        // Get categories for the form
        $db = \App\Core\Database::getConnection();
        $stmt = $db->query("SELECT id, name FROM categories ORDER BY name");
        $categories = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->view->render('events/create.twig', [
            'categories' => $categories
        ]);
    }

    public function create() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Prepare event data
                $eventData = [
                    'title' => htmlspecialchars($_POST['title']),
                    'description' => htmlspecialchars($_POST['description']),
                    'date' => $_POST['date'],
                    'location' => htmlspecialchars($_POST['location']),
                    'price' => floatval($_POST['price']),
                    'capacity' => intval($_POST['capacity']),
                    'category_id' => intval($_POST['category_id']),
                    'organizer_id' => Auth::getUserId(),
                    'status' => 'draft'
                ];

                $eventId = $this->organizer->createEvent($eventData);

                if ($eventId) {
                    header("Location: /events/show/" . $eventId);
                    exit;
                } else {
                    echo "Erreur lors de la création de l'événement.";
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    public function show($id) 
    {
        $event = $this->organizer->findById($id);
        
        if ($event) {
            $this->view->render('events/show.twig', [
                'event' => $event
            ]);
        } else {
            header("Location: /events");
            exit;
        }
    }

    public function showEditForm($id) 
    {
        $event = $this->organizer->findById($id);
        
        if ($event && $event['organizer_id'] === Auth::getUserId()) {
            // Get categories for the form
            $db = \App\Core\Database::getConnection();
            $stmt = $db->query("SELECT id, name FROM categories ORDER BY name");
            $categories = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $this->view->render('events/edit.twig', [
                'event' => $event,
                'categories' => $categories
            ]);
        } else {
            header("Location: /events");
            exit;
        }
    }

    public function edit($id) 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $eventData = [
                    'title' => htmlspecialchars($_POST['title']),
                    'description' => htmlspecialchars($_POST['description']),
                    'date' => $_POST['date'],
                    'location' => htmlspecialchars($_POST['location']),
                    'price' => floatval($_POST['price']),
                    'capacity' => intval($_POST['capacity']),
                    'category_id' => intval($_POST['category_id'])
                ];

                if ($this->organizer->updateEvent($id, Auth::getUserId(), $eventData)) {
                    header("Location: /events/show/" . $id);
                    exit;
                } else {
                    echo "Erreur lors de la modification de l'événement.";
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    public function delete($id) 
    {
        if ($this->organizer->deleteEvent($id, Auth::getUserId())) {
            header("Location: /events");
            exit;
        } else {
            echo "Erreur lors de la suppression de l'événement.";
        }
    }

    public function listEvents() 
    {
        $events = $this->organizer->getEventsByOrganizer(Auth::getUserId());
        
        $this->view->render('events/list.twig', [
            'events' => $events
        ]);
    }
}