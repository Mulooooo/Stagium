<?php
namespace App\Models;

class WishlistModel extends Model{
    public function isSaved($userId, $offerId){
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM FAVORI WHERE utilisateur_id = :utilisateur_id AND offre_id = :offre_id;");
        $stmt->execute([':utilisateur_id' => $userId, ':offre_id' => $offerId]);
        return $stmt->fetchColumn() > 0;
    }
    public function toggle($userId, $offerId){
        if($this->isSaved($userId, $offerId)){
            $stmt = $this->db->prepare("DELETE FROM FAVORI WHERE utilisateur_id = :utilisateur_id AND offre_id = :offre_id;");
            $return = 'removed';
            } else {
            $stmt = $this->db->prepare("INSERT INTO FAVORI (utilisateur_id , offre_id) VALUES (:utilisateur_id , :offre_id);");
            $return = 'added';
            }
        $stmt->execute([':utilisateur_id' => $userId, ':offre_id' => $offerId]);
        return $return;
    }
    public function getSavedOffers($userId){
        $stmt = $this->db->prepare("SELECT OFFRE_STAGE.id, OFFRE_STAGE.titre, ENTREPRISE.nom, SITE_ENTREPRISE.ville FROM FAVORI JOIN OFFRE_STAGE ON FAVORI.offre_id = OFFRE_STAGE.id JOIN SITE_ENTREPRISE ON OFFRE_STAGE.site_entreprise_id = SITE_ENTREPRISE.id JOIN ENTREPRISE ON SITE_ENTREPRISE.entreprise_id = ENTREPRISE.id WHERE FAVORI.utilisateur_id = :utilisateur_id;");
        $stmt->execute([':utilisateur_id' => $userId]);
        return $stmt->fetchAll();
    }
}