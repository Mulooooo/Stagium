<?php
namespace App\Controllers;
use App\Models\CompanyModel;
use App\Models\OfferModel;
use App\Models\EvaluationModel;

class CompanyController extends Controller{
    public function index(){
        $page = $_GET['page'] ?? 1;
        $limit = 6;
        $companyModel = new CompanyModel();
        $filters["q"] = $_GET['q'] ?? '';
        $companies = $companyModel->searchCompanies($filters, $page, $limit);
        $total = $companies['total'];
        $items = $companies['items'];
        $totalPages = ceil($total / $limit);
        $this->render("companies/index.html.twig", ['companies' => $items, 'total_pages' => $totalPages, 'current_page' => $page, 'filters' => $filters]);
    }
    public function show(){
        $id = $_GET['id'] ?? null;
        if (!$id) { 
            header('Location: /companies'); 
            exit;
        }
        $companyModel = new CompanyModel();
        $company = $companyModel->findById($id);
        $sites = $companyModel->getSitesByCompany($id);

        $offerModel = new OfferModel();
        $offers = $offerModel->getOffersByCompany($id);

        $evaluationModel = new EvaluationModel();
        $evaluations = $evaluationModel->getByEntreprise($id);
        $this->render("companies/show.html.twig", ['company' => $company, 'offers' => $offers, 'evaluations' => $evaluations, 'sites' => $sites]);
    }

    public function create(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\App\Core\Csrf::verify()) {
                $this->render('companies/create.html.twig', ['error' => 'Jeton CSRF invalide']);
                return;
            }

            $data = $_POST;
            $companyModel = new CompanyModel();
            $company = $companyModel->create($data);
            header('Location: /companies');
            exit;
        }
        $this->render("companies/create.html.twig");
    }
    public function edit(){
        $id = $_GET['id'] ?? null;
        if (!$id) { 
            header('Location: /companies'); 
            exit;
        }
        $companyModel = new CompanyModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\App\Core\Csrf::verify()) {
                $this->render('companies/edit.html.twig', ['error' => 'Jeton CSRF invalide']);
                return;
            }

            $data = $_POST;
            $company = $companyModel->update($id, $data);
            header('Location: /companies');
            exit;
        }
        $company = $companyModel->findById($id);
        $this->render("companies/edit.html.twig", ['company' => $company]);
    }
    public function delete(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\App\Core\Csrf::verify()) {
                $this->render('companies/edit.html.twig', ['error' => 'Jeton CSRF invalide']);
                return;
            }

            $id = $_POST['id'];
            $companyModel = new CompanyModel();
            $companies = $companyModel->delete($id);
            header('Location: /companies');
            exit;
        }
    }

    public function createSite() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\App\Core\Csrf::verify()) {
                $this->render('companies/show.html.twig', ['error' => 'Jeton CSRF invalide']);
                return;
            }

            $entrepriseId = $_POST['entreprise_id'];
            $companyModel = new CompanyModel();
            $companyModel->createSite(
                $entrepriseId,
                $_POST['nom_site'],
                $_POST['ville'],
                $_POST['code_postal'],
                $_POST['rue'],
                $_POST['siret'],
                $_POST['numero'],
                $_POST['pays']
            );
            header('Location: /companies/show?id=' . $entrepriseId);
            exit;
        }
    }
}