<?php

namespace App\Models;

class OfferModel extends Model{
    public function getActiveOffers() {
        $stmt = $this->db->prepare("SELECT OFFRE_STAGE.id, OFFRE_STAGE.titre, OFFRE_STAGE.gratification, OFFRE_STAGE.date_debut, OFFRE_STAGE.duree_semaines, ENTREPRISE.nom, SITE_ENTREPRISE.ville FROM OFFRE_STAGE JOIN SITE_ENTREPRISE ON OFFRE_STAGE.site_entreprise_id = SITE_ENTREPRISE.id JOIN ENTREPRISE ON SITE_ENTREPRISE.entreprise_id = ENTREPRISE.id WHERE OFFRE_STAGE.est_active = 1;");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getOfferById(int $id) {
        $stmt = $this->db->prepare("SELECT OFFRE_STAGE.titre, OFFRE_STAGE.gratification, OFFRE_STAGE.date_debut, OFFRE_STAGE.duree_semaines, ENTREPRISE.nom, SITE_ENTREPRISE.ville FROM OFFRE_STAGE JOIN SITE_ENTREPRISE ON OFFRE_STAGE.site_entreprise_id = SITE_ENTREPRISE.id JOIN ENTREPRISE ON SITE_ENTREPRISE.entreprise_id = ENTREPRISE.id WHERE OFFRE_STAGE.id = :id;");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}