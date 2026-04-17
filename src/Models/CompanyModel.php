<?php
namespace App\Models;

class CompanyModel extends Model{
    public function getAll(){
        $stmt = $this->db->prepare("SELECT * FROM ENTREPRISE WHERE est_active = 1;");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function findById($id){
        $stmt = $this->db->prepare("SELECT * FROM ENTREPRISE WHERE id = :id;");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    public function create(){
    }
    public function update(){
    }
    public function delete(){
    }
}