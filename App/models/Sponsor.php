<?php

namespace App\Models;

use App\Core\BaseModel;

class Sponsor extends BaseModel
{
    protected $table = 'sponsors';

    public function createSponsor($name, $imagePath)
    {
        return $this->create([
            'name' => $name,
            'image_url' => $imagePath
        ]);
    }

    public function getAllSponsors()
    {
        return $this->findAll();
    }
    public function update($id, $data) {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $query = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $data['id'] = $id;
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public function deleteSponsor($id)
{
    return $this->delete($id);
}
}
