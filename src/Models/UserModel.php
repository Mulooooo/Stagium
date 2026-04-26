<?php
namespace App\Models;

class UserModel extends Model{
    public function findByEmail(string $email) {
        $stmt = $this->db->prepare("SELECT * FROM UTILISATEUR WHERE email = :email;");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM UTILISATEUR WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("UPDATE UTILISATEUR SET prenom = :prenom, nom = :nom, email = :email WHERE id = :id");
        return $stmt->execute([':prenom' => $data['prenom'], ':nom' => $data['nom'], ':email' => $data['email'], ':id' => $id]);
    }
}