<?php

namespace App\Controllers\back;

use App\core\Auth;
use App\core\View;
use App\models\Sponsor;
use App\models\Tag;
use App\models\Category;
use App\models\User;

class DashboardController {
    public function index() {
        if (!Auth::check()) {
            header("Location: /login");
            exit;
        }

        $user = Auth::user();
        $view = new View();

        $tag=new Tag();
        $totalTags = $tag->countTags();

        $category=new category();
        $totalCategories = $category->countCategories();

        $sponsorModel=new sponsor();
        $totalsponsors=$sponsorModel->countSponsors();

        $users = new User();
        $totalUsers = $users->getUserStatistics();
    

        $view->render('dashboard.twig', [
            'username' => $user['username'],'totalSponsors'=>$totalsponsors,'totalTags' => $totalTags, 'totalUsers' => $totalUsers,'totalCategories' => $totalCategories
            
        ]);
    }
}