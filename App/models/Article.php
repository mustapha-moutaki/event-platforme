<?php
namespace App\Models;
use App\core\Database;
use PDO;

class Article {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection(); // Connexion à la base de données
    }

    // Insérer un article
    public function insertArticle($title, $content, $userId) {
        $sql = "INSERT INTO articles (title, content, user_id) VALUES (:title, :content, :user_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'title' => $title,
            'content' => $content,
            'user_id' => $userId
        ]);
    }

    // Récupérer tous les articles
    public function getArticles() {
        $sql = "SELECT a.id, a.title, a.content, u.username 
                FROM articles a 
                JOIN users u ON a.user_id = u.id 
                ORDER BY a.id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
