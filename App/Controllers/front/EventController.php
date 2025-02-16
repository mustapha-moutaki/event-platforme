<?php

namespace App\Controllers\Front;

session_start();

use App\Core\View;
use App\Models\Event;
use App\Models\Organizer; 
use App\Core\Auth;
use App\Core\Controller;
use App\Models\Events;

class EventController extends Controller
{
    private $eventModel;
    private $organizerModel;
    private $view;

    public function __construct() 
    {
        $this->eventModel = new Event();
        $this->organizerModel = new Organizer();
        $this->view = new View();
    }

    public function showCreateForm() 
    {
        $db = \App\Core\Database::getConnection();
        $stmt = $db->query("SELECT id, name FROM categories ORDER BY name");
        $categories = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        
        $stmt = $db->query("SELECT id, region FROM region ORDER BY region");
        $regions = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
        
        $stmt = $db->query("SELECT id, name FROM tags ORDER BY name");
        $tags = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        
        $stmt = $db->query("SELECT id, name FROM sponsors ORDER BY name");
        $sponsors = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $this->view->render('events/create.twig', [
            'categories' => $categories,
            'regions' => $regions, 
            'tags' => $tags,
            'sponsors' => $sponsors
        ]); 
    }

    public function create() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST["title"] ?? '';
    
