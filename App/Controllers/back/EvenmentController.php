<?php

namespace App\Controllers\Back;
session_start();
use App\Models\Events;
use App\core\View;
use App\core\Auth;
class EvenmentController{
    public function affichageEvent(){
        $view=new View();
        $events=new Events();
        $evenements=$events->getAllEvents();


        foreach ($evenements as &$event) {
            $event['comments'] = $events->getCommentsByEventId($event['id']);
        }

        $view->render('evenement.twig',['events'=>$evenements]);
    }

    public function submitComment(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventId = $_POST['event_id'];
            $userId =Auth::UserId() ; 
            $content = $_POST['content'];
    
            $event = new Events();
            $event->addComment($eventId, $userId, $content);
    
            
            header('Location: /admin/events');
            exit();
        }
    }

    public function updateComment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commentId = $_POST['comment_id'];
            $content = $_POST['content'];
            
            $event = new Events();
            $event->updateComment($commentId, $content);
    
            header('Location: /admin/events');
            exit();
        }
    }
    
    public function deleteComment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commentId = (int) $_POST['comment_id'];
            
            
            $event = new Events();
            $event->deleteComment($commentId);
    
            header('Location: /admin/events');
            exit();
        }
    }
    
    public function updateEventStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'], $_POST['status'])) {
            $eventId = intval($_POST['event_id']);
            $newStatus = $_POST['status'];
    
            $allowedStatuses = ['draft', 'pending', 'active', 'cancelled', 'completed'];
            if (!in_array($newStatus, $allowedStatuses)) {
                die("Statut invalide !");
            }
    
            $eventModel = new Events();
            if ($eventModel->updateStatus($eventId, $newStatus)) {
                header("Location: /admin/events");
                exit;
            } else {
                die("Erreur lors de la mise à jour du statut.");
            }
        }
    }
    
    
    
}