<?php
namespace App\Models;

class StudentModel extends Model {
    public function getAll(){
        $stmt = $this->db->prepare("SELECT * FROM UTILISATEUR WHERE role = 'etudiant';");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function findById($id){
        $stmt = $this->db->prepare("SELECT * FROM UTILISATEUR WHERE role = 'etudiant' and id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}