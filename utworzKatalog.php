<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit();
}
$file = $_SESSION['path'] . DIRECTORY_SEPARATOR . $_POST['folder'];
if (!file_exists($file) && dirname($_SESSION['path'], 5) != 'uploads') {

    mkdir($file, 0777, true);
    header('location: dashboard.php');
} else {
    echo "<h1>folder już istnieje lub osiągnieto limit głębokosci folderów</h1>";
    echo '<a href="dashboard.php">Wróć</a>';
}
