<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fabrika_yonetim";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}  else {
	$conn->query("SET NAMES UTF8");
}

$dId   = isset($_POST['dId']) ? $_POST['dId'] : null;
$hTuru = isset($_POST['hTuru']) ? $_POST['hTuru'] : null;

$sql = "UPDATE demirbas SET acik_kapali=".$hTuru." WHERE id='".$dId."'";

if ($conn->query($sql) === TRUE) {
    echo "ISLEM BASARILI!";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>