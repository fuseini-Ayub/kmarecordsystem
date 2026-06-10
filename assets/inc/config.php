<?php
$mysqli = new mysqli('localhost', 'root', '', 'kma_records_management');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$con=mysqli_connect("localhost","root","","kma_records_management");

?>