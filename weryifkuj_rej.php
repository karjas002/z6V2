<?php
    $dbhost="jaskowiak.tmultimedialne.pl"; $dbuser="tmultimedialne_jaskowiak"; $dbpassword="Jaskowiakdb1!"; $dbname="tmultimedialne_jaskowiakdb1";

    $user = htmlentities ($_POST['user'], ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $user
    $pass = htmlentities ($_POST['pass'], ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $pass
    $pass1 = htmlentities ($_POST['pass1'], ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $pass
    $link = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname); // połączenie z BD – wpisać swoje dane
    if (!$link) {
        echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
    } // obsługa błędu połączenia z BD
    mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków
    $result = mysqli_query($link, "SELECT * FROM 6users WHERE username='$user'"); // wiersza, w którym login=login z formularza
    $rekord = mysqli_fetch_array($result); // wiersza z BD, struktura zmiennej jak w BD
    if (!$rekord) //Jeśli brak, to nie ma użytkownika o podanym loginie
    {
        if($pass == $pass1) {
            mysqli_query($link, "INSERT INTO 6users (username, password) VALUES ('$user', '$pass');");
            mkdir("uploads/".$user, 0777, true);
            /*mysqli_query($link, "insert into 6logi (idu, licznik) values (2, 0)");
            $result = mysqli_query($link, "SELECT * FROM users WHERE username='$user'");
            $rekord = mysqli_fetch_array($result);
            $id = $rekord['id'];
            mysqli_query($link, "INSERT INTO 6logi (idu, date, licznik) VALUES ('$id', '0') ") or die("DB error: $dbname");*/
            echo "konto stworzone";
            echo "<a href='index.php'>Powrót/a>";
            
        } else echo "Wpisane hasła muszą być takie same!";
        
        
    } else {
        print 'ten uzytkownik juz istnieje';
    }
?>