<?php
session_start();
include("bd.php");
$package_number = $_GET['package_number'];

$SQLstring = "SELECT * FROM pyment WHERE message = '$package_number'";
$resultZ = mysqli_query($dblink, $SQLstring);
if ($resultZ->num_rows > 0) {
    $SQLstring = "DELETE FROM pyment WHERE message = '$package_number'";
    mysqli_query($dblink, $SQLstring);
}

$SQLstring = "DELETE FROM list_of_services WHERE Message_ID = '$package_number'";
mysqli_query($dblink, $SQLstring);

$SQLstring = "DELETE FROM message WHERE idMessage = '$package_number'";
mysqli_query($dblink, $SQLstring);

?>