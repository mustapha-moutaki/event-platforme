<?php
namespace App\Core;

use App\core\Database;
use PDO;

abstract class BaseModel
{

    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll($conditions = [], $orderBy = null, $limit = null, $offset = null)
    {
        $query = "SELECT * FROM {$this->table}";
        
        if (!empty($conditions)) {
            $query .= " WHERE ";
            $whereConditions = [];
            foreach ($conditions as $key => $value) {
                $whereConditions[] = "$key = :$key";
            }
            $query .= implode(' AND ', $whereConditions);
        }

        if ($orderBy) {
            $query .= " ORDER BY $orderBy";
        }

        if ($limit) {
            $query .= " LIMIT :limit";
            if ($offset) {
                $query .= " OFFSET :offset";
            }
        }

        $stmt = $this->db->prepare($query);

        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
        }

        if ($limit) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            if ($offset) {
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $fields = implode(', ', array_keys($data));
        $values = ':' . implode(', :', array_keys($data));
        
        $query = "INSERT INTO {$this->table} ($fields) VALUES ($values)";
        $stmt = $this->db->prepare($query);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        return $stmt->execute() ? $this->db->lastInsertId() : false;
    }

    public function update($id, $data)
    {
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

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
}