<?php
session_start();

$response = array(
	"full_name" => isset($_SESSION["first_name"]) && isset($_SESSION["last_name"]) ? $_SESSION["first_name"] . " " . $_SESSION["last_name"] : null
);

header('Content-Type: application/json');
echo json_encode($response);

?>