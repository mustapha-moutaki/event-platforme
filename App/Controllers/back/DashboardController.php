<?php

namespace App\Controllers\back;

use App\core\Auth;
use App\core\View;

class DashboardController {
    public function index() {
        if (!Auth::check()) {
            header("Location: /login");
            exit;
        }

        $user = Auth::user();
        $view = new View();
        $view->render('dashboard.twig', [
            'username' => $user['username'],
            
        ]);
    }
}
