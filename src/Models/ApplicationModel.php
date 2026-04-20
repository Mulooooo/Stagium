<?php
namespace App\Models;

class ApplicationModel extends Model{
    public function hasAlreadyApplied($userId, $offerId){
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM CANDIDATURE WHERE utilisateur_id = :utilisateur_id AND offre_id = :offre_id;");
        $stmt->execute([':utilisateur_id' => $userId, ':offre_id' => $offerId]);
        return $stmt->fetchColumn() > 0;
    }
    public function apply($data){
        $stmt = $this->db->prepare("INSERT INTO CANDIDATURE (utilisateur_id, offre_id, chemin_cv, chemin_lm) VALUES (:utilisateur_id, :offre_id, :chemin_cv, :chemin_lm);");
        return $stmt->execute($data);
    }
    public function getStudentApplications($userId){
        $stmt = $this->db->prepare("SELECT CANDIDATURE.id, OFFRE_STAGE.titre, ENTREPRISE.nom, SITE_ENTREPRISE.ville, OFFRE_STAGE.id AS offre_id FROM CANDIDATURE JOIN OFFRE_STAGE ON CANDIDATURE.offre_id = OFFRE_STAGE.id JOIN SITE_ENTREPRISE ON OFFRE_STAGE.site_entreprise_id = SITE_ENTREPRISE.id JOIN ENTREPRISE ON SITE_ENTREPRISE.entreprise_id = ENTREPRISE.id WHERE CANDIDATURE.utilisateur_id = :utilisateur_id;");
        $stmt->execute([':utilisateur_id' => $userId]);
        return $stmt->fetchAll();
    }
}