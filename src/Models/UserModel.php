<?php
namespace App\Models;
use App\Models\Database;

class UserModel {
    public function findByEmail(string $email) {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM UTILISATEUR WHERE email = :email;");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}