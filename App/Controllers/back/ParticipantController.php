<?php


namespace App\Controllers\Back;


use App\core\View;
use App\controllers\front\EventController;


class participantController {
    public function viewParticipant() {
      
        $view = new View();
        $events= new EventController();
        $event = $events->showAllEvents();
        $cities = $events->showAllCities();
        //  var_dump($cities);
        //  die();
      
        
        $view->render('participant/participant.twig', ['events' => $event, 'cities' =>$cities]);
    }
}