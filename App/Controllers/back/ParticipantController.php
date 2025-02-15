<?php


namespace App\Controllers\Back;


use App\core\View;


class participantController {
    public function viewParticipant() {
      
        $view = new View();
       
        //  var_dump($cities);
        //  die();
      
        
        $view->render('participant/participant.twig');
    }
}