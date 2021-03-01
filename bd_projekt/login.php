<html lang="pl-PL">

<head>
  <title> Login </title>
</head>

<body>
<h2> Login </h2>

<?php
session_start();

$conn = oci_connect($_SESSION['DB_LOGN'], $_SESSION['DB_PASW'],
    "//labora.mimuw.edu.pl/LABS");
if (!$conn) {
  echo "oci_connect failed\n";
  $e = oci_error();
  echo $e['message'];
}

$login_err = "";
if (isset($_POST['EMAIL']) && isset($_POST['PASSWORD'])) {
  $email = $_POST['EMAIL'];
  $password = $_POST['PASSWORD'];

  $sqlText = "SELECT * FROM KLIENT WHERE EMAIL = " . "'" . "$email" . "'" .
      "AND HASLO = " . "'" . "$password" . "'";
  $stmt = oci_parse($conn, $sqlText);
  oci_execute($stmt);

  if ($row = oci_fetch_array($stmt, OCI_BOTH)) {
    $_SESSION['EMAIL'] = $email;
    $_SESSION['KLIENT_ID'] = $row['ID'];
  } else {
    unset($_SESSION['EMAIL']);
    unset($_SESSION['KLIENT_ID']);
    $login_err = "niepoprawne dane logowania<br>";
  }
}
?>

<?php include "header.php"; ?>

<form action="login.php" method="post">
  E-mail: <input type="text" name="EMAIL" value=""><br><br>
  Hasło: <input type="password" name="PASSWORD" value=""><br><br>
  <input type="submit" value="zaloguj">
</form>

<?php
echo $login_err;

oci_commit($conn);
oci_close($conn);
?>

</body>
</html>
