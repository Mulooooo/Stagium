<?php
namespace App\Controllers;
use App\Controllers\Controller;
use App\Models\OfferModel;

class OfferController extends Controller{
    public function index() {
        $page = $_GET['page'] ?? 1;
        $limit = 6;
        $filters["q"] = $_GET['q'] ?? '';
        $filters["location"] = $_GET['location'] ?? '';
        $offerModel = new OfferModel();
        $offers = $offerModel->searchOffers($filters, $page, $limit);
        $total = $offers['total'];
        $items = $offers['items'];
        $totalPages = ceil($total / $limit);
        $this->render("offers/index.html.twig", ['offers' => $items, 'total_pages' => $totalPages, 'current_page' => $page, 'filters' => $filters]);
    }

    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) { 
            header('Location: /offers');
            exit;
        }
        $offerModel = new OfferModel();
        $offer = $offerModel->getOfferById($id);
        $isSaved = false;
        if (isset($_SESSION['user_id'])) {
            $wishlistModel = new \App\Models\WishlistModel();
            $isSaved = $wishlistModel->isSaved($_SESSION['user_id'], $id);
        }
        $alreadyApplied = false;
        if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'etudiant') {
            $applicationModel = new \App\Models\ApplicationModel();
            $alreadyApplied = $applicationModel->hasAlreadyApplied($_SESSION['user_id'], $id);
        }
        if (!$offer) {
            http_response_code(404);
            return;
        } else {
            $this->render("offers/show.html.twig", ['offer' => $offer, 'is_saved' => $isSaved, 'already_applied' => $alreadyApplied]);
        }
    }
    public function create(){
        $offerModel = new OfferModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $error = null;
            if (!\App\Core\Csrf::verify()) {
                $error = 'Jeton CSRF invalide';
            } elseif (empty($_POST['titre'])) {
                $error = "Le titre est obligatoire.";
            } elseif (empty($_POST['date_debut'])) {
                $error = "La date de début est obligatoire.";
            } elseif (empty($_POST['duree_semaines']) || $_POST['duree_semaines'] < 1 || $_POST['duree_semaines'] > 52) {
                $error = "La durée doit être entre 1 et 52 semaines.";
            } elseif (empty($_POST['site_entreprise_id'])) {
                $error = "L'entreprise est obligatoire.";
            }
            if ($error) {
                $sites = $offerModel->getSites();
                $this->render('offers/create.html.twig', ['error' => $error, 'sites' => $sites]);
                return;
            }

            $data = [
                'titre' => $_POST['titre'],
                'description' => $_POST['description'],
                'gratification' => $_POST['gratification'],
                'date_debut' => $_POST['date_debut'],
                'duree_semaines' => $_POST['duree_semaines'],
                'site_entreprise_id' => $_POST['site_entreprise_id']
            ];
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
            $error = null;
            if (!\App\Core\Csrf::verify()) {
                $error = 'Jeton CSRF invalide';
            } elseif (empty($_POST['titre'])) {
                $error = "Le titre est obligatoire.";
            } elseif (empty($_POST['date_debut'])) {
                $error = "La date de début est obligatoire.";
            } elseif (empty($_POST['duree_semaines']) || $_POST['duree_semaines'] < 1 || $_POST['duree_semaines'] > 52) {
                $error = "La durée doit être entre 1 et 52 semaines.";
            }
            if ($error) {
                $offer = $offerModel->getOfferById($id);
                $sites = $offerModel->getSites();
                $this->render('offers/edit.html.twig', ['error' => $error, 'offer' => $offer, 'sites' => $sites]);
                return;
            }
            
            $data = [
                'titre' => $_POST['titre'],
                'description' => $_POST['description'],
                'gratification' => $_POST['gratification'],
                'date_debut' => $_POST['date_debut'],
                'duree_semaines' => $_POST['duree_semaines'],
                'site_entreprise_id' => $_POST['site_entreprise_id']
            ];
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
            if (!\App\Core\Csrf::verify()) {
                $this->render('offers/show.html.twig', ['error' => 'Jeton CSRF invalide']);
                return;
            }
            
            $id = $_POST['id'];
            $offerModel = new OfferModel();
            $offers = $offerModel->delete($id);
            header('Location: /offers');
            exit;
        }
    }
}