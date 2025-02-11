<?php
// <!-- adding new file -->
namespace App\core;

class Controller {
    public function view($view, $data = []) {
        extract($data);
        require __DIR__ .'/../view/home.twig';
    }
}
