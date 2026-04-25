<?php
namespace App\Models;

class EvaluationModel extends Model {

    public function addEvaluation(int $entrepriseId, int $userId, int $note, string $commentaire): bool {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO EVALUATION (note, commentaire, date_evaluation) VALUES (:note, :commentaire, NOW())");
            $stmt->execute([':note' => $note, ':commentaire' => $commentaire]);
            $evaluationId = $this->db->lastInsertId();

            $stmt = $this->db->prepare("INSERT INTO CONCERNE_ENTREPRISE (evaluation_id, entreprise_id) VALUES (:eval_id, :entreprise_id)");
            $stmt->execute([':eval_id' => $evaluationId, ':entreprise_id' => $entrepriseId]);

            $stmt = $this->db->prepare("INSERT INTO REDIGE (evaluation_id, utilisateur_id) VALUES (:eval_id, :user_id)");
            $stmt->execute([':eval_id' => $evaluationId, ':user_id' => $userId]);

            return $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getByEntreprise(int $entrepriseId): array {
        $stmt = $this->db->prepare("SELECT EVALUATION.note, EVALUATION.commentaire, EVALUATION.date_evaluation FROM EVALUATION JOIN CONCERNE_ENTREPRISE ON EVALUATION.id = CONCERNE_ENTREPRISE.evaluation_id WHERE CONCERNE_ENTREPRISE.entreprise_id = :id ORDER BY EVALUATION.date_evaluation DESC");
        $stmt->execute([':id' => $entrepriseId]);
        return $stmt->fetchAll();
    }
}