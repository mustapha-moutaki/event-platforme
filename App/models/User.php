<?php
namespace App\Models;
//  require_once __DIR__ . '/../core/Database.php';


use App\core\Database;
use PDO;
use PDOException;


class User {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getAllUsers() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM users");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function createUser($username, $email, $password) {
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $password
        ]);
    }

    public function find($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save($userId, $status) {
        $sql = "UPDATE users SET status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'status' => $status,
            'id' => $userId
        ]);
    }

public function deleteUser($userId) {
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute(['id' => $userId]);
}

public function getUserRole($userId) {
    $sql = "SELECT r.name FROM roles r 
            JOIN user_roles ur ON r.id = ur.role_id 
            WHERE ur.user_id = :user_id LIMIT 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['user_id' => $userId]);
    $role = $stmt->fetch(PDO::FETCH_ASSOC);
    return $role ? $role['name'] : null;
}

public function assignRole($userId, $roleName) {
    // Vérifier si le rôle existe
    $sql = "SELECT id FROM roles WHERE name = :role";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['role' => $roleName]);
    $role = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($role) {
        $roleId = $role['id'];

        // Vérifier si l'utilisateur a déjà un rôle
        $checkSql = "SELECT * FROM user_roles WHERE user_id = :user_id";
        $checkStmt = $this->pdo->prepare($checkSql);
        $checkStmt->execute(['user_id' => $userId]);

        if ($checkStmt->rowCount() == 0) {
            // Assigner le rôle
            $assignSql = "INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)";
            $assignStmt = $this->pdo->prepare($assignSql);
            return $assignStmt->execute(['user_id' => $userId, 'role_id' => $roleId]);
        }
    }
    return false;
}

public function getUserStatistics() {
    $statistics = [];

    
    $sql = "SELECT COUNT(*) as total_users FROM users";
    $stmt = $this->pdo->query($sql);
    $statistics['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

  
    $sql = "SELECT COUNT(*) as active_users FROM users WHERE status = 'active'";
    $stmt = $this->pdo->query($sql);
    $statistics['active_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['active_users'];

    
    $sql = "SELECT COUNT(*) as pending_users FROM users WHERE status = 'pending'";
    $stmt = $this->pdo->query($sql);
    $statistics['pending_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['pending_users'];

    
    $sql = "SELECT COUNT(*) as banned_users FROM users WHERE status = 'banned'";
    $stmt = $this->pdo->query($sql);
    $statistics['banned_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['banned_users'];

    return $statistics;
}

}
