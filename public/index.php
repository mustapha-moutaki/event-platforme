<?php
require realpath(__DIR__."/../vendor/autoload.php");
require_once __DIR__ . '/../App/config/config.php';
require_once __DIR__ . '/../App/core/Security.php';

// $router = require_once __DIR__ . '/../App/config/routes.php';
use App\core\Router;
use App\core\Security;

use App\Controllers\Front\HomeController;
use App\Controllers\back\LoginController;
$router = new Router();
Security::secureHeaders();
$router->addRoute('GET', '/', HomeController::class, 'index');
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

 $router->dispatch();
