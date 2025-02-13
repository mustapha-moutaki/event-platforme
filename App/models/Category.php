<?php

namespace App\Models;

use App\Core\BaseModel;

class Category extends BaseModel {
    protected $table = "categories";

    public function __construct()
    {
        parent::__construct();
    }

    public function findByName($name) {
        return $this->findAll(['name' => $name]);
    }

    public function countcategories() {
        return $this->count(); 
    }
}

