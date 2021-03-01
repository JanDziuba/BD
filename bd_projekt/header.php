<?php
session_start();

if (isset($_SESSION['EMAIL'])) {
  echo "Zalogowany jako: ". $_SESSION['EMAIL']. "<br><br>";
} else {
  echo "Nie zalogowany <br><br>";
}

echo "<form action='login.php' method='post'><button class='button' 
        type='submit'>zaloguj się</button></form>";

echo "<form action='register.php' method='post'><button class='button' 
        type='submit'>rejestracja</button></form>";

echo "<a href=\"kino.php\">KINO</a><br><br>";

echo "<a href=\"zamowienie.php\">MOJE ZAMÓWIENIA</a><br><br><hr>";

