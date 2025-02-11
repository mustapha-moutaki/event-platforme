<?php
// include '../../vendor/autoload.php';
use Config\Database;
use Models\User;
if (isset($_SESSION['user_id']) ?? 'User') {
  $db = Database::makeConnection(); 
  $userModel = new User($db);

  $userId = $_SESSION['user_id'];  

  $user = $userModel->getUserById($userId);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
     sidebar
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="../../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>
<body>
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="/Plateforme-de-Cours-en-Ligne-Youdemy/public/dashboard.php" target="_blank">
        <!-- <img src="../assets/img/logos/" class="navbar-brand-img" width="26" height="26" alt="main_logo"> -->
        <span class="ms-1 text-sm text-dark"><strong>YOUDEMY</strong>courses</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="http://localhost/Plateforme-de-Cours-en-Ligne-Youdemy/public/dashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>

        <?php if (isset($user['role']) && $user['role'] == 'admin'): ?>
        <li class="nav-item">
          <a class="nav-link text-dark" href="http://localhost/Plateforme-de-Cours-en-Ligne-Youdemy/views/teacher/manageTeachers.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Manage Teachers</span>
          </a>
        </li>
        <?php endif; ?>

        <?php if (isset($user['role']) && $user['role'] == 'student'): ?>
        <li class="nav-item">
          <a class="nav-link text-dark" href="/Plateforme-de-Cours-en-Ligne-Youdemy/views/Student/mycourses.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">My Courses</span>
          </a>
        </li>
          <?php endif;?>

        <?php if (isset($user['role']) && $user['role'] == 'admin'): ?>
        <li class="nav-item">
          <a class="nav-link text-dark" href="http://localhost/Plateforme-de-Cours-en-Ligne-Youdemy/views/student/manageStudents.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Manage Students</span>
          </a>
        </li>
        <?php endif; ?>

        <?php if (isset($user['role'], $user['status']) && 
          ($user['role'] == 'admin') && 
          $user['status'] == 'active'): ?>
        <li class="nav-item">
          <a class="nav-link text-dark" href="http://localhost/Plateforme-de-Cours-en-Ligne-Youdemy/views/courses/manageCourses.php">
            <i class="material-symbols-rounded opacity-5">view_in_ar</i>
            <span class="nav-link-text ms-1">Manage courses</span>
          </a>
        </li>
          <?php endif; ?>



          
        <?php if (isset($user['role'], $user['status']) && 
          ($user['role'] == 'teacher') && 
          $user['status'] == 'active'): ?>
        <li class="nav-item">
          <a class="nav-link text-dark" href="http://localhost/Plateforme-de-Cours-en-Ligne-Youdemy/views/Teacher/manageCourses.php">
            <i class="material-symbols-rounded opacity-5">view_in_ar</i>
            <span class="nav-link-text ms-1">Manage courses</span>
          </a>
        </li>
          <?php endif; ?>

          

          

          <?php if (isset($user['role']) && $user['role'] == 'admin'): ?>
        <li class="nav-item">
        <a class="nav-link text-dark" href="http://localhost/Plateforme-de-Cours-en-Ligne-Youdemy/views/Categories/manageCategories.php">
        <i class="material-symbols-rounded opacity-5">school</i>
     <span>Manage Categories</span>
        </a>
        </li>
           <?php endif; ?>

           <?php if (isset($user['role']) && $user['role'] == 'admin'): ?>
          
         
        <li class="nav-item">
  <a class="nav-link text-dark" href="http://localhost/Plateforme-de-Cours-en-Ligne-Youdemy/views/tags/manageTags.php">
    <i class="material-symbols-rounded opacity-5">label</i>
    <span class="nav-link-text ms-1">Manage Tags</span>
  </a>
</li>
 <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/notifications.html">
            <i class="material-symbols-rounded opacity-5">notifications</i>
            <span class="nav-link-text ms-1">Notifications</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account pages</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/profile.html">
            <i class="material-symbols-rounded opacity-5">person</i>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
       
       
      </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">
    
      <a class="btn bg-gradient-dark w-100 nav-link text-dark" href="/Plateforme-de-Cours-en-Ligne-Youdemy\public\logout.php">
            <i class="material-symbols-rounded opacity-5 text-light">logout</i>
            <span class="nav-link-text ms-1 text-white">logout</span>
          </a>
    </div>

  </aside>
</body>
</html>
