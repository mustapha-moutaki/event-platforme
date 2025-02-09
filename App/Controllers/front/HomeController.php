<?php
namespace App\Controllers\front;

use App\core\Controller;

class HomeController extends Controller {
    public function index() {
        $this->view('home');
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
//         require __DIR__ .'/../../view/home.html.twig';
        
//     }

// }