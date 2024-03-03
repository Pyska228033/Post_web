
<?php
include("bd.php");
session_start();

$user_number = (int)$_SESSION['user_number'];
$idWorker = (int)$_SESSION['idWorker'];

$SQLstring = "UPDATE message SET locked = 0 WHERE sender = '$user_number'";
mysqli_query($dblink, $SQLstring);

//http_response_code(200);
?>