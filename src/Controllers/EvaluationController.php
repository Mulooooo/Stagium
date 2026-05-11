<?php
namespace App\Controllers;
use App\Models\EvaluationModel;

class EvaluationController extends Controller {
    public function evaluate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $error = null;

            if (!\App\Core\Csrf::verify()) {
                $error = 'Jeton CSRF invalide';
            } elseif (empty($_POST['note']) || $_POST['note'] < 1 || $_POST['note'] > 5) {
                $error = 'La note doit être entre 1 et 5.';
            } elseif (empty($_POST['commentaire'])) {
                $error = 'Le commentaire est obligatoire.';
            }

            if ($error) {
                $companyModel = new \App\Models\CompanyModel();
                $offerModel = new \App\Models\OfferModel();
                $evaluationModel = new EvaluationModel();
                $company = $companyModel->findById($_POST['entreprise_id']);
                $offers = $offerModel->getOffersByCompany($_POST['entreprise_id']);
                $evaluations = $evaluationModel->getByEntreprise($_POST['entreprise_id']);
                $this->render('companies/show.html.twig', [
                    'company' => $company,
                    'offers' => $offers,
                    'evaluations' => $evaluations,
                    'error' => $error
                ]);
                return;
            }

            $evaluationModel = new EvaluationModel();
            $evaluationModel->addEvaluation($_POST['entreprise_id'], $_SESSION['user_id'], $_POST['note'], $_POST['commentaire']);
            header('Location: /companies/show?id=' . $_POST['entreprise_id']);
            exit;
        }
    }
}