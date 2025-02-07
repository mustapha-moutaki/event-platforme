<?php

namespace App\core;

class Security {
   
    
    public static function sanitizeInput($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    
    public static function sanitizeSQL($pdo, $data) {
        return $pdo->quote($data);
    }

   
    public static function generateCSRFToken() {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    
    public static function validateCSRFToken($token) {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            die("Erreur CSRF : Requête invalide.");
        }
    }

    
    public static function secureHeaders() {
        header("X-Frame-Options: DENY"); 
        header("X-XSS-Protection: 1; mode=block");
        header("X-Content-Type-Options: nosniff"); 
        header("Referrer-Policy: no-referrer-when-downgrade");
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
    }
}
