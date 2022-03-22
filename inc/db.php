<?php
$servername = "localhost";
$username = "shopifyapp";
$password = "0oMqGdNAkJq3Ec";
$db = "sociallinksapp";
$conn = mysqli_connect($servername, $username, $password, $db);
if (!$conn) {
die("Connection Error: " . mysqli_connect_error());
}
?>