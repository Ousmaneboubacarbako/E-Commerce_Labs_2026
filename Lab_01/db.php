<?php
$host = "localhost";
$db_user = "ousmane.bako";
$db_pass = "Ousmane07";
$db_name = "ecommerce_2026A_ousmane_bako";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>