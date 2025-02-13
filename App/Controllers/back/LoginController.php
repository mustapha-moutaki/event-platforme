<?php

namespace App\Controllers\back;

use App\core\Auth;
use App\core\View;
use App\core\Session;
session_start();


class LoginController {
    public function showLoginForm() {
        $view = new View();
        $view->render('login.html.twig');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            if (Auth::login($email, $password)) {
                $role = Session::get('user_role');
                if (!$role) {
                    header("Location: /choose-role"); 
                    exit;
                } elseif ($role === 'admin') {
                    header("Location: /admin/dashboard");
                    exit;
                } elseif ($role === 'organizer') {
                    header("Location: /organizer/dashboard");
                    exit;
                } elseif ($role === 'participant') {
                    header("Location: /participant/home");
                    exit;
                }
                // header("Location: /dashboard");
                // exit;
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
