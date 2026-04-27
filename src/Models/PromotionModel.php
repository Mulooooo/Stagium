<?php
namespace App\Models;

class PromotionModel extends Model {

    public function getAll() {
        return $this->db->query("SELECT * FROM PROMOTION ORDER BY annee_scolaire DESC")->fetchAll();
    }

    public function getByPilot(int $pilotId) {
        $stmt = $this->db->prepare("SELECT PROMOTION.* FROM PROMOTION JOIN GERE ON PROMOTION.id = GERE.promotion_id WHERE GERE.utilisateur_id = :id");
        $stmt->execute([':id' => $pilotId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM PROMOTION WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO PROMOTION (filiere, type_cycle, annee_cycle, annee_scolaire, campus_id) VALUES (:filiere, :type_cycle, :annee_cycle, :annee_scolaire, 1)");
        return $stmt->execute([
            ':filiere' => $data['filiere'],
            ':type_cycle' => $data['type_cycle'],
            ':annee_cycle' => $data['annee_cycle'],
            ':annee_scolaire' => $data['annee_scolaire']
        ]);
    }

    public function getStudents(int $promotionId) {
        $stmt = $this->db->prepare("SELECT UTILISATEUR.id AS utilisateur_id, UTILISATEUR.prenom, UTILISATEUR.nom FROM UTILISATEUR JOIN INSCRIT ON UTILISATEUR.id = INSCRIT.utilisateur_id WHERE INSCRIT.promotion_id = :id");
        $stmt->execute([':id' => $promotionId]);
        return $stmt->fetchAll();
    }

    public function getPilots(int $promotionId) {
        $stmt = $this->db->prepare("SELECT UTILISATEUR.id AS utilisateur_id, UTILISATEUR.prenom, UTILISATEUR.nom FROM UTILISATEUR JOIN GERE ON UTILISATEUR.id = GERE.utilisateur_id WHERE GERE.promotion_id = :id");
        $stmt->execute([':id' => $promotionId]);
        return $stmt->fetchAll();
    }

    public function addStudent(int $promotionId, int $userId) {
        $stmt = $this->db->prepare("INSERT IGNORE INTO INSCRIT (utilisateur_id, promotion_id) VALUES (:user_id, :promo_id)");
        return $stmt->execute([':user_id' => $userId, ':promo_id' => $promotionId]);
    }

    public function addPilot(int $promotionId, int $userId) {
        $stmt = $this->db->prepare("INSERT IGNORE INTO GERE (utilisateur_id, promotion_id) VALUES (:user_id, :promo_id)");
        return $stmt->execute([':user_id' => $userId, ':promo_id' => $promotionId]);
    }

    public function removeStudent(int $promotionId, int $userId) {
        $stmt = $this->db->prepare("DELETE FROM INSCRIT WHERE utilisateur_id = :user_id AND promotion_id = :promo_id");
        return $stmt->execute([':user_id' => $userId, ':promo_id' => $promotionId]);
    }

    public function removePilot(int $promotionId, int $userId) {
        $stmt = $this->db->prepare("DELETE FROM GERE WHERE utilisateur_id = :user_id AND promotion_id = :promo_id");
        return $stmt->execute([':user_id' => $userId, ':promo_id' => $promotionId]);
    }

    public function delete(int $id) {
        $stmt = $this->db->prepare("DELETE FROM PROMOTION WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}