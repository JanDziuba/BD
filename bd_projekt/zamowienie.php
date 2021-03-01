<html lang="pl-PL">

<head>
  <title> Zamówienie </title>
</head>

<body>
<h2> Zamówienie </h2>

<?php
include "header.php";
session_start();
$order_msg = "";

$conn = oci_connect($_SESSION['DB_LOGN'], $_SESSION['DB_PASW'],
    "//labora.mimuw.edu.pl/LABS");
if (!$conn) {
  echo "oci_connect failed\n";
  $e = oci_error();
  echo $e['message'];
}

if (isset($_GET['seans_id'])) {
  if (isset($_SESSION['KLIENT_ID'])) {

    $sqlText = "SELECT * FROM SEANS WHERE ID =" . $_GET['seans_id'];
    $stmt = oci_parse($conn, $sqlText);
    oci_execute($stmt);

    if ($row = oci_fetch_array($stmt, OCI_BOTH)) {
      if ($row['LICZBA_WOLNYCH_MIEJSC'] <= 0) {
        $order_msg = "Brak wolnych miejsc<br>";
      } else {
        $sqlText = "UPDATE SEANS SET LICZBA_WOLNYCH_MIEJSC = 
        LICZBA_WOLNYCH_MIEJSC - 1 WHERE ID =" . $_GET['seans_id'];
        $stmt = oci_parse($conn, $sqlText);
        oci_execute($stmt);

        $sqlText = "SELECT COALESCE(MAX(ID) + 1, 1) AS ID FROM ZAMOWIENIE";
        $stmt = oci_parse($conn, $sqlText);
        oci_execute($stmt);
        if ($row = oci_fetch_array($stmt, OCI_BOTH)) {
          $id = $row['ID'];

          $sqlText = "INSERT INTO ZAMOWIENIE VALUES (" . $id . ", 1, " .
              $_SESSION['KLIENT_ID'] . ", " . $_GET['seans_id'] . ")";
          $stmt = oci_parse($conn, $sqlText);
          oci_execute($stmt);
          $order_msg = "Kupiłeś bilety<br>";
        } else {
          echo "błąd" . __LINE__;
        }
      }
    } else {
      echo "błąd" . __LINE__;
    }
  } else {
    $order_msg = "Zaloguj się żeby kupić bilety<br>";
  }
}

echo $order_msg;

// Pokaż wszystkie zamówienia użytkownika.
if (isset($_SESSION['KLIENT_ID'])) {
  echo "<br>WSZYSTKIE ZAMÓWIENIA<br><br>";
  $sqlText = "SELECT FILM_ID, TYTUL, to_char(CZAS_ROZPOCZECIA, 'DD-MM-YYYY') AS DZIEN,
       to_char(CZAS_ROZPOCZECIA, 'HH24:MI:SS') AS GODZINA, NUMER_SALI, 
       LICZBA_MIEJSC  FROM ZAMOWIENIE
    INNER JOIN SEANS ON ZAMOWIENIE.SEANS_ID = SEANS.ID
    INNER JOIN FILM ON SEANS.FILM_ID = FILM.ID
    WHERE KLIENT_ID =" . $_SESSION['KLIENT_ID'];
  $stmt = oci_parse($conn, $sqlText);
  oci_execute($stmt);

  while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
    echo "<table style='border: 1px solid black;'><td>" .
        "<a HREF=\"film.php?id=" . $row['FILM_ID'] . "\">" .
        $row['TYTUL'] . "</a><br><br>" .
        "DZIEŃ: " . $row['DZIEN'] . "<br><br>" .
        "GODZINA: " . $row['GODZINA']  . "<br><br>" .
        "SALA: " . $row['NUMER_SALI'] . "<br><br>" .
        "LICZBA MIEJSC: " . $row['LICZBA_MIEJSC'] .
        "<br><br></td></table>";
  }
}

oci_commit($conn);
oci_close($conn);
?>

</body>
</html>
