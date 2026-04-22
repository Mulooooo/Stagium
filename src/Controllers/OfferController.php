<?php
namespace App\Controllers;
use App\Controllers\Controller;
use App\Models\OfferModel;

class OfferController extends Controller{
    public function index() {
        $page = $_GET['page'] ?? 1;
        $limit = 6;
        $offerModel = new OfferModel();
        $offers = $offerModel->getActiveOffers($page, $limit);
        $total = $offers['total'];
        $items = $offers['items'];
        $totalPages = ceil($total / $limit);
        $this->render("offers/index.html.twig", ['offers' => $items, 'total_pages' => $totalPages, 'current_page' => $page]);
    }

    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) { 
            header('Location: /offers'); 
            exit;
        }
        $offerModel = new OfferModel();
        $offer = $offerModel->getOfferById($id);
        if (!$offer) {
            http_response_code(404);
            return;
        } else {
            $this->render("offers/show.html.twig", ['offer' => $offer]);
        }
    }
    public function create(){
        $offerModel = new OfferModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $offer = $offerModel->create($data);
            header('Location: /offers');
            exit;
        }
        $sites = $offerModel->getSites();
        $this->render("offers/create.html.twig", ['sites' => $sites]);
    }
    public function edit(){
        $id = $_GET['id'] ?? null;
        if (!$id) { 
            header('Location: /offers'); 
            exit;
        }
        $offerModel = new OfferModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $offer = $offerModel->update($id, $data);
            header('Location: /offers');
            exit;
        }
        $offer = $offerModel->getOfferById($id);
        $sites = $offerModel->getSites();
        $this->render("offers/edit.html.twig", ['offer' => $offer, 'sites' => $sites]);
    }
    public function delete(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $offerModel = new OfferModel();
            $offers = $offerModel->delete($id);
            header('Location: /offers');
            exit;
        }
    }
}