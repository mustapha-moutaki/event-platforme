<?php

namespace App\Controllers\Back;

use App\Models\Tag;
use App\core\View;

class TagController {
    
    public function listTags() {
        $view = new View();
        $tag = new Tag();
        $tags = $tag->findAll();
        $view->render('tags/tags.twig', ['tags' => $tags]);
    }

    public function createTag() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
            $tag = new Tag();
            $tag->create(['name' => $_POST['name']]);
        }
        header('Location: /admin/tags');
        exit;
    }

    public function deleteTag()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
            $tag = new Tag();
            $tag->delete($_POST['id']);
        }
        header('Location: /admin/tags');
    }

    public function updateTag()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id']) && !empty($_POST['name'])) {
            $tag = new Tag();
            $tag->update($_POST['id'], ['name' => $_POST['name']]);
        }
        header('Location: /admin/tags');
        exit;
    }

    public function editTag()
    {
        if (!isset($_GET['id'])) {
            die("ID de tag manquant !");
        }

        $tag = new Tag();
        $tagData = $tag->findById($_GET['id']);

        if (!$tagData) {
            die("Tag non trouvé !");
        }

        $view = new View();
        $view->render('tags/edit_tag.twig', ['tag' => $tagData]);
    }
}
