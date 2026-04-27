<?php

namespace App\Models;

class OfferModel extends Model{
    public function getActiveOffers($page, $limit) {
        $offset = ($page - 1) * $limit;
        $stmt = $this->db->prepare("SELECT OFFRE_STAGE.id, OFFRE_STAGE.titre, OFFRE_STAGE.gratification, OFFRE_STAGE.date_debut, OFFRE_STAGE.duree_semaines, ENTREPRISE.nom, SITE_ENTREPRISE.ville FROM OFFRE_STAGE JOIN SITE_ENTREPRISE ON OFFRE_STAGE.site_entreprise_id = SITE_ENTREPRISE.id JOIN ENTREPRISE ON SITE_ENTREPRISE.entreprise_id = ENTREPRISE.id WHERE OFFRE_STAGE.est_active = 1 LIMIT :limit OFFSET :offset;");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        
        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM OFFRE_STAGE WHERE est_active = 1");
        $countStmt->execute();
        $total = $countStmt->fetchColumn();
        return ['items' => $stmt->fetchAll(), 'total' => $total];
    }

    public function searchOffers(array $filters, $page, $limit) {
        $sql = "SELECT OFFRE_STAGE.id, OFFRE_STAGE.titre, OFFRE_STAGE.gratification, OFFRE_STAGE.date_debut, OFFRE_STAGE.duree_semaines, ENTREPRISE.nom, SITE_ENTREPRISE.ville FROM OFFRE_STAGE JOIN SITE_ENTREPRISE ON OFFRE_STAGE.site_entreprise_id = SITE_ENTREPRISE.id JOIN ENTREPRISE ON SITE_ENTREPRISE.entreprise_id = ENTREPRISE.id WHERE OFFRE_STAGE.est_active = 1";
        $params = [];

        if (!empty($filters['q'])) {
            $sql .= " AND (OFFRE_STAGE.titre LIKE :q OR ENTREPRISE.nom LIKE :q2)";
            $params[':q'] = '%' . $filters['q'] . '%';
            $params[':q2'] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['location'])) {
            $sql .= " AND SITE_ENTREPRISE.ville LIKE :location";
            $params[':location'] = '%' . $filters['location'] . '%';
        }

        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM ($sql) AS sub");
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();

        $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $offset = ($page - 1) * $limit;
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return ['items' => $stmt->fetchAll(), 'total' => $total];
    }

    public function getOfferById(int $id) {
        $stmt = $this->db->prepare("SELECT OFFRE_STAGE.id AS id, OFFRE_STAGE.titre, OFFRE_STAGE.gratification, OFFRE_STAGE.date_debut, OFFRE_STAGE.duree_semaines, OFFRE_STAGE.description, ENTREPRISE.id AS entreprise_id, ENTREPRISE.nom, SITE_ENTREPRISE.ville ,ENTREPRISE.description AS company_description, SECTEUR_ACTIVITE.nom AS company_sector FROM OFFRE_STAGE JOIN SITE_ENTREPRISE ON OFFRE_STAGE.site_entreprise_id = SITE_ENTREPRISE.id JOIN ENTREPRISE ON SITE_ENTREPRISE.entreprise_id = ENTREPRISE.id LEFT JOIN SECTEUR_ACTIVITE ON ENTREPRISE.secteur_id = SECTEUR_ACTIVITE.id WHERE OFFRE_STAGE.id = :id;");
        $stmt->execute([':id' => $id]);
        $offer = $stmt->fetch();

        if ($offer) {
            $skillStmt = $this->db->prepare("SELECT COMPETENCE.libelle FROM COMPETENCE JOIN REQUIERT ON COMPETENCE.id = REQUIERT.competence_id WHERE REQUIERT.offre_id = :id");
            $skillStmt->execute([':id' => $id]);
            $offer['skills'] = $skillStmt->fetchAll();
        }
        return $offer;
    }

    public function getOffersByCompany(int $companyId): array {
        $stmt = $this->db->prepare("
            SELECT OFFRE_STAGE.id, OFFRE_STAGE.titre, OFFRE_STAGE.gratification, 
                OFFRE_STAGE.date_debut, OFFRE_STAGE.duree_semaines, 
                ENTREPRISE.nom, SITE_ENTREPRISE.ville 
            FROM OFFRE_STAGE 
            JOIN SITE_ENTREPRISE ON OFFRE_STAGE.site_entreprise_id = SITE_ENTREPRISE.id 
            JOIN ENTREPRISE ON SITE_ENTREPRISE.entreprise_id = ENTREPRISE.id 
            WHERE ENTREPRISE.id = :id AND OFFRE_STAGE.est_active = 1
        ");
        $stmt->execute([':id' => $companyId]);
        return $stmt->fetchAll();
    }
    
    public function getSites() {
        $stmt = $this->db->prepare("SELECT SITE_ENTREPRISE.id, SITE_ENTREPRISE.nom_site, ENTREPRISE.nom FROM SITE_ENTREPRISE JOIN ENTREPRISE ON SITE_ENTREPRISE.entreprise_id = ENTREPRISE.id ORDER BY ENTREPRISE.nom;");
        $stmt->execute();
        return $stmt->fetchAll();
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

    public function getStats(): array {
        $total = $this->db->query("SELECT COUNT(*) FROM OFFRE_STAGE WHERE est_active = 1")->fetchColumn();

        $avgCandidatures = $this->db->query("SELECT AVG(nb) FROM (SELECT COUNT(*) as nb FROM CANDIDATURE GROUP BY offre_id) AS sub")->fetchColumn();

        $topWishlist = $this->db->query("
            SELECT OFFRE_STAGE.titre, COUNT(*) as nb_favoris
            FROM FAVORI
            JOIN OFFRE_STAGE ON FAVORI.offre_id = OFFRE_STAGE.id
            GROUP BY OFFRE_STAGE.id
            ORDER BY nb_favoris DESC, OFFRE_STAGE.id ASC
            LIMIT 3
        ")->fetchAll();

        $repartitionDuree = $this->db->query("
            SELECT duree_semaines, COUNT(*) as nb
            FROM OFFRE_STAGE WHERE est_active = 1
            GROUP BY duree_semaines
            ORDER BY duree_semaines ASC
        ")->fetchAll();

        return [
            'total' => $total,
            'avg_candidatures' => round($avgCandidatures, 1),
            'top_wishlist' => $topWishlist,
            'repartition_duree' => $repartitionDuree
        ];
    }
}