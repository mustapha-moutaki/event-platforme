<?php

namespace App\Controllers\Back;

use App\Core\Controller;
use App\Models\Sponsor;
use App\core\View;

class SponsorController
{
    public function index_Sponsor()
    {
        $sponsorModel = new Sponsor();
        $sponsors = $sponsorModel->getAllSponsors();
        $totalsponsors=$sponsorModel->countSponsors();
        $view=new  View();
        $view->render('sponsors/sponsors.twig', ['sponsors' => $sponsors
                   ,'totalSponsors'=>$totalsponsors]);
    }

    public function store()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $name = $_POST["name"] ?? '';
            if (!empty($_FILES["image"]["name"])) {
                $uploadDir = __DIR__ . '/../../../public/uploads/';
                $fileName = time() . '_' . basename($_FILES["image"]["name"]);
                $uploadFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $uploadFile)) {
                    $imagePath = '/uploads/' . $fileName;
                    $sponsorModel = new Sponsor();
                    $sponsorModel->createSponsor($name, $imagePath);
                    header("Location: /admin/sponsors");
                    exit;
                } else {
                    echo "Erreur lors de l'upload de l'image.";
                }
            } else {
                echo "Veuillez choisir une image.";
            }
        }
    }
    public function updateSponsor() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];

            $sponsorModel = new Sponsor();
            $sponsor = $sponsorModel->findById($id);

            if (!$sponsor) {
                die("Sponsor non trouvé !");
            }

            $data = ['name' => $name];

            // Gestion de l'upload de l'image
            if (!empty($_FILES['image']['name'])) {
                $imageName = time() . '_' . $_FILES['image']['name'];
                $uploadDir = __DIR__ . '/../../../public/uploads/';
                $uploadFile = $uploadDir . basename($imageName);

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    $data['image_url'] ='/uploads/' . $imageName;
                   
                } else {
                    die("Erreur lors de l'upload de l'image !");
                }
            }


            $sponsorModel->update($id, $data);

            header("Location: /admin/sponsors");
            exit;
        }
    }
    public function deleteSponsor()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;

        if ($id) {
            $sponsorModel = new Sponsor();
            $sponsor = $sponsorModel->findById($id);

            if ($sponsor) {
                
                $imagePath = __DIR__ . '/../../../public' . $sponsor['image_url'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $sponsorModel->delete($id);
            }
        }
    }
    header("Location: /admin/sponsors");
    exit;
}

}

