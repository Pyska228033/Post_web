<?php
$servername = "localhost";
$username = "root";
$password_bd = "mysql";
$dbName = "mail";

$dblink = mysqli_connect($servername, $username, $password_bd, $dbName);
mysqli_set_charset($dblink, "utf8");

if (!$dblink) {
    die("Ошибка подключения: " . mysqli_connect_error());
}
?>
