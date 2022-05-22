<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit();
}
$path = $_SESSION['path'];
$target_dir = $path;
$target_file = $target_dir . "/" . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
echo "Typ pliku: " . $imageFileType . "\n";

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 10000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if (
    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" &&
    $imageFileType != "avi" && $imageFileType != "mp4" && $imageFileType != "mp3" && $imageFileType != "wav"
) {

    echo "Sorry, only JPG, JPEG, PNG, GIF, MP3, WAV, AVI & MP4 files are allowed.";
    $uploadOk = 0;
}

if ($uploadOk == 1 && move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " uploaded.";

    $time = date('H:i:s', time());
    $user = $_SESSION['user'];
    $post = $target_file;

    header('Location: dashboard.php');
} else {
    echo "Error uploading file.\n";
    echo '<a href="dashboard.php">Wróć</a>';
}
