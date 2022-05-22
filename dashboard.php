<?php  
    session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
    if (!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit();
    }

    echo "Jesteś zalogowany jako: ".$_SESSION['user'];
    echo "       <a href='logout.php'><button>Logout</button></a>";
    $path = $_SESSION['path'];
    echo "<br> Twoja ścieżka: ".$_SESSION['path'];
    if (!empty($_POST['wyswietlanie'])) {
        $_SESSION['filter'] = $_POST['wyswietlanie'];
    }

    $filter = $_SESSION['filter'];
    $mp3icon = 'mp3.svg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jaśkowiak</title>
</head>
<body>
    <h2>Wyświetlanie</h2>
<form action="" method="post">
        <select name="wyswietlanie">
            <option value="lista">lista</option>
            <option value="miniatury">miniatury</option>
        </select>
        <input type="submit" value="Zmień">
    </form>
    <h2>Utwórz nowy folder</h2>

    <form action="utworzKatalog.php" method="post">
        <input type="text" name="folder">
        <input type="submit" value="Utwórz">
    </form>



<?php
    

    $target = __DIR__ . DIRECTORY_SEPARATOR . $path;
    if ($fh = opendir($target)) {
        while (($entry = readdir($fh)) !== false) {
            if ($entry != "." && $entry != "..") {
                $plik = $target . DIRECTORY_SEPARATOR . $entry;
                if ($filter == 'lista') {
                    if (is_dir($plik)) {
                        echo
                        '<form class="filesList" action="zmienKatalog.php" method="post">
                        <input type="hidden" name="path" value="' . $entry . '">';
                        echo '<button type="submit" value="' . $entry . '">' . $entry . '</button></form>';
                    } else {
                        echo '<a class = "files" href="' . $path . '/' . $entry . '" download>' . $entry .
                            '</a> <br>';
                    }
                }  else if ($filter == 'miniatury') {
                    echo
                    '<form class="filesList" action="zmienKatalog.php" method="post">
                        <input type="hidden" name="path" value="' . $entry . '">';
                    if (is_dir($plik)) {
                        echo 'Folder: <button type="submit" value="' . $entry . '">' . $entry . '</button></form>';
                    } else {
                        if (str_ends_with($plik, '.jpg') || str_ends_with($plik, '.png') || str_ends_with($plik, '.gif') || str_ends_with($plik, '.jpeg')  ) {
                            echo '<a class = "thumb" href="' . $path . '/' . $entry . '" download>' .
                                '<img class="icons" width="50px" height="50px" src="' . $path . "/" .  $entry . '">' .
                                '<br>' . $entry . '</a> <br>';
                        } else if (str_ends_with($plik, '.avi') || str_ends_with($plik, '.wav') || str_ends_with($plik, '.mp3') ) {
                            echo '<a href="' . $path . '/' . $entry . '" download>' . '<img width="50px" height="50px" src="'.$mp3icon.'"> ' . $entry .
                                '</a> <br>';
                        }else {
                            echo '<a class = "files" href="' . $path . '/' . $entry . '" download>' .
                                $entry .
                                '</a> <br>';
                        }
                    }
                }
            }
        }
        closedir($fh);
    }
    
    ?>
        <form action="katalogBack.php" method="post">
        <input type="submit" value="Wstecz" name="submit">
    </form>
        Prześlij plik
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" id="filetoUpload">
        <input type="submit" value="Wyślij plik" name="submit">
    </form>
    <br>

</body>
</html>