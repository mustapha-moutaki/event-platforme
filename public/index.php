<?php
require realpath(__DIR__."/../vendor/autoload.php");
require_once __DIR__ . '/../App/config/config.php';
// require_once __DIR__ . '/../App/core/Security.php';



// $router = require_once __DIR__ . '/../App/config/routes.php';
use App\core\Router;
// use App\core\Security;

use App\Controllers\Front\HomeController;
use App\Controllers\back\LoginController;
use App\Controllers\front\EventController;
$router = new Router();
// Security::secureHeaders();
// inddex
// $router->addRoute('GET', '/', HomeController::class, 'index');
$router->addRoute('GET', '/', EventController::class, 'showAllEvents');
$router->addRoute('GET', '/admin/users', \App\Controllers\back\UserController::class, 'listUsers');
$router->addRoute('GET', '/login', LoginController::class, 'showLoginForm');
$router->addRoute('POST', '/login', LoginController::class, 'login');
$router->addRoute('GET', '/logout', LoginController::class, 'logout');
$router->addRoute('GET', '/dashboard', \App\Controllers\back\DashboardController::class, 'index');
$router->addRoute('GET', '/signup', \App\Controllers\back\RegisterController::class, 'showRegisterForm');
$router->addRoute('POST', '/signup', \App\Controllers\back\RegisterController::class, 'register');
$router->addRoute('GET', '/articles', \App\Controllers\back\ArticleController::class, 'listArticles');
$router->addRoute('GET', '/article/new', \App\Controllers\back\ArticleController::class, 'showForm');
$router->addRoute('POST', '/article/add', \App\Controllers\back\ArticleController::class, 'insertArticle');
$router->addRoute('POST', '/admin/users/update', \App\Controllers\back\UserController::class, 'updateUserStatus');
$router->addRoute('POST', '/admin/users/delete', \App\Controllers\back\UserController::class, 'deleteUser');
//event creation routing
$router->addRoute('GET', '/events/create', EventController::class, 'showCreateForm');
$router->addRoute('POST', '/events/create', EventController::class, 'create');
$router->addRoute('GET', '/events/show/{id}', EventController::class, 'show');
$router->addRoute('GET', '/events', EventController::class, 'listEvents');
$router->addRoute('GET', '/events/edit/{id}', EventController::class, 'showEditForm');
$router->addRoute('POST', '/events/edit/{id}', EventController::class, 'edit');
$router->addRoute('POST', '/events/delete/{id}', EventController::class, 'delete');
$router->addRoute('GET', '/events/cities/{regionId}', EventController::class, 'getCitiesByRegion');


$router->addRoute('GET', '/admin/categories', \App\Controllers\Back\CategoryController::class, 'listCategories');
$router->addRoute('POST', '/categories/create', \App\Controllers\Back\CategoryController::class, 'createCategory');
$router->addRoute('POST', '/admin/category/delete', \App\Controllers\Back\CategoryController::class, 'deleteCategory');
// $router->addRoute('POST', '/admin/category/update', \App\Controllers\Back\CategoryController::class, 'updateCategory');
$router->addRoute('GET', '/admin/category/edit', \App\Controllers\Back\CategoryController::class, 'editCategory');
$router->addRoute('POST', '/admin/category/update', \App\Controllers\Back\CategoryController::class, 'updateCategory');


 $router->dispatch();
