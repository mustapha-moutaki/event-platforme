<?php

namespace App\Controllers\back;

use App\Models\User;
use App\core\View;

class UserController {
    public function listUsers() {
        $userModel = new User();
        $users = $userModel->getAllUsers();
        $statistics = $userModel->getUserStatistics();
        $view = new View();
        $view->render('users.twig', ['users' => $users,
    'statistics' => $statistics]);
        
    }

    public function updateUserStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            $status = $_POST['status'] ?? null;

            if ($userId && $status) {
                $userModel = new User();
                $user = $userModel->find($userId);

                if ($user) {
                    $userModel->save($userId, $status);
                }
            }
        }
        header('Location: /admin/users');
        exit();
    }
    
    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            
            if ($userId) {
                $userModel = new User();
                $userModel->deleteUser($userId);
            }
        }
        header('Location: /admin/users');
        exit();
    }
}



