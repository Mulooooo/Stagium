<?php
namespace App\Models;

class StudentModel extends Model {
    public function getAll($page, $limit){
        $offset = ($page - 1) * $limit;
        $stmt = $this->db->prepare("SELECT * FROM UTILISATEUR WHERE role = 'etudiant' LIMIT :limit OFFSET :offset;");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM UTILISATEUR WHERE role = 'etudiant'");
        $countStmt->execute();
        $total = $countStmt->fetchColumn();
        return ['items' => $stmt->fetchAll(), 'total' => $total];
    }
    public function findById($id){
        $stmt = $this->db->prepare("SELECT * FROM UTILISATEUR WHERE role = 'etudiant' and id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    public function create($data){
        $stmt = $this->db->prepare("INSERT INTO UTILISATEUR (nom, prenom, email, mot_de_passe, role) VALUES (:nom, :prenom, :email, :mot_de_passe, 'etudiant');");
        $stmt->execute($data);
        $lastId = $this->db->lastInsertId();
        $stmt2 = $this->db->prepare("INSERT INTO ETUDIANT (utilisateur_id) VALUES (:id)");
        return $stmt2->execute([':id' => $lastId]);
    }
    public function update($id, $data){
        $stmt = $this->db->prepare("UPDATE UTILISATEUR SET nom=:nom, prenom=:prenom, email=:email, mot_de_passe=:mot_de_passe WHERE id=:id;");
        $data[':id'] = $id;
        return $stmt->execute($data);
    }
    public function delete($id){
        $stmt = $this->db->prepare("DELETE FROM UTILISATEUR WHERE id = :id AND role = 'etudiant';");
        return $stmt->execute([':id' => $id]);
    }
}