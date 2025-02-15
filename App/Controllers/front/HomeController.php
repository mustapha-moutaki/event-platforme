<?php
namespace App\Controllers\front;

use App\core\Controller;
use App\core\View;
use App\controllers\front\EventController;

class HomeController extends Controller {
    public function index() {
      
        $view = new View();
        $events= new EventController();
        $event = $events->showAllEvents();
        $cities = $events->showAllCities();
        //  var_dump($cities);
        //  die();
       
        
        $view->render('home.twig',  ['events' => $event, 'cities' =>$cities]);
    }
    
    public function update() {
        // $this->view('home');
        $view = new View();
       
        
        $view->render('updateprofile.twig');
    }
}



// namespace App\Controllers\front;
// use App\Models\User;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// class HomeController {
//     public function index() {
//         echo "Bienvenue sur la page d'accueil !";
//         var_dump(__DIR__);
//         require __DIR__ .'/../../view/home.html';
        
//     }

// }