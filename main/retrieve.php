<?php

include("../inc/config.php");
include("../inc/class.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

if (isset($_POST["edit_idService"])) {
	$dataFetcher = new ServiceRetrieve($conn);
	$edit_id = $_POST["edit_idService"];
	$row_service = $dataFetcher->dataFetch($edit_id);

	// output the user data into JSON Format
	header("Content-Type: application/json");
	echo json_encode($row_service);

	$dbConnect->closeConnection();
}

if (isset($_POST["del_idService"])) {
	$dataFetcher = new ServiceRetrieve($conn);
	$del_id = $_POST["del_idService"];
	$row_service = $dataFetcher->dataFetch($del_id);

	header("Content-Type: application/json");
	echo json_encode($row_service);

	$dbConnect->closeConnection();
}

if (isset($_POST["updateId"])) {
	$dataFetcher = new RetrieveData($conn);
	$updateId = $_POST["updateId"];
	$row_data = $dataFetcher->dataFetch($updateId);

	header("Content-Type: application/json");
	echo json_encode($row_data);

	$dbConnect->closeConnection();
}

if (isset($_POST["update_laptopId"])) {
	$dataFetcher = new RetrieveData($conn);
	$updateId = $_POST["update_laptopId"];
	$row_data = $dataFetcher->dataLaptopFetch($updateId);

	header("Content-Type: application/json");
	echo json_encode($row_data);

	$dbConnect->closeConnection();
}

if (isset($_POST["delete_dataId"])) {
	$dataFetcher = new RetrieveData($conn);
	$delete_dataId = $_POST["delete_dataId"];
	$row_data = $dataFetcher->dataFetch($delete_dataId);

	header("Content-Type: application/json");
	echo json_encode($row_data);

	$dbConnect->closeConnection();
}

if (isset($_POST["delLaptopId"])) {
	$dataFetcher = new RetrieveData($conn);
	$delete_dataId = $_POST["delLaptopId"];
	$row_data = $dataFetcher->dataLaptopFetch($delete_dataId);

	header("Content-Type: application/json");
	echo json_encode($row_data);

	$dbConnect->closeConnection();
}

if(isset($_POST["updatePass"])){
	$dataPassFetcher = new RetrieveUserPass($conn);
	$pass = $_POST["updatePass"];
	$row_pass = $dataPassFetcher->userDataFetch($pass);

	header("Content-Type: application/json");
	echo json_encode($row_pass);

	$dbConnect->closeConnection();
}

if(isset($_POST["restore_id"])){
	$dataPassFetcher = new RetrieveDataArchive($conn);
	$restore_data = $_POST["restore_id"];
	$row_dataArch = $dataPassFetcher->dataScannerFetch($restore_data);

	header("Content-Type: application/json");
	echo json_encode($row_dataArch);

	$dbConnect->closeConnection();
}

if(isset($_POST["restore_laptopId"])){
	$dataPassFetcher = new RetrieveDataArchive($conn);
	$restore_data = $_POST["restore_laptopId"];
	$row_dataArch = $dataPassFetcher->dataLaptopFetch($restore_data);

	header("Content-Type: application/json");
	echo json_encode($row_dataArch);

	$dbConnect->closeConnection();
}

if(isset($_POST["updateApple_id"])){
	$dataAppleFetcher = new RetrieveData($conn);
	$restore_appleData = $_POST["updateApple_id"];
	$row_appleData = $dataAppleFetcher->dataAppleFetch($restore_appleData);

	header("Content-Type: application/json");
	echo json_encode($row_appleData);

	$dbConnect->closeConnection();
}

if(isset($_POST["deleteAppleId"])){
	$dataAppleFetcher = new RetrieveData($conn);
	$restore_appleData = $_POST["deleteAppleId"];
	$row_appleData = $dataAppleFetcher->dataAppleFetch($restore_appleData);

	header("Content-Type: application/json");
	echo json_encode($row_appleData);

	$dbConnect->closeConnection();
}

if(isset($_POST["restore_AppleId"])){
	$dataAppleFetcher = new RetrieveDataArchive($conn);
	$restore_appleData = $_POST["restore_AppleId"];
	$row_appleData = $dataAppleFetcher->dataAppleFetch($restore_appleData);

	header("Content-Type: application/json");
	echo json_encode($row_appleData);

	$dbConnect->closeConnection();
}

if (isset($_POST["editScannereId"])) {
	$dataScannerFetch = new RetrieveData($conn);
	$restore_scanner = $_POST["editScannereId"];
	$row_scannerData = $dataScannerFetch->dataScannerFetch($restore_scanner);

	header("Content-Type: application/json");
	echo json_encode($row_scannerData);

	$dbConnect->closeConnection();
}

if (isset($_POST["deletScannerId"])) {
	$data = new RetrieveData($conn);
	$retrieve = $_POST["deletScannerId"];
	$row = $data->dataScannerFetch($retrieve);

	header("Content-Type: application/json");
	echo json_encode($row);

	$dbConnect->closeConnection();
}

if(isset($_POST["restore_scannerId"])){
	$dataScannerFetcher = new RetrieveDataArchive($conn);
	$restore_scannerData = $_POST["restore_scannerId"];
	$row_scannerData = $dataScannerFetcher->dataScannerFetch($restore_scannerData);

	header("Content-Type: application/json");
	echo json_encode($row_scannerData);

	$dbConnect->closeConnection();
}

if (isset($_POST["editMs365"])) {
	$data = new RetrieveData($conn);
	$retrieve = $_POST["editMs365"];
	$row = $data->dataM365Fetch($retrieve);

	header("Content-Type: application/json");
	echo json_encode($row);

	$dbConnect->closeConnection();
}

if (isset($_POST["delMs365"])) {
	$data = new RetrieveData($conn);
	$retrieve = $_POST["delMs365"];
	$row = $data->dataM365Fetch($retrieve);

	header("Content-Type: application/json");
	echo json_encode($row);

	$dbConnect->closeConnection();
}

// select value if = in the username
if (isset($_POST["selected"])) {
	$data = new M365Value($conn);
	$selValue = $_POST["selected"];
	$row = $data->fetchdata($selValue);

	header("Content-Type: application/json");
	echo json_encode($row);

	$dbConnect->closeConnection();
}


?>