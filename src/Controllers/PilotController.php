<?php
namespace App\Controllers;
use App\Models\PilotModel;

class PilotController extends Controller {
    public function index(){
        $page = $_GET['page'] ?? 1;
        $limit = 6;
        $pilotModel = new PilotModel();
        $pilots = $pilotModel->getAll($page, $limit);
        $total = $pilots['total'];
        $items = $pilots['items'];
        $totalPages = ceil($total / $limit);
        $this->render("pilots/index.html.twig", ['pilots' => $items, 'total_pages' => $totalPages, 'current_page' => $page]);
    }
    public function show(){
        $id = $_GET['id'] ?? null;
        if (!$id) { 
            header('Location: /pilots'); 
            exit;
        }
        $pilotModel = new PilotModel();
        $pilot = $pilotModel->findById($id);
        $this->render("pilots/show.html.twig", ['pilot' => $pilot]);
    }
    public function create(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $data['mot_de_passe'] = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
            $pilotModel = new PilotModel();
            $pilotModel->create($data);
            header('Location: /pilots');
            exit;
        }
        $this->render("pilots/create.html.twig");
    }
    public function edit(){
        $id = $_GET['id'] ?? null;
        if (!$id) { 
            header('Location: /pilots');
            exit;
        }
        $pilotModel = new PilotModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            if (empty($data['mot_de_passe'])) {
                $pilot = $pilotModel->findById($id);
                $data['mot_de_passe'] = $pilot['mot_de_passe'];
            } else {
                $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
            }
            $offer = $pilotModel->update($id, $data);
            header('Location: /pilots');
            exit;
        }
        $pilot = $pilotModel->findById($id);
        $this->render("pilots/edit.html.twig", ['pilot' => $pilot]);
    }
    public function delete(){
        $pilotModel = new PilotModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pilotModel->delete($_POST['id']);
            header('Location: /pilots');
            exit;
        }
    }
}