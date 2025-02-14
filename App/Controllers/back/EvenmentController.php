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
    
  
    
    
}