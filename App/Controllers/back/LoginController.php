<?php

namespace App\Controllers\back;

use App\core\Auth;
use App\core\View;

class LoginController {
    public function showLoginForm() {
        $view = new View();
        $view->render('login.twig');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            if (Auth::login($email, $password)) {
                header("Location: /dashboard");
                exit;
            } else {
                echo "Email ou mot de passe incorrect.";
            }
        }
    }

    public function logout() {
        Auth::logout();
        header("Location: /login");
        exit;
    }
}
