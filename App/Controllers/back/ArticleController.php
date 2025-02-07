<?php
namespace App\Controllers\back;

use App\Models\Article;
use App\core\Auth;
use App\core\View;
Session_start();

class ArticleController {
    public function showForm() {
         $view = new View();
        echo  $view->render('articles/form.twig');
    }

    public function insertArticle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $title = trim($_POST['title']);
            $content = trim($_POST['content']);
            $userId = Auth::userId(); 
            var_dump( $userId);

            if (!$userId) {
                die("Erreur : utilisateur non connecté !");
            }

            $articleModel = new Article();
            if ($articleModel->insertArticle($title, $content, $userId)) {
                header("Location: /articles");
                exit;
            } else {
                die("Erreur lors de l'ajout de l'article.");
            }
        }
    }

    public function listArticles() {
        $articleModel = new Article();
        $articles = $articleModel->getArticles();
        $view = new View();
        echo  $view->render('articles/list.twig', ['articles' => $articles]);
    }
}
