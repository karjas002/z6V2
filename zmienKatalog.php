<?php
session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit();
}
$path = $_POST['path'];
$_SESSION['path'] = $_SESSION['path'] . DIRECTORY_SEPARATOR . $path;

header('Location: dashboard.php');
