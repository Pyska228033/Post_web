
<?php
include("bd.php");
session_start();

$user_number = (int) $_SESSION['idUser'];

$SQLstring = "UPDATE message SET locked = 0 WHERE sender = '$user_number'";
mysqli_query($dblink, $SQLstring);

//http_response_code(200);
?>