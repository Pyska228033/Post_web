<?php
include("bd.php");
session_start();
mysqli_query($dblink, "UNLOCK TABLES");
$dblink->close();
http_response_code(200);
?>