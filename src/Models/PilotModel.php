<?php
namespace App\Models;

class PilotModel extends Model{
    public function getAll(){
        $stmt = $this->db->prepare("SELECT * FROM UTILISATEUR WHERE role = 'pilote';");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function findById($id){
        $stmt = $this->db->prepare("SELECT * FROM UTILISATEUR WHERE role = 'pilote' and id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    public function create($data){
        $stmt = $this->db->prepare("INSERT INTO UTILISATEUR (nom, prenom, email, mot_de_passe, role) VALUES (:nom, :prenom, :email, :mot_de_passe, 'pilote');");
        $stmt->execute($data);
        $lastId = $this->db->lastInsertId();
        $stmt2 = $this->db->prepare("INSERT INTO PILOTE (utilisateur_id) VALUES (:id)");
        return $stmt2->execute([':id' => $lastId]);
    }
    public function update($id, $data){
        $stmt = $this->db->prepare("UPDATE UTILISATEUR SET nom=:nom, prenom=:prenom, email=:email, mot_de_passe=:mot_de_passe WHERE id=:id;");
        $data[':id'] = $id;
        return $stmt->execute($data);
    }
    public function delete($id){
        $stmt = $this->db->prepare("DELETE FROM UTILISATEUR WHERE id = :id AND role = 'pilote';");
        return $stmt->execute([':id' => $id]);
    }
}