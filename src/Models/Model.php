<?php
namespace App\Models;
use App\Models\Database;

class Model {
    protected $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
}