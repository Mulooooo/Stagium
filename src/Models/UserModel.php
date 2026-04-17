<?php
namespace App\Models;

class UserModel extends Model{
    public function findByEmail(string $email) {
        $stmt = $this->db->prepare("SELECT * FROM UTILISATEUR WHERE email = :email;");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}