            try {
                // Handle image upload
                if (!empty($_FILES["image"]["name"])) {
                    $uploadDir = __DIR__ . '/../../../public/uploads/';
                    $fileName = time() . '_' . basename($_FILES["image"]["name"]);
                    $uploadFile = $uploadDir . $fileName;
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $uploadFile)) {
                        $imagePath = '/uploads/' . $fileName;
                    } else {
                        echo "Erreur lors de l'upload de l'image.";
                        return;
                    }
                } else {
                    echo "Veuillez choisir une image.";
                    return;
                }
    
                // Collect event data from POST, including new fields event_type and price_type
                $eventData = [
                    'title' => htmlspecialchars($_POST['title']),
                    'description' => htmlspecialchars($_POST['description']),
                    'date' => $_POST['date'],
                    'price' => floatval($_POST['price']),
                    'capacity' => intval($_POST['capacity']),
                    'category_id' => intval($_POST['category_id']),
                    'organizer_id' => Auth::UserId(),
                    'status' => 'draft',  // Assuming a default status
                    'image' => $imagePath,
                    'region_id' => intval($_POST['region_id']),
                    'ville_id' => intval($_POST['ville_id']),
                    'event_type' => $_POST['event_type'],  // New field
                    'price_type' => $_POST['price_type'],  // New field
                ];
    
                // Add the URL only if it's a virtual event
                if ($_POST['event_type'] === 'virtual' && isset($_POST['url'])) {
                    $eventData['url'] = $_POST['url'];
                }
    
                // Create the event and get the event ID
                $eventId = $this->eventModel->createEvent($eventData);
    
                // Handle tags if provided
                if (!empty($_POST['tags'])) {
                    foreach ($_POST['tags'] as $tagId) {
                        $this->eventModel->addTagToEvent($tagId, $eventId);
                    }
                }
    
                // Handle sponsors if provided
                if (!empty($_POST['sponsors'])) {
                    foreach ($_POST['sponsors'] as $sponsorId) {
                        $this->eventModel->addSponsorToEvent($sponsorId, $eventId);
                    }
                }
    
                // Redirect or show an error if event creation failed
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
        $event = $this->eventModel->findById($id);
        
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
        $event = $this->eventModel->findById($id);
    
        if ($event && $event['organizer_id'] === Auth::UserId()) {
            $db = \App\Core\Database::getConnection();
            $stmt = $db->query("SELECT id, name FROM categories ORDER BY name");
            $categories = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
            
            $stmt = $db->prepare("SELECT t.id, t.name FROM tags t 
                                   JOIN event_tags et ON t.id = et.tag_id 
                                   WHERE et.event_id = :event_id");
            $stmt->execute(['event_id' => $id]);
            $eventTags = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $eventTagIds = array_column($eventTags, 'id'); 

            $stmt = $db->query("SELECT id, region FROM region ORDER BY region");
            $regions = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
            
            $stmt = $db->prepare("SELECT s.id, s.name FROM sponsors s 
                                   JOIN event_sponsors es ON s.id = es.sponsor_id 
                                   WHERE es.event_id = :event_id");
            $stmt->execute(['event_id' => $id]);
            $eventSponsors = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $eventSponsorIds = array_column($eventSponsors, 'id'); 
    
            
            $stmt = $db->query("SELECT id, name FROM tags ORDER BY name");
            $tags = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
            $stmt = $db->query("SELECT id, name FROM sponsors ORDER BY name");
            $sponsors = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
            $this->view->render('events/edit.twig', [
                'event' => $event,
                'categories' => $categories,
                'tags' => $tags,
                'sponsors' => $sponsors,
                'eventTagIds' => $eventTagIds, 
                'eventSponsorIds' => $eventSponsorIds,
                'regions' => $regions
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
                // Handle the form data
                $eventData = [
                    'title' => htmlspecialchars($_POST['title']),
                    'description' => htmlspecialchars($_POST['description']),
                    'date' => $_POST['date'],
                    'price' => floatval($_POST['price']),
                    'capacity' => intval($_POST['capacity']),
                    'category_id' => intval($_POST['category_id']),
                    'organizer_id' => Auth::UserId(),
                    'status' => 'draft',
                    'region_id' => intval($_POST['region_id']),
                    'ville_id' => intval($_POST['ville_id']),
                    'event_type' => $_POST['event_type'], // Added to handle event type
                    'url' => ($_POST['event_type'] == 'virtual' && !empty($_POST['url'])) ? $_POST['url'] : null, // Added to handle URL for virtual events
                ];
    
                // Handle image upload
                if (!empty($_FILES['image']['name'])) {
                    $uploadDir = __DIR__ . '/../../../public/uploads/';
                    $imageName = basename($_FILES['image']['name']);
                    $imagePath = $uploadDir . uniqid() . '_' . $imageName;
    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                        $eventData['image'] = '/uploads/' . basename($imagePath); 
                    } else {
                        throw new \Exception('Failed to upload image.');
                    }
                }
    
                // Update event in the database
                if ($this->eventModel->updateEvent($id, Auth::UserId(), $eventData)) {
                    // Clear and add tags and sponsors
                    $this->eventModel->clearTags($id);
                    $this->eventModel->clearSponsors($id);
    
                    if (!empty($_POST['tags'])) {
                        foreach ($_POST['tags'] as $tagId) {
                            $this->eventModel->addTagToEvent($tagId, $id);
                        }
                    }
    
                    if (!empty($_POST['sponsors'])) {
                        foreach ($_POST['sponsors'] as $sponsorId) {
                            $this->eventModel->addSponsorToEvent($sponsorId, $id);
                        }
                    }
    
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
        
        $event = $this->eventModel->findById($id);
        if ($event && $event['organizer_id'] === Auth::UserId()) {
            
            $imagePath = __DIR__ . '/../../../public' . $event['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            if ($this->eventModel->deleteEvent($id, Auth::UserId())) {
                header("Location: /events");
                exit;
            } else {
                echo "Erreur lors de la suppression de l'événement.";
            }
        } else {
            echo "Erreur: Événement non trouvé ou accès non autorisé.";
        }
    }

    public function getCitiesByRegion($regionId) 
    {
        $db = \App\Core\Database::getConnection();
        $stmt = $db->prepare("SELECT id, ville FROM ville WHERE region = :region_id");
        $stmt->execute(['region_id' => $regionId]);
        $cities = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        // var_dump($cities);
        header('Content-Type: application/json');
        echo json_encode($cities); 
        exit;
    }

    public function listEvents() 
    {
       
        $events = $this->organizerModel->getEventsByOrganizer(Auth::UserId());

        
        $this->view->render('events/events.twig', [
            'events' => $events
        ]);
      
    }

    public function showAllEvents() 
    {
        $events = $this->eventModel->getAllEvents(); 
        return $events;

        
    }

    // public function showEventDetails($id) 
    // {
        
    //     $event = $this->eventModel->findById($id);
        
    //     if ($event) {
            
    //         $db = \App\Core\Database::getConnection();
    
            
    //         $stmt = $db->prepare("SELECT ville FROM ville WHERE id = :ville_id");
    //         $stmt->execute(['ville_id' => $event['ville_id']]);
    //         $city = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            
    //         $stmt = $db->prepare("SELECT region FROM region WHERE id = :region_id");
    //         $stmt->execute(['region_id' => $event['region_id']]);
    //         $region = $stmt->fetch(\PDO::FETCH_ASSOC);
            
    //         $stmt = $db->prepare("SELECT username FROM users WHERE id = :organizer_id");
    //         $stmt->execute(['organizer_id' => $event['organizer_id']]);
    //         $organizer = $stmt->fetch(\PDO::FETCH_ASSOC);
            
    //         $this->view->render('participant/eventDetails.twig', [
    //             'event' => $event,
    //             'category_name' => $event['category_name'], 
    //             'city' => $city['ville'] ?? 'N/A',  
    //             'region' => $region['region'] ?? 'N/A', 
    //             'organizer' => $organizer['username'] ?? 'N/A', 

    public function showAllCities(){
        $city = $this->eventModel->getAllCities();
        return $city;
    }

    public function showEventDetails($id) 
    {
    
        $events=new Events();
        $event = $events->getAllEvents($id);

        $db = \App\Core\Database::getConnection();
    
            
        $stmt = $db->prepare("SELECT ville FROM ville WHERE id = :ville_id");
        $stmt->execute(['ville_id' => $event['ville_id']]);
        $city = $stmt->fetch(\PDO::FETCH_ASSOC);

        
        $stmt = $db->prepare("SELECT region FROM region WHERE id = :region_id");
        $stmt->execute(['region_id' => $event['region_id']]);
        $region = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        $stmt = $db->prepare("SELECT username FROM users WHERE id = :organizer_id");
        $stmt->execute(['organizer_id' => $event['organizer_id']]);
        $organizer = $stmt->fetch(\PDO::FETCH_ASSOC);
       
        if ($event) {
          
               
                $comments = $events->getCommentsByEventId($id);
                
            $this->view->render('participant/eventDetails.twig', [
                'event' => $event,
                'comments'=>$comments, 
                'auth' => ['user' => ['id' => Auth::UserId()]],
                'event' => $event,
                'category_name' => $event['category_name'], 
                'city' => $city['ville'] ?? 'N/A',  
                'region' => $region['region'] ?? 'N/A', 
                'organizer' => $organizer['username'] ?? 'N/A',
                
            ]);
        } else {
            header("Location: /");
            exit;
        }
    }
    

    public function showAllCategories(){
        $category = $this->eventModel->getAllCategories();
        return $category;
    }

    public function reportComment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commentId = $_POST['comment_id'];
            $reason = $_POST['reason'];
            $reporterId = Auth::UserId(); 
    
            $event = new Events();
            
            
            $commentAuthorId = $event->getUserIdByCommentId($commentId);
            
            if ($commentAuthorId === $reporterId) {
                $_SESSION['error'] = "Vous ne pouvez pas signaler votre propre commentaire.";
                header('Location: /events/details/' . $event->getEventIdByCommentId($commentId));
                exit();
            }
            
        
            $success = $event->reportComment($commentId, $reporterId, $reason);
            
            if ($success) {
                $_SESSION['message'] = "Le commentaire a été signalé avec succès.";
            } else {
                $_SESSION['error'] = "Une erreur s'est produite lors du signalement du commentaire.";
            }
            
           
            $eventId = $event->getEventIdByCommentId($commentId);
            header('Location: /events/details/' . $eventId);
            exit();
        }
    }


}




