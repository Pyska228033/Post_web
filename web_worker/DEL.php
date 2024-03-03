<?php
include("bd.php");
session_start();
$idWorker = $_GET['idWorker'];

$SQLstring = "DELETE FROM worker WHERE idWorker = '$idWorker'";
mysqli_query($dblink, $SQLstring);
$dblink->close();
http_response_code(200);
?>