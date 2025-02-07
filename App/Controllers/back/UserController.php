<?php

namespace App\Controllers\back;

use App\Models\User;
use App\core\View;

class UserController {
    public function listUsers() {
        $userModel = new User();
        $users = $userModel->getAllUsers();

        $view = new View();
        $view->render('users.twig', ['users' => $users]);
        
    }
}
