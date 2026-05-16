<?php
namespace App\Controllers;
use App\Models\ApplicationModel;

class ApplicationController extends Controller{
    public function myApplications(){
        $id = $_SESSION['user_id'];
        $applicationModel = new ApplicationModel();
        $applications = $applicationModel->getStudentApplications($id);
        $this->render("student/applications.html.twig", ["applications" => $applications]);
    }
    public function apply(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {            
            if (!\App\Core\Csrf::verify()) {
                $error = 'Jeton CSRF invalide';
            } elseif ($_FILES['cv']['error'] !== UPLOAD_ERR_OK || $_FILES['lm']['error'] !== UPLOAD_ERR_OK) {
                $error = 'Erreur lors de l\'upload.';
            } elseif ($_FILES['cv']['size'] > 2 * 1024 * 1024 || $_FILES['lm']['size'] > 2 * 1024 * 1024) {
                $error = 'Les fichiers ne doivent pas dépasser 2Mo.';
            } elseif (mime_content_type($_FILES['cv']['tmp_name']) !== 'application/pdf' || mime_content_type($_FILES['lm']['tmp_name']) !== 'application/pdf') {
                $error = 'Les fichiers doivent être des PDF.';
            }

            if ($error) {
                $offerModel = new \App\Models\OfferModel();
                $offer = $offerModel->getOfferById($_POST['offre_id']);
                $this->render('offers/show.html.twig', ['offer' => $offer, 'error' => $error]);
                return;
            }
            
            $applicationModel = new ApplicationModel();
            if (!$applicationModel->hasAlreadyApplied($_SESSION['user_id'], $_POST['offre_id'])) {
                $cv = $_FILES['cv'];
                $lm = $_FILES['lm'];
                $cv_destination = 'storage/cv/' . uniqid() . '_' . $cv['name'];
                $lm_destination = 'storage/lm/' . uniqid() . '_' . $lm['name'];
                move_uploaded_file($cv['tmp_name'], dirname(__DIR__, 2) . '/' . $cv_destination);
                move_uploaded_file($lm['tmp_name'], dirname(__DIR__, 2) . '/' . $lm_destination);
                $applicationModel->apply(['chemin_cv' => $cv_destination, 'chemin_lm' => $lm_destination, 'offre_id' => $_POST['offre_id'], 'utilisateur_id' => $_SESSION['user_id']]);
            }
            header('Location: /student/applications');
            exit();
        }
    }
    public function pilotApplications(){
        $id = $_SESSION['user_id'];
        $applicationModel = new ApplicationModel();
        $applications = $applicationModel->getPilotStudentsApplications($id);
        $this->render('pilot/applications.html.twig', ["applications" => $applications]);
    }

    public function serveFile() {
        $path = $_GET['path'] ?? null;
        $fullPath = dirname(__DIR__, 2) . '/' . $path;
        
        if (!$path || !file_exists($fullPath)) {
            http_response_code(404);
            die('Fichier introuvable.');
        }

        if ($_SESSION['user_role'] === 'etudiant') {
            $applicationModel = new ApplicationModel();
            $isOwner = $applicationModel->isFileOwner($_SESSION['user_id'], $path);
            if (!$isOwner) {
                http_response_code(403);
                die('Accès refusé.');
            }
        }

        header('Content-Type: application/pdf');
        readfile($fullPath);
        exit;
    }
}