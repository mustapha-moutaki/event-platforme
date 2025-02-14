<?php
namespace App\Controllers\back;

use App\core\View;
use App\Models\User;
use App\core\Session;
session_start();

class ChooseRoleController {
    public function showRoleSelection() {
        $view = new View();
        $view->render('choose_role.twig'); 
    }

    public function setRole() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) {
            $userId = Session::get('user_id');
            $role = $_POST['role'];

            $userModel = new User();
           $userModel->assignRole($userId, $role);
    //   var_dump($a);
    //   die;

            Session::set('user_role', $role);
            if ($role === 'admin') {
                header("Location: /admin/dashboard");
            } elseif ($role === 'organizer') {
                header("Location: /admin/tags");
            } elseif ($role === 'participant') {
                header("Location: / ");
            }
            exit;
        }
    }

    public function switchRole() {
        $userId = Session::get('user_id');
        
        if (!$userId) {
            header("Location: /login"); 
            exit;
        }

        $userModel = new User();
        $newRole = $userModel->switchRole($userId);

        if ($newRole) {
            Session::set('user_role', $newRole);
            if ($newRole === 'organizer') {
                header("Location: /admin/tags");
            } else {
                header("Location: /");
            }
            exit;
        }

        // En cas d'erreur
        header("Location: /dashboard?error=role_switch_failed");
        exit;
    }
}
