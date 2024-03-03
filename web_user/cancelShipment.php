<?php
include("bd.php");
$package_number = $_GET['package_number'];

$SQLstring = "UPDATE message SET status = 5 WHERE idMessage = $package_number";
$result1 = mysqli_query($dblink, $SQLstring);

http_response_code(200);
?>