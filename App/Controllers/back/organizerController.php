<?php


namespace App\Controllers\Back;


use App\core\View;


class organizerController {
    public function viewOrgnaizer() {
      
        $view = new View();
       
        //  var_dump($cities);
        //  die();
      
        
        $view->render('organizer/organizer.twig');
    }
}