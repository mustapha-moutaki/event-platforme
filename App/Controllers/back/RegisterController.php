<?php

namespace App\Controllers\back;

use App\core\Auth;
use App\core\View;
use App\Models\User;
use App\core\Validator;
use App\core\Security;
session_start();

class RegisterController {
    public function showRegisterForm() {
        Security::generateCSRFToken();
        $view = new View();
        $view->render('signup.twig', ['csrf_token' => $_SESSION['csrf_token']]);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             Security::validateCSRFToken($_POST['csrf_token']); 
            $validator = new Validator();
            $rules = [
                'username' => ['required', 'min:3', 'max:20'],
                'email' => ['required', 'email'],
                'password' => ['required', 'password']
            ];

            if (!$validator->validate($_POST, $rules)) {
                $errors = $validator->getErrors();
                $view = new View();
                $view->render('signup.twig', ['errors' => $errors, 'old' => $_POST]);
                return;
            }

            $username = Security::sanitizeInput($_POST['username']);
            $email = Security::sanitizeInput($_POST['email']);
            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
           

            $userModel = new User();
            if ($userModel->createUser($username, $email, $hashedPassword)) {
                header("Location: /login");
                exit;
            } else {
                echo "Erreur lors de l'inscription.";
            }
        }
    }
}
