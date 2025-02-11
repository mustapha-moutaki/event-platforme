<?php

namespace App\Controllers\front;
session_start();

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

    public function createForm() {
        $this->view('home');
    }

    public function showCreateForm() 
    {
        
        $db = \App\core\Database::getConnection();
        $stmt = $db->query("SELECT id, name FROM categories ORDER BY name");
        // var_dump($stmt);
        $categories = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->view->render('events/create.twig', [
            'categories' => $categories
        ]); 
    }

    public function create() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                var_dump(Auth::UserId());
                
                $eventData = [
                    'title' => htmlspecialchars($_POST['title']),
                    'description' => htmlspecialchars($_POST['description']),
                    'date' => $_POST['date'],
                    'location' => htmlspecialchars($_POST['location']),
                    'price' => floatval($_POST['price']),
                    'capacity' => intval($_POST['capacity']),
                    'category_id' => intval($_POST['category_id']),
                    'organizer_id' => Auth::UserId(),
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
        
        if ($event && $event['organizer_id'] === Auth::UserId()) {
            
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

                if ($this->organizer->updateEvent($id, Auth::UserId(), $eventData)) {
                    header("Location: /events" . $id);
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
        if ($this->organizer->deleteEvent($id, Auth::UserId())) {
            header("Location: /events");
            exit;
        } else {
            echo "Erreur lors de la suppression de l'événement.";
        }
    }

    public function listEvents() 
    {
        $events = $this->organizer->getEventsByOrganizer(Auth::UserId());
        
        $this->view->render('events/events.twig', [
            'events' => $events
        ]);
    }
}