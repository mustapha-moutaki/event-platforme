<?php

namespace App\Models;

use App\Core\BaseModel;

class Tag extends BaseModel {
    protected $table = "tags";

    public function __construct()
    {
        parent::__construct();
    }

    public function findByName($name) {
        return $this->findAll(['name' => $name]);
    }
}
