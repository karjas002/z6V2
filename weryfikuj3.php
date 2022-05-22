<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<HEAD>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</HEAD>
<BODY>
<?php
 $dbhost="jaskowiak.tmultimedialne.pl"; $dbuser="tmultimedialne_jaskowiak"; $dbpassword="Jaskowiakdb1!"; $dbname="tmultimedialne_jaskowiakdb1";
 $user = htmlentities ($_POST['user'], ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $user
 $pass = htmlentities ($_POST['pass'], ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $pass
 $link = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);// połączenie z BD – wpisać swoje dane
 if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
 mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków
 $result = mysqli_query($link, "SELECT * FROM 6users WHERE username='$user'"); // wiersza, w którym login=login z formularza
 $rekord = mysqli_fetch_array($result); // wiersza z BD, struktura zmiennej jak w BD
 $id = $rekord['id'];
 if (!$rekord) {
  mysqli_close($link);
  echo "Brak użytkownika o takim 6loginie !";
} else { 
    $dateNow = date('Y-m-d H:i:s');
    $count = mysqli_query($link, "SELECT licznik FROM 6logi where idu = '$id'") or die("DB error: $dbname");

          if (mysqli_num_rows($count) == 0) {
              mysqli_query($link, "INSERT INTO 6logi (idu, date, licznik) VALUES ('$id', '$dateNow', '1') ") or die("DB error: $dbname");
              mysqli_query($link, "INSERT INTO ostrzezenie (idu, date, czyWys) VALUES ('$id', '$dateNow', '1') ") or die("DB error: $dbname");
              echo "dupa";
          }
    /*$dateNow = date('Y-m-d H:i:s');
    $count = mysqli_query($link, "SELECT licznik FROM 6logi where idu = '$id'") or die("DB error: $dbname");

          if (mysqli_num_rows($count) == 0) {
              mysqli_query($link, "INSERT INTO 6logi (idu, date, licznik) VALUES ('$id', '$dateNow', '1') ") or die("DB error: $dbname");
          }*/
  $isBlocked = mysqli_query($link, "SELECT * FROM 6logi WHERE idu='$id'");
  $row = mysqli_fetch_array($isBlocked);

  $start = new DateTime($row['date']);
  $end = new DateTime(date('Y-m-d H:i:s'));

  $time = $end->getTimestamp() - $start->getTimestamp();

  if ($row['licznik'] == 3 && $time <= 30) {

      $ip = isset($_SERVER['HTTP_CLIENT_IP'])
          ? $_SERVER['HTTP_CLIENT_IP']
          : (isset($_SERVER['HTTP_X_FORWARDED_FOR'])
              ? $_SERVER['HTTP_X_FORWARDED_FOR']
              : $_SERVER['REMOTE_ADDR']);

      echo 'konto zablokowane na 30 sekund';
      mysqli_query($link, "INSERT INTO ostrzezenie (idu,ip,czyWys) VALUES 
       ('$id', '$ip' ,'0')") or die("DB error: $dbname");
       echo '<br><a href="loguj.php">Cofnij do logowania</a>';
  }
  else if ($row['licznik'] == 3 && $time > 30 || $row['licznik'] < 3) {
      if ($row['licznik'] == 3 && $time > 30)
          mysqli_query($link, "UPDATE 6logi SET licznik = '0' where idu = '$id'") or die("DB error: $dbname");

      if ($rekord['password'] == $pass) {
        /*$dateNow = date('Y-m-d H:i:s');
        $count = mysqli_query($link, "SELECT licznik FROM 6logi where idu = '$id'") or die("DB error: $dbname");
        if (mysqli_num_rows($count) == 0) {
            mysqli_query($link, "INSERT INTO 6logi (idu, date, licznik) VALUES ('$id', '$dateNow', '1') ") or die("DB error: $dbname");
            mysqli_query($link, "INSERT INTO ostrzezenie (idu, date, licznik) VALUES ('$id', '$dateNow', '1') ") or die("DB error: $dbname");
        }*/

          $czyWys = mysqli_query($link, "SELECT * FROM ostrzezenie WHERE idu='$id' ORDER BY id DESC LIMIT 1");
          $row2 = mysqli_fetch_array($czyWys);

          session_start();
          $_SESSION['loggedin'] = true;
          $_SESSION['user'] = $rekord['username'];
          $_SESSION['path'] = "uploads/" . $user; 
          $_SESSION['filter'] = 'lista';

          if ($row2['czyWys'] == 0) {
            echo '<h1 style="color:red">Próba włamania!!!</h1><br>';
              echo '<h1 style="color:red">Ktoś chciał się zalogować na twoje konto z IP: ' .
                  $row2['ip'] . ' w dniu ' . $row2['date'] . '</h1>';
              echo '<a href="dashboard.php">Przejdź dalej</a>';
              mysqli_query($link, "UPDATE ostrzezenie SET czyWys = '1' where idu='$id' ORDER BY id DESC LIMIT 1") or die("DB error: $dbname");
          } else {
              header('Location: dashboard.php');
          }
      } else { 
          $dateNow = date('Y-m-d H:i:s');
          $count = mysqli_query($link, "SELECT licznik FROM 6logi where idu = '$id'") or die("DB error: $dbname");

          if (mysqli_num_rows($count) == 0) {
              mysqli_query($link, "INSERT INTO 6logi (idu, date, licznik) VALUES ('$id', '$dateNow', '1') ") or die("DB error: $dbname");
          } else {
              $num = mysqli_fetch_array($count);
              $number = $num[0];
              $number++;
              mysqli_query($link, "UPDATE 6logi SET licznik = '$number', date = '$dateNow' where idu = '$id'") or die("DB error: $dbname");
          }

          echo "Błąd w haśle ! <br><br>";
          echo '<a class="back" href="loguj.php">Wróć</a>';
      }
  }
}
?>
</BODY>
</HTML>

