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
            $applicationModel = new ApplicationModel();
            if (!$applicationModel->hasAlreadyApplied($_SESSION['user_id'], $_POST['offre_id'])) {
                $cv = $_FILES['cv'];
                $lm = $_FILES['lm'];
                $cv_destination = dirname(__DIR__, 2) . '/storage/cv/' . uniqid() . '_' . $cv['name'];
                $lm_destination = dirname(__DIR__, 2) . '/storage/lm/' . uniqid() . '_' . $lm['name'];
                move_uploaded_file($cv['tmp_name'], $cv_destination);
                move_uploaded_file($lm['tmp_name'], $lm_destination);
                $applicationModel->apply(['chemin_cv' => $cv_destination, 'chemin_lm' => $lm_destination, 'offre_id' => $_POST['offre_id'], 'utilisateur_id' => $_SESSION['user_id']]);
            }
            header('Location: /student/applications');
            exit();
        }
    }
}