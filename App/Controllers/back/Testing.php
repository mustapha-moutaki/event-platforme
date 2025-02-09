<?php
// Include the autoload file (if you're using Composer)
require __DIR__ . '/../vendor/autoload.php';

// Initialize the Twig environment
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../view');
$twig = new \Twig\Environment($loader);

// Render a simple template
echo $twig->render('test_template.twig', ['name' => 'World']);
?>