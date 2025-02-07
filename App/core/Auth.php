<?php

namespace App\core;

use App\Models\User;

class Auth {
    public static function login($email, $password) {
        $userModel = new User();
        $user = $userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            Session::start();
            Session::set('user_id', $user['id']);
            Session::set('username', $user['username']);
            return true;
        }
        return false;
    }

    public static function check() {
        Session::start();
        return Session::has('user_id');
    }

    public static function logout() {
        Session::start();
        Session::destroy();
    }

    public static function user() {
        Session::start();
        return [
            'id' => Session::get('user_id'),
            'username' => Session::get('username')
        ];
    }
    public static function userId() {
        return $_SESSION['user_id'] ?? null; 
    }
}
