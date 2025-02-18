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
      $notificationModel = new Events();
        $organizerId = Auth::userId();
        $events = $organizerModel->getEventsByOrganizer($organizerId);
        $eventCount = $organizerModel->countEventsByOrganizer($organizerId); 
        $commentsData=$organizerModel->countCommentsByOrganizer($organizerId);
        $notifications = $notificationModel->getNotifications($organizerId);
        $totalRevenue = $organizerModel->getTotalRevenueByOrganizer($organizerId);
        $activeEventCount = $organizerModel->countActiveEventsByOrganizer($organizerId);
       
        $view->render('organizer/organizer.twig', [
            'events' => $events,
            'statistique'=>  $eventCount ,
            'notifications' => $notifications,
            'commentsData' => $commentsData,  
            'totalRevenue' => $totalRevenue,
            'activeEventCount' => $activeEventCount

        ]);
}
}