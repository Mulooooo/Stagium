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
    public function create($data){
        $stmt = $this->db->prepare("INSERT INTO ENTREPRISE (siren, nom, description, email, telephone) VALUES (:siren, :nom, :description, :email, :telephone);");
        return $stmt->execute($data);
    }
    public function update($id, $data){
        $stmt = $this->db->prepare("UPDATE ENTREPRISE SET siren=:siren, nom=:nom, description=:description, email=:email, telephone=:telephone WHERE id=:id;");
        $data[':id'] = $id;
        return $stmt->execute($data);
    }
    public function delete($id){
        $stmt = $this->db->prepare("DELETE FROM ENTREPRISE WHERE id = :id;");
        return $stmt->execute([':id' => $id]);
    }
}