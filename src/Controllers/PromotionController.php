<?php
namespace App\Controllers;
use App\Models\PromotionModel;
use App\Models\StudentModel;
use App\Models\PilotModel;

class PromotionController extends Controller {

    public function index() {
        $promotionModel = new PromotionModel();
        if ($_SESSION['user_role'] === 'pilote') {
            $promotions = $promotionModel->getByPilot($_SESSION['user_id']);
        } else {
            $promotions = $promotionModel->getAll();
        }
        $this->render('promotions/index.html.twig', ['promotions' => $promotions]);
    }

    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /promotions');
            exit;
        }
        $promotionModel = new PromotionModel();
        $promotion = $promotionModel->findById($id);
        $students = $promotionModel->getStudents($id);
        $pilots = $promotionModel->getPilots($id);
        $allStudents = (new StudentModel())->getAll(1, 1000)['items'];
        $allPilots = (new PilotModel())->getAll(1, 1000)['items'];
        $this->render('promotions/show.html.twig', [
            'promotion' => $promotion,
            'students' => $students,
            'pilots' => $pilots,
            'all_students' => $allStudents,
            'all_pilots' => $allPilots
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $promotionModel = new PromotionModel();
            $promotionModel->create($_POST);
            header('Location: /promotions');
            exit;
        }
        $this->render('promotions/create.html.twig');
    }

    public function addStudent() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\App\Core\Csrf::verify()) {
                $this->render('promotions/show.html.twig', ['error' => 'Jeton CSRF invalide']);
                return;
            }

            $promotionModel = new PromotionModel();
            $promotionModel->addStudent($_POST['promotion_id'], $_POST['utilisateur_id']);
            header('Location: /promotions/show?id=' . $_POST['promotion_id']);
            exit;
        }
    }

    public function addPilot() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\App\Core\Csrf::verify()) {
                $this->render('promotions/show.html.twig', ['error' => 'Jeton CSRF invalide']);
                return;
            }

            $promotionModel = new PromotionModel();
            $promotionModel->addPilot($_POST['promotion_id'], $_POST['utilisateur_id']);
            header('Location: /promotions/show?id=' . $_POST['promotion_id']);
            exit;
        }
    }

    public function removeStudent() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\App\Core\Csrf::verify()) {
                $this->render('promotions/show.html.twig', ['error' => 'Jeton CSRF invalide']);
                return;
            }

            $promotionModel = new PromotionModel();
            $promotionModel->removeStudent($_POST['promotion_id'], $_POST['utilisateur_id']);
            header('Location: /promotions/show?id=' . $_POST['promotion_id']);
            exit;
        }
    }

    public function removePilot() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\App\Core\Csrf::verify()) {
                $this->render('promotions/show.html.twig', ['error' => 'Jeton CSRF invalide']);
                return;
            }

            $promotionModel = new PromotionModel();
            $promotionModel->removePilot($_POST['promotion_id'], $_POST['utilisateur_id']);
            header('Location: /promotions/show?id=' . $_POST['promotion_id']);
            exit;
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $promotionModel = new PromotionModel();
            $promotionModel->delete($_POST['id']);
            header('Location: /promotions');
            exit;
        }
    }
}