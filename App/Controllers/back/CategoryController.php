<?php

namespace App\Controllers\Back;


use App\Models\Category;
use App\core\View;
use App\core\Session;
session_start();
class CategoryController {
    
    public function listCategories() {
        $role = Session::get('user_role');
        if ( $role !== 'admin') {
            header("Location: /login");
            exit;
        }
     $view=new View();
     $categorie=new Category();
     $categories =  $categorie->findAll();
     $totalCategorie=$categorie->countcategories();
    //  var_dump($categories);
     $view->render('categories/categories.twig', ['categories' => $categories
    ,'totalCategories'=>$totalCategorie]);

    }

    public function createCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
            $categorie=new Category();
            $categorie->create(['name' => $_POST['name']]);
        }
        header('Location: /admin/categories');
        exit;
    }
    public function deleteCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
            $categorie=new Category();
            $categorie->delete($_POST['id']);
        }
        header('Location: /admin/categories');
    }

    public function updateCategory()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id']) && !empty($_POST['name'])) {
        $categorie = new Category();
        $categorie->update($_POST['id'], ['name' => $_POST['name']]);
    }
    header('Location: /admin/categories');
    exit;
}
public function editCategory()
{
    if (!isset($_GET['id'])) {
        die("ID de catégorie manquant !");
    }

    $categorie = new Category();
    $category = $categorie->findById($_GET['id']);

    if (!$category) {
        die("Catégorie non trouvée !");
    }

    $view = new View();
    $view->render('categories/edit_category.twig', ['category' => $category]);
}

}


