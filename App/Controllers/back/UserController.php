<?php

namespace App\Controllers\back;

use App\Models\User;
use App\core\View;
use App\core\Session;
session_start();
class UserController {
    public function listUsers() {
        $role = Session::get('user_role');
        if ( $role !== 'admin') {
            header("Location: /login");
            exit;
        }
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
//new addition
    // public function updateProfile() {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $userId = $_POST['user_id'] ?? null;
            
    //         if ($userId) {
    //             $userModel = new User();
    //             $userModel->updateUser($userId);
    //         }
    //     }
    //     header('Location: /admin/updateprofile');
    //     exit();
    // }

}



