<?php

namespace App\Models;

class OfferModel extends Model{
    public function getActiveOffers() {
        $stmt = $this->db->prepare("SELECT OFFRE_STAGE.id, OFFRE_STAGE.titre, OFFRE_STAGE.gratification, OFFRE_STAGE.date_debut, OFFRE_STAGE.duree_semaines, ENTREPRISE.nom, SITE_ENTREPRISE.ville FROM OFFRE_STAGE JOIN SITE_ENTREPRISE ON OFFRE_STAGE.site_entreprise_id = SITE_ENTREPRISE.id JOIN ENTREPRISE ON SITE_ENTREPRISE.entreprise_id = ENTREPRISE.id WHERE OFFRE_STAGE.est_active = 1;");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getOfferById(int $id) {
        $stmt = $this->db->prepare("SELECT OFFRE_STAGE.titre, OFFRE_STAGE.gratification, OFFRE_STAGE.date_debut, OFFRE_STAGE.duree_semaines, OFFRE_STAGE.description, ENTREPRISE.nom, SITE_ENTREPRISE.ville FROM OFFRE_STAGE JOIN SITE_ENTREPRISE ON OFFRE_STAGE.site_entreprise_id = SITE_ENTREPRISE.id JOIN ENTREPRISE ON SITE_ENTREPRISE.entreprise_id = ENTREPRISE.id WHERE OFFRE_STAGE.id = :id;");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    public function getSites() {
        $stmt = $this->db->prepare("SELECT SITE_ENTREPRISE.id, SITE_ENTREPRISE.nom_site, ENTREPRISE.nom FROM SITE_ENTREPRISE JOIN ENTREPRISE ON SITE_ENTREPRISE.entreprise_id = ENTREPRISE.id ORDER BY ENTREPRISE.nom;");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function create($data){
        $stmt = $this->db->prepare("INSERT INTO OFFRE_STAGE (titre, description, gratification, date_debut, duree_semaines, site_entreprise_id) VALUES (:titre, :description, :gratification, :date_debut, :duree_semaines, :site_entreprise_id);");
        return $stmt->execute($data);
    }
    public function update($id, $data){
        $stmt = $this->db->prepare("UPDATE OFFRE_STAGE SET titre=:titre, description=:description, gratification=:gratification, date_debut=:date_debut, duree_semaines=:duree_semaines, site_entreprise_id=:site_entreprise_id  WHERE id=:id;");
        $data[':id'] = $id;
        return $stmt->execute($data);
    }
    public function delete($id){
        $stmt = $this->db->prepare("DELETE FROM OFFRE_STAGE WHERE id = :id;");
        return $stmt->execute([':id' => $id]);
    }
}