<?php
namespace App\Controllers;
use App\Models\EvaluationModel;

class EvaluationController extends Controller {
    public function evaluate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entrepriseId = $_POST['entreprise_id'];
            $note = $_POST['note'];
            $commentaire = $_POST['commentaire'];
            $userId = $_SESSION['user_id'];

            $evaluationModel = new EvaluationModel();
            $evaluationModel->addEvaluation($entrepriseId, $userId, $note, $commentaire);

            header('Location: /companies/show?id=' . $entrepriseId);
            exit;
        }
    }
}