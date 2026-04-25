<?php
namespace App\Models;

class CompanyModel extends Model{
    public function getAll($page, $limit){
        $offset = ($page - 1) * $limit;
        $stmt = $this->db->prepare("SELECT * FROM ENTREPRISE WHERE est_active = 1 LIMIT :limit OFFSET :offset;");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM ENTREPRISE WHERE est_active = 1");
        $countStmt->execute();
        $total = $countStmt->fetchColumn();
        return ['items' => $stmt->fetchAll(), 'total' => $total];
    }

    public function searchCompanies(array $filters, $page, $limit) {
        $sql = "SELECT * FROM ENTREPRISE WHERE est_active = 1";
        $params = [];

        if (!empty($filters['q'])) {
            $sql .= " AND nom LIKE :q";
            $params[':q'] = '%' . $filters['q'] . '%';
        }

        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM ($sql) AS sub");
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();

        $offset = ($page - 1) * $limit;
        $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return ['items' => $stmt->fetchAll(), 'total' => $total];
    }

    public function findById($id){
        $stmt = $this->db->prepare("SELECT * FROM ENTREPRISE WHERE id = :id;");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
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