<?php
session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit();
}
if (dirname($_SESSION['path'], 1) != 'uploads') {
    $_SESSION['path'] = dirname($_SESSION['path'], 1);
}
header('Location: dashboard.php');
