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
use App\Controllers\back\SponsorController;
$router = new Router();
// Security::secureHeaders();
// inddex
$router->addRoute('GET', '/', HomeController::class, 'index');
$router->addRoute('GET', '/admin/users', \App\Controllers\back\UserController::class, 'listUsers');
$router->addRoute('GET', '/login', LoginController::class, 'showLoginForm');
$router->addRoute('POST', '/login', LoginController::class, 'login');
$router->addRoute('GET', '/logout', LoginController::class, 'logout');
$router->addRoute('GET', '/dashboard', \App\Controllers\back\DashboardController::class, 'index');
$router->addRoute('GET', '/signup', \App\Controllers\back\RegisterController::class, 'showRegisterForm');
$router->addRoute('POST', '/signup', \App\Controllers\back\RegisterController::class, 'register');
$router->addRoute('POST', '/admin/users/update', \App\Controllers\back\UserController::class, 'updateUserStatus');
$router->addRoute('POST', '/admin/users/delete', \App\Controllers\back\UserController::class, 'deleteUser');
//event creation routing
$router->addRoute('GET', '/events/create', EventController::class, 'showCreateForm');
$router->addRoute('POST', '/events/create', EventController::class, 'create');
$router->addRoute('GET', '/events/show/{id}', EventController::class, 'show');
$router->addRoute('GET', '/events', EventController::class, 'listEvents');
$router->addRoute('GET', '/events/edit/{id}', EventController::class, 'showEditForm');
$router->addRoute('POST', '/events/edit', EventController::class, 'edit');
$router->addRoute('GET', '/admin/categories', \App\Controllers\Back\CategoryController::class, 'listCategories');
$router->addRoute('POST', '/categories/create', \App\Controllers\Back\CategoryController::class, 'createCategory');
$router->addRoute('POST', '/admin/category/delete', \App\Controllers\Back\CategoryController::class, 'deleteCategory');
// $router->addRoute('POST', '/admin/category/update', \App\Controllers\Back\CategoryController::class, 'updateCategory');
$router->addRoute('GET', '/admin/category/edit', \App\Controllers\Back\CategoryController::class, 'editCategory');
$router->addRoute('POST', '/admin/category/update', \App\Controllers\Back\CategoryController::class, 'updateCategory');
$router->addRoute('GET', '/admin/tags', \App\Controllers\Back\TagController::class, 'listTags');
$router->addRoute('POST', '/tags/create', \App\Controllers\Back\TagController::class, 'createTag');
$router->addRoute('POST', '/admin/tag/delete', \App\Controllers\Back\TagController::class, 'deleteTag');
$router->addRoute('POST', '/admin/tag/update', \App\Controllers\Back\TagController::class, 'updateTag');
$router->addRoute('GET', '/admin/tag/edit', \App\Controllers\Back\TagController::class, 'editTag');
$router->addRoute('GET', '/admin/sponsors', \App\Controllers\Back\SponsorController::class, 'index_Sponsor');
$router->addRoute('POST', '/admin/sponsors', \App\Controllers\Back\SponsorController::class, 'store');
$router->addRoute('POST','/admin/sponsors/update', \App\Controllers\Back\SponsorController::class, 'updateSponsor');
$router->addRoute('POST', '/admin/sponsors/delete', \App\Controllers\Back\SponsorController::class, 'deleteSponsor');
$router->addRoute('GET','/choose-role', \App\Controllers\back\ChooseRoleController::class,'showRoleSelection');
$router->addRoute('POST','/set-role', \App\Controllers\back\ChooseRoleController::class,'setRole');

$router->dispatch();
