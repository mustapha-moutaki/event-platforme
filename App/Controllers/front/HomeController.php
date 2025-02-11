<?php
namespace App\Controllers\front;

use App\core\Controller;

class HomeController extends Controller {
    public function index() {
        $this->view('home');
        $view = new View();
        $view->render('home.twig',  ['events' => $ev]);
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