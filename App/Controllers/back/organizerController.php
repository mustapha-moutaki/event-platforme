<?php


namespace App\Controllers\Back;


use App\core\View;
use App\Models\Organizer; 
use App\Core\Auth;
use App\Models\Events;
session_start();


class organizerController {
    public function viewOrgnaizer() {
      
      $view=new View();
      
        
      $organizerModel = new Organizer();
        $organizerId = Auth::userId();
        $events = $organizerModel->getEventsByOrganizer($organizerId);
        $eventCount = $organizerModel->countEventsByOrganizer($organizerId); 
        $notificationModel = new Events(); 
        $notifications = $notificationModel->getNotifications($organizerId);
       
        $view->render('organizer/organizer.twig', [
            'events' => $events,
            'statistique'=>  $eventCount ,
            'notifications' => $notifications
        ]);
}
}