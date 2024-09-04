<?php

class Connection{

private $server;
private $user;
private $pass;
private $db_name;
private $conn;

public function __construct($server, $user, $pass, $db_name){
$this->server = $server;
$this->user = $user;
$this->pass = $pass;
$this->db_name = $db_name;
}

public function connectDb(){
$this->conn = new mysqli($this->server, $this->user, $this->pass, $this->db_name);
if ($this->conn->connect_error) {
die("connection failed: " . $this->conn->connect_error);
}

return $this->conn;
}

public function closeConnection(){
if ($this->conn) {
$this->conn->close();
}

}
}

require_once "showAlert.php";


class InsertUploadRegistration{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function userRegister($lname, $fname, $mname, $contact, $email, $user, $pass, $file){
$lname = $this->conn->escape_string(trim($lname));
$fname = $this->conn->escape_string(trim($fname));
$mname = $this->conn->escape_string(trim($mname));
$contact = $this->conn->escape_string(trim($contact));
$email = $this->conn->escape_string(trim($email));
$user = $this->conn->escape_string(trim($user));
$pass = $this->conn->escape_string(trim($pass));
$file = $_FILES["file"];

if (!empty($file["name"])) {
$newFileName = $this->handleFileUpload($file);
}else{
$newFileName = null;
}

$check_user = "SELECT * FROM tbl_users WHERE username='$user'";
$check_email = "SELECT * FROM tbl_users WHERE email='$email'";

$check_user_row = $this->conn->query($check_user);
$check_email_row = $this->conn->query($check_email);

$total_user_row = $check_user_row->num_rows;
$total_email_row = $check_email_row->num_rows;

if ($total_user_row > 0 || $total_email_row > 0) {
showAlertError();
}else{
$hash = password_hash($pass, PASSWORD_BCRYPT);
$account_type = "admin";
$sql = "INSERT INTO tbl_users(last_name, first_name, middle_name, contact, email, username, password, account_type, img) VALUES(?,?,?,?,?,?,?,?,?)";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("sssssssss", $lname, $fname, $mname, $contact, $email, $user, $hash, $account_type, $newFileName);
$stmt->execute();
$stmt->close();
showAlertRegistrationSuccess();
}
}

public function handleFileUpload($file){
$origName = $file["name"];
$ext = pathinfo($origName, PATHINFO_EXTENSION);
$newFileName = uniqid() . "_" . $origName;
$dest = "upload/" . $newFileName;

while (file_exists($dest)) {
$newFileName = uniqid() . "_" . $origName;
$dest = "upload/" . $newFileName;
}

move_uploaded_file($file["tmp_name"], $dest);
return $newFileName;
}

}

if (isset($_POST["btnRegister"])) {
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$userReg = new InsertUploadRegistration($conn);

$userReg->userRegister($_POST["lname"], $_POST["fname"], $_POST["mname"], $_POST["contact"], $_POST["email"], $_POST["user"], $_POST["pass"], $_FILES["file"]);
}


class UserLogin{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function userLogin($postUsername, $postPassword){
$userLog = $this->conn->escape_string(trim($postUsername));
$passLog = $this->conn->escape_string(trim($postPassword));

$sql = "SELECT * FROM tbl_users WHERE username = '$userLog'";
$user = $this->conn->query($sql);
$total_users = $user->num_rows;

if ($total_users > 0) {

while ($row_users = $user->fetch_assoc()) {
$userId = $row_users["user_id"];
$db_user = $row_users["username"];
$db_pass = $row_users["password"];
$db_fname = $row_users["first_name"];
$db_lname = $row_users["last_name"];
$db_account_type = $row_users["account_type"];
$db_stats = $row_users["status"];
$db_img = $row_users["img"];

if (password_verify($passLog, $db_pass) && strcmp($userLog, $db_user) === 0) {

$_SESSION["user_id"] = $userId;
$_SESSION["username"] = $db_user;
$_SESSION["password"] = $db_pass;
$_SESSION["first_name"] = $db_fname;
$_SESSION["last_name"] = $db_lname;
$_SESSION["account_type"] = $db_account_type;
$_SESSION["status"] = $db_stats;
$_SESSION["img"] = $db_img;

if ($db_account_type == "admin") {
	if ($db_stats == "reset") {
		header("location: pass_verified");
	}else{
		header("location: main");
	}
}else if($db_account_type == "guest"){
	if ($db_stats == "reset") {
		header("location: pass_verified");
	}else{
		header("location: guest");
	}
}

}else{
	return "Wrong Password or check the sensitivity of the username";
}
}
}else{
return "No Username";
}
return null;
}
}

if (isset($_POST["btnLogin"])) {
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$users = new UserLogin($conn);

$result = $users->userLogin($_POST["userLog"], $_POST["passLog"]);
if ($result) {
showAlertLoginError($result);
}
}

class RetrieveUserPass{
private $conn;

public function  __construct($conn){
$this->conn = $conn;
}

public function userDataFetch($id){
$sql = "SELECT * FROM tbl_users WHERE user_id=?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row_data = $result->fetch_assoc();
$stmt->close();
return $row_data;
}
}

class UpdateUserPass{
private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function updateUserPass($updatePass, $updatePassword){
$hash = password_hash($updatePassword, PASSWORD_BCRYPT);
$sql = "UPDATE tbl_users SET password = ? WHERE user_id = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("si", $hash, $updatePass);
$stmt->execute();
$stmt->close();
}
}

if(isset($_POST["btnUpdatePass"])){
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$passUpdate = new UpdateUserPass($conn);

if(isset($_POST["updatePass"])){
$updatePass = $conn->escape_string(trim($_POST["updatePass"]));
$updatePassword = $conn->escape_string(trim($_POST["updatPassword"]));

$passUpdate->updateUserPass($updatePass, $updatePassword);
showAlertUpdate();
}
$dbConnect->closeConnection();
}

// PC Inventory Page with pagination
class RecordsManager{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function getRecords($page = 1, $perPage = 5){
// $sql = "SELECT * FROM tbl_inventory";
$offset = ($page - 1) * $perPage;
// calculate the number of years dynamically base on date acquired with month format
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_inventory ORDER BY date_acquired DESC LIMIT $perPage OFFSET $offset"; 
$records = $this->conn->query($sql);
return $records;
}

// get all the total records
public function getTotalRecordsCount(){
$sql = "SELECT COUNT(*) AS total_records FROM tbl_inventory";
$result = $this->conn->query($sql);
$row = $result->fetch_assoc();
return $row["total_records"];
}

// laptop records
public function getLaptopRecords($page_laptop = 1, $perPage_laptop = 5){
// laptop records
$offsetLaptop = ($page_laptop - 1) * $perPage_laptop;
// calculate the number of years dynamically base on date acquired with month format
$sqlLaptop = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_laptop_inventory ORDER BY id DESC LIMIT $perPage_laptop OFFSET $offsetLaptop"; 
$recordsLaptop = $this->conn->query($sqlLaptop);
return $recordsLaptop;
}

// get all the total records
public function getLaptopTotalRecordsCount(){
$sqlLaptop = "SELECT COUNT(*) AS total_records FROM tbl_laptop_inventory";
$result = $this->conn->query($sqlLaptop);
$rowlaptop = $result->fetch_assoc();
return $rowlaptop["total_records"];
}


// apple inventory
public function getAppleRecords($page_laptop = 1, $perPage_laptop = 5){
// laptop records
$offsetLaptop = ($page_laptop - 1) * $perPage_laptop;
// calculate the number of years dynamically base on date acquired with month format
$sqlLaptop = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_apple_inventory ORDER BY id DESC LIMIT $perPage_laptop OFFSET $offsetLaptop"; 
$recordsApple = $this->conn->query($sqlLaptop);
return $recordsApple;
}

// get all the total records
public function getAppleTotalRecordsCount(){
$sqlLaptop = "SELECT COUNT(*) AS total_records FROM tbl_apple_inventory";
$result = $this->conn->query($sqlLaptop);
$rowApple = $result->fetch_assoc();
return $rowApple["total_records"];
}

// ================

// scanner inventory
public function getScannerRecords($page_laptop = 1, $perPage_laptop = 5){
// laptop records
$offsetLaptop = ($page_laptop - 1) * $perPage_laptop;
// calculate the number of years dynamically base on date acquired with month format
$sqlLaptop = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_scanner_inventory ORDER BY id DESC LIMIT $perPage_laptop OFFSET $offsetLaptop"; 
$recordScanner = $this->conn->query($sqlLaptop);
return $recordScanner;
}

// get all the total records
public function getScannerTotalRecordsCount(){
$sqlLaptop = "SELECT COUNT(*) AS total_records FROM tbl_scanner_inventory";
$result = $this->conn->query($sqlLaptop);
$rowApple = $result->fetch_assoc();
return $rowApple["total_records"];
}
// =====================

// count ms365 total records
public function countM365Records(){
	$sql = "SELECT COUNT(*) AS total_records FROM tbl_m365_acc";
	$result = $this->conn->query($sql);
	$row = $result->fetch_assoc();
	return $row["total_records"];
}

// ms365 account 
public function getM365Records($page_m365 = 1, $perPage_m365 = 5){
// public function getM365Records(){
$offsetM365 = max(0, ($page_m365 - 1) * $perPage_m365);	//use this query if you using asc function
// $offsetM365 = ($page_m365 - 1) * $perPage_m365; //use this if you using desc function
$sql = "SELECT * FROM tbl_m365_acc ORDER BY display_name DESC LIMIT $perPage_m365 OFFSET $offsetM365";
	$recordM365 = $this->conn->query($sql);
	return $recordM365;
}

}
class RetrieveData{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function dataFetch($id){
$sql = "SELECT * FROM tbl_inventory WHERE id = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row_data = $result->fetch_assoc();
$stmt->close();
return $row_data;
}

// laptop retrieve data
public function dataLaptopFetch($id){
$sql = "SELECT * FROM tbl_laptop_inventory WHERE id = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row_data = $result->fetch_assoc();
$stmt->close();
return $row_data;
}

// apple retrieve data
public function dataAppleFetch($id){
$sql = "SELECT * FROM tbl_apple_inventory WHERE id = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row_data = $result->fetch_assoc();
$stmt->close();
return $row_data;
 }

// scanner retrieve data
 public function dataScannerFetch($id){
$sql = "SELECT * FROM tbl_scanner_inventory WHERE id = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row_data = $result->fetch_assoc();
$stmt->close();
return $row_data;
 }

 public function dataM365Fetch($id){
 	$sql = "SELECT * FROM tbl_m365_acc WHERE id = ?";
 	$stmt = $this->conn->prepare($sql);
 	$stmt->bind_param("i", $id);
 	$stmt->execute();
 	$result = $stmt->get_result();
 	$row_data = $result->fetch_assoc();
 	$stmt->close();
 	return $row_data;
 }
}

class RetrieveDataArchive{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function dataFetch($id){
$sql = "SELECT * FROM tbl_inventory_archive WHERE id = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row_data_arch = $result->fetch_assoc();
$stmt->close();
return $row_data_arch;
}

// laptop retrieve data
public function dataLaptopFetch($id){
$sql = "SELECT * FROM tbl_laptop_inventory_archive WHERE id = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row_data = $result->fetch_assoc();
$stmt->close();
return $row_data;
}

// apple retrieve data
public function dataAppleFetch($id){
$sql = "SELECT * FROM tbl_apple_inventory_archive WHERE id = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row_data = $result->fetch_assoc();
$stmt->close();
return $row_data;
 }

// scanner retrieve data
 public function dataScannerFetch($id){
$sql = "SELECT * FROM tbl_scanner_inventory_archive WHERE id = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row_scannerData = $result->fetch_assoc();
$stmt->close();
return $row_scannerData;
 }

}

class DeleteInventoryData{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function deleteRecords($id){

if (isset($id)) {
$sql_insert_arch = "INSERT INTO tbl_inventory_archive(services, property_tag_name, description, property_tag, date_acquired, actual_user, remarks, specify, service_unserviceable) SELECT services, property_tag_name, description, property_tag, date_acquired, actual_user, remarks, specify, service_unserviceable FROM tbl_inventory WHERE id = ?";
$get_insert = $this->conn->prepare($sql_insert_arch);
$get_insert->bind_param("i", $id);
$get_insert->execute();

$sql = "DELETE FROM tbl_inventory WHERE id = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id);
$rs_del = $stmt->execute();

if ($rs_del == true) {
return true;
}
}
return false;
}

// laptop delete records
public function deleteLaptopRecords($id_laptop){
	if (isset($id_laptop)) {
		$sql_insert_arch = "INSERT INTO tbl_laptop_inventory_archive(services, property_tag_name, description, property_tag, date_acquired, actual_user, remarks, specify, service_unserviceable) SELECT services, property_tag_name, description, property_tag, date_acquired, actual_user, remarks, specify, service_unserviceable FROM tbl_laptop_inventory WHERE id = ?";
$get_insert = $this->conn->prepare($sql_insert_arch);
$get_insert->bind_param("i", $id_laptop);
$get_insert->execute();

$sql = "DELETE FROM tbl_laptop_inventory WHERE id = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id_laptop);
$rs_del = $stmt->execute();

if ($rs_del == true) {
		return true;
  }
	}
	return false;
}

public function deleteAppleRecords($id_apple){
	if (isset($id_apple)) {
		$sql_insert_arch = "INSERT INTO tbl_apple_inventory_archive(services, property_tag_name, description, property_tag, date_acquired, actual_user, remarks, specify, service_unserviceable) SELECT services, property_tag_name, description, property_tag, date_acquired, actual_user, remarks, specify, service_unserviceable FROM tbl_apple_inventory WHERE id = ?";
$get_insert = $this->conn->prepare($sql_insert_arch);
$get_insert->bind_param("i", $id_apple);
$get_insert->execute();

$sql = "DELETE FROM tbl_apple_inventory WHERE id = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id_apple);
$rs_del = $stmt->execute();

if ($rs_del == true) {
		return true;
  }
	}
	return false;
}

public function deleteScannerData($id_scanner){
	if (isset($id_scanner)) {
	$sql_insert_arch = "INSERT INTO tbl_scanner_inventory_archive(services, property_tag_name, description, property_tag, date_acquired, actual_user, remarks, specify, service_unserviceable) SELECT services, property_tag_name, description, property_tag, date_acquired, actual_user, remarks, specify, service_unserviceable FROM tbl_scanner_inventory WHERE id = ?";
$get_insert = $this->conn->prepare($sql_insert_arch);
$get_insert->bind_param("i", $id_scanner);
$get_insert->execute();

$sql = "DELETE FROM tbl_scanner_inventory WHERE id = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id_scanner);
$rs_del = $stmt->execute();

if ($rs_del == true) {
		return true;
  }	
 }

 return false;
 }

 public function deleteM365($id_m365){
 	if (isset($id_m365)) {
 		$sql_insert_arch = "INSERT INTO tbl_m365_acc_archive(username, account_name, display_name, actual_user, temporary_pass, permanent_pass, remarks, status) SELECT username, account_name, display_name, actual_user, temporary_pass, permanent_pass, remarks, status FROM tbl_m365_acc WHERE id = ?";
 		$get_insert = $this->conn->prepare($sql_insert_arch);
 		$get_insert->bind_param("i", $id_m365);
 		$get_insert->execute();

 		$sql = "DELETE FROM tbl_m365_acc WHERE id = ?";
 		$stmt = $this->conn->prepare($sql);
 		$stmt->bind_param("i", $id_m365);
 		$rs_del = $stmt->execute();

 		if ($rs_del == true) {
 			return true;
 		}
 	}
 	return false;
 }

}

// m365 delete inventory
if (isset($_POST["btnDeleteMs"])) {
	$dbConnect = new Connection($server, $user, $pass, $db_name);
	$conn = $dbConnect->connectDb();

	$del = new DeleteInventoryData($conn);
	$idDel = $_POST["del_MsId"];
	$deleted = $del->deleteM365($idDel);

	if ($deleted) {
		showAlertDelete();
	}

	$dbConnect->closeConnection();
}

// apple delete inventory
if (isset($_POST["btnAppleDelete"])) {
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$deleData = new DeleteInventoryData($conn);
$id_del = $_POST["deleteAppleId"];
$deleted = $deleData->deleteAppleRecords($id_del);

if ($deleted) {
showAlertDelete();
}

$dbConnect->closeConnection();
}
// =========================

if (isset($_POST["btnDelete"])) {
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$deleData = new DeleteInventoryData($conn);
$id_del = $_POST["delete_dataId"];
$deleted = $deleData->deleteRecords($id_del);

if ($deleted) {
showAlertDelete();
}

$dbConnect->closeConnection();
}

// laptop delete button
if (isset($_POST["btnLaptopDelete"])) {
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$deleData = new DeleteInventoryData($conn);
$id_del = $_POST["deleteLaptopId"];
$deleted = $deleData->deleteLaptopRecords($id_del);

if ($deleted) {
showAlertDelete();
}

$dbConnect->closeConnection();
}


// delete scanner data
if (isset($_POST["btnDeleteScanner"])) {
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$deleData = new DeleteInventoryData($conn);
$id_del = $_POST["del_scannerId"];
$deleted = $deleData->deleteScannerData($id_del);

if ($deleted) {
showAlertDelete();
}

$dbConnect->closeConnection();
}
// ============

// delete archive
class DeleteInventoryDataArchive{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function deleteArchRecords($id){

if (isset($id)) {
$sql_insert_arch = "INSERT INTO tbl_inventory(services, property_tag_name, description, property_tag, date_acquired, actual_user, remarks, specify, service_unserviceable) SELECT services, property_tag_name, description, property_tag, date_acquired, actual_user, remarks, specify, service_unserviceable FROM tbl_inventory_archive WHERE id = ?";
$get_insert = $this->conn->prepare($sql_insert_arch);
$get_insert->bind_param("i", $id);
$get_insert->execute();

$sql = "DELETE FROM tbl_inventory_archive WHERE id = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id);
$rs_del = $stmt->execute();

if ($rs_del == true) {
	return true;
}
}
return false;
}

// laptop restore data
public function deleteLaptopArchRecords($id_laptop){

if (isset($id_laptop)) {
$sql_insert_arch = "INSERT INTO tbl_laptop_inventory(services, property_tag_name, description, property_tag, date_acquired, actual_user, remarks, specify, service_unserviceable) SELECT services, property_tag_name, description, property_tag, date_acquired, actual_user, remarks, specify, service_unserviceable FROM tbl_inventory_archive WHERE id = ?";
$get_insert = $this->conn->prepare($sql_insert_arch);
$get_insert->bind_param("i", $id_laptop);
$get_insert->execute();

$sql = "DELETE FROM tbl_laptop_inventory_archive WHERE id = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id_laptop);
$rs_del = $stmt->execute();

if ($rs_del == true) {
	return true;
 }
}
return false;

 }

 // apple restore data
 public function deleteAppleArchRecords($id_apple){
 	if (isset($id_apple)) {
$sql_insert_arch = "INSERT INTO tbl_apple_inventory(services, property_tag_name, description, property_tag, date_acquired, actual_user, remarks, specify, service_unserviceable) SELECT services, property_tag_name, description, property_tag, date_acquired, actual_user, remarks, specify, service_unserviceable FROM tbl_apple_inventory_archive WHERE id = ?";
$get_insert = $this->conn->prepare($sql_insert_arch);
$get_insert->bind_param("i", $id_apple);
$get_insert->execute();

$sql = "DELETE FROM tbl_apple_inventory_archive WHERE id = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id_apple);
$rs_del = $stmt->execute();

if ($rs_del == true) {
	return true;
 }
}
return false;
 }

 public function deleteScannerArchRecords($id_scanner){
 	if ($id_scanner) {
 	$sql_insert_arch = "INSERT INTO tbl_scanner_inventory(services, property_tag_name, description, property_tag, date_acquired, actual_user, remarks, specify, service_unserviceable) SELECT services, property_tag_name, description, property_tag, date_acquired, actual_user, remarks, specify, service_unserviceable FROM tbl_scanner_inventory_archive WHERE id = ?";
$get_insert = $this->conn->prepare($sql_insert_arch);
$get_insert->bind_param("i", $id_scanner);
$get_insert->execute();

$sql = "DELETE FROM tbl_scanner_inventory_archive WHERE id = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id_scanner);
$rs_del = $stmt->execute();

if ($rs_del == true) {
	return true;
 }
}
return false;
 }

}

if (isset($_POST["btnDeleteArchive"])) {
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$deleDataArch = new DeleteInventoryDataArchive($conn);
$id_delArch = $_POST["restore_id"];
$deletedArch = $deleDataArch->deleteArchRecords($id_delArch);

if ($deletedArch) {
showAlertRestore();
}

$dbConnect->closeConnection();
}

// laptop restore data
if (isset($_POST["btnlaptopDeleteArchive"])) {
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$deleDataArch = new DeleteInventoryDataArchive($conn);
$id_delArch = $_POST["restore_laptopId"];
$deletedArch = $deleDataArch->deleteLaptopArchRecords($id_delArch);

if ($deletedArch) {
showAlertRestore();
}

$dbConnect->closeConnection();
}

// restore scanner data
if (isset($_POST["btnScannerRestore"])) {
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$deleDataArch = new DeleteInventoryDataArchive($conn);
$id_delArch = $_POST["restore_scannerId"];
$deletedArch = $deleDataArch->deleteScannerArchRecords($id_delArch);

if ($deletedArch) {
showAlertRestore();
}

$dbConnect->closeConnection();
}

// apple restore data
if (isset($_POST["btnAppleRestore"])) {
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$deleDataArch = new DeleteInventoryDataArchive($conn);
$id_delArch = $_POST["restore_AppleId"];
$deletedArch = $deleDataArch->deleteAppleArchRecords($id_delArch);

if ($deletedArch) {
showAlertRestore();
}

$dbConnect->closeConnection();
}
// =================

// filter by date acquired
class LiveSearch{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function performFilter($filter){

// Count the total number of rows based on the filter
$count_query = "SELECT COUNT(*) AS total_count FROM tbl_inventory WHERE date_acquired = '$filter'";
$count_result = $this->conn->query($count_query);
$row = $count_result->fetch_assoc();
$total_count = $row['total_count'];

// Retrieve filtered records along with years of service
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_inventory WHERE date_acquired = '$filter'";
$count_year = $this->conn->query($sql);
$row_yr = $count_year->fetch_assoc();
$year_count = $row_yr["years_of_service"];

$get = $this->conn->query($sql);
$data = "";

$data .="
<div class='table-responsive'>
<table class='table table-hover'>
<thead>
<tr class='text-center'>
<th>Services</th>
<th>Tag Name</th>
<th>Description</th>
<th>Property Tag</th>
<th>Date Acquired</th>
<th>Years services</th>
<th>Actual user</th>
<th>Remarks</th>
<th>Specific</th>
<th>Status</th>
<th class='lastChild'>Actions</th>
</tr>
</thead>
<tbody>";

if ($get->num_rows > 0) {
while ($row_filter = $get->fetch_assoc()) {
$origDate = $row_filter["date_acquired"];
$dateTime = new DateTime($origDate);
$formatDate = $dateTime->format("M d, Y");

$data .= "
<tr class='text-center'>
<td>".$row_filter["services"]."</td>
<td width='1%'>".$row_filter["property_tag_name"]."</td>
<td width='20%'>".$row_filter["description"]."</td>
<td width='20%'>".$row_filter["property_tag"]."</td>
<td width='14%'>".$formatDate."</td>
<td width='1%'>".$row_filter["years_of_service"]."</td>
<td width='1%'>".$row_filter["actual_user"]."</td>
<td>".$row_filter["remarks"]."</td>
<td>".$row_filter["specify"]."</td>
<td>".$row_filter["service_unserviceable"]."</td>
<td width='10%' class='lastChild'>
<a href='#' type='button' class='btn btn-sm btn-outline-success edit-dataId' data-bs-toggle='modal' data-bs-target='#modalUpdate' id='".$row_filter["id"]."'><i class='fa fa-underline'></i></a> 
<a href='#' type='button' class='btn btn-sm btn-outline-danger delete-dataId' data-bs-toggle='modal' data-bs-target='#modalDelete' id='".$row_filter["id"]."'><i class='fa fa-eraser'></i></a>
</td>
</tr>";
}

$data .="

<tr>
<th class='border border-2' colspan='4'><span class='text-primary fs-5'>".$year_count."</span> year/s of service total count:</th>
<td class='border border-2 fw-bolder text-primary fs-5'>".$total_count."</td>
</tr>

";

} else {
$data .= "
<tr>
<td colspan='12' class='text-center fw-bolder'>
<h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
</td>
</tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
}

}

class LiveSearchLaptopBydateAcquired{

private $conn;

public function __construct($conn){
	$this->conn = $conn;
}

// perform laptop filter
public function performFilterdateLaptop($filterLaptopDate){

// Count the total number of rows based on the filter
$count_query = "SELECT COUNT(*) AS total_count FROM tbl_laptop_inventory WHERE date_acquired = '$filterLaptopDate'";
$count_result = $this->conn->query($count_query);
$row = $count_result->fetch_assoc();
$total_count = $row['total_count'];

// Retrieve filtered records along with years of service
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_laptop_inventory WHERE date_acquired = '$filterLaptopDate'";
$count_year = $this->conn->query($sql);
$row_yr = $count_year->fetch_assoc();
$year_count = $row_yr["years_of_service"];

$get = $this->conn->query($sql);
$data = "";

$data .="
<div class='table-responsive'>
<table class='table table-hover'>
<thead>
<tr class='text-center'>
<th>Services</th>
<th>Tag Name</th>
<th>Description</th>
<th>Property Tag</th>
<th>Date Acquired</th>
<th>Years services</th>
<th>Actual user</th>
<th>Remarks</th>
<th>Specific</th>
<th>Status</th>
<th class='lastChild'>Actions</th>
</tr>
</thead>
<tbody>";

if ($get->num_rows > 0) {
while ($row_filter = $get->fetch_assoc()) {
$origDate = $row_filter["date_acquired"];
$dateTime = new DateTime($origDate);
$formatDate = $dateTime->format("M d, Y");

$data .= "
<tr class='text-center'>
<td>".$row_filter["services"]."</td>
<td width='1%'>".$row_filter["property_tag_name"]."</td>
<td width='20%'>".$row_filter["description"]."</td>
<td width='20%'>".$row_filter["property_tag"]."</td>
<td width='14%'>".$formatDate."</td>
<td width='1%'>".$row_filter["years_of_service"]."</td>
<td width='1%'>".$row_filter["actual_user"]."</td>
<td>".$row_filter["remarks"]."</td>
<td>".$row_filter["specify"]."</td>
<td>".$row_filter["service_unserviceable"]."</td>
<td width='10%' class='lastChild'>
<a href='#' type='button' class='btn btn-sm btn-outline-success editLaptopId' data-bs-toggle='modal' data-bs-target='#modalUpdate' id='".$row_filter['id']."'><i class='fa fa-underline'></i></a> <a href='#' type='button' class='btn btn-sm btn-outline-danger delLaptopId' data-bs-toggle='modal' data-bs-target='#modalDelete' id='".$row_filter['id']."'><i class='fa fa-eraser'></i></a>
</td>
</tr>";
}

$data .="

<tr>
<th class='border border-2' colspan='4'><span class='text-primary fs-5'>".$year_count."</span> year/s of service total count:</th>
<td class='border border-2 fw-bolder text-primary fs-5'>".$total_count."</td>
</tr>

";

} else {
$data .= "
<tr>
<td colspan='12' class='text-center fw-bolder'>
<h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
</td>
</tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
 }
}


// trigger the filter
if (isset($_POST["filter"])) {
$filter = $_POST["filter"];
include("config.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$liveFilter = new LiveSearch($conn);
$liveFilter->performFilter($filter);
$dbConnect->closeConnection();
}

// trigger the filter for laptop by date acquired
if (isset($_POST["filterDateLaptop"])) {
$filterLaptopDate = $_POST["filterDateLaptop"];
include("config.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$livefilterLaptopDate = new LiveSearchLaptopBydateAcquired($conn);
$livefilterLaptopDate->performFilterdateLaptop($filterLaptopDate);
$dbConnect->closeConnection();
}
// ++++++++++++++++++++++++


// filter by years of service
class FilterYearsService{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function performFilter($filter){

$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_inventory WHERE TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) = '$filter'";
$count_year = $this->conn->query($sql);
$row_yr = $count_year->fetch_assoc();
$year_count = $row_yr["years_of_service"];
$row_count = $count_year->num_rows;

$get = $this->conn->query($sql);
$data = "";

$data .="
<div class='table-responsive'>
<table class='table table-hover'>
<thead>
<tr class='text-center'>
<th>Services</th>
<th>Tag Name</th>
<th>Description</th>
<th>Property Tag</th>
<th>Date Acquired</th>
<th>Years services</th>
<th>Actual user</th>
<th>Remarks</th>
<th>Specific</th>
<th>Status</th>
<th class='lastChild'>Actions</th>
</tr>
</thead>
<tbody>";

if ($get->num_rows > 0) {
while ($row_filter = $get->fetch_assoc()) {
$origDate = $row_filter["date_acquired"];
$dateTime = new DateTime($origDate);
$formatDate = $dateTime->format("M d, Y");

$data .= "
<tr class='text-center'> 
<td>".$row_filter["services"]."</td>
<td width='1%'>".$row_filter["property_tag_name"]."</td>
<td width='20%'>".$row_filter["description"]."</td>
<td width='20%'>".$row_filter["property_tag"]."</td>
<td width='14%'>".$formatDate."</td>
<td width='1%'>".$row_filter["years_of_service"]."</td>
<td width='1%'>".$row_filter["actual_user"]."</td>
<td>".$row_filter["remarks"]."</td>
<td>".$row_filter["specify"]."</td>
<td>".$row_filter["service_unserviceable"]."</td>
<td width='10%' class='lastChild'>
<a href='#' type='button' class='btn btn-sm btn-outline-success edit-dataId' data-bs-toggle='modal' data-bs-target='#modalUpdate' id='".$row_filter["id"]."'><i class='fa fa-underline'></i></a> 
<a href='#' type='button' class='btn btn-sm btn-outline-danger delete-dataId' data-bs-toggle='modal' data-bs-target='#modalDelete' id='".$row_filter["id"]."'><i class='fa fa-eraser'></i></a>
</td>
</tr>";
}

$data .="

<tr>
<th class='border border-2' colspan='4'><span class='text-primary fs-5'>".$year_count."</span> year/s of service total count:</th>
<td class='border border-2 fw-bolder text-primary fs-5'>".$row_count."</td>
</tr>

";

} else {
$data .= "
<tr>
<td colspan='12' class='text-center fw-bolder'>
<h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
</td>
</tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
 }
}


// trigger the filter
if (isset($_POST["filterYrService"])) {
$filterYrService = $_POST["filterYrService"];
include("config.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$liveFilterYrs = new FilterYearsService($conn);
$liveFilterYrs->performFilter($filterYrService);
$dbConnect->closeConnection();
}

// ++++++++++++++++++++++++

// filter by categories
class LiveTypeFilter{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function performFilter($filterType){

// Count the total number of rows based on the filter
$count_query = "SELECT COUNT(*) AS total_count FROM tbl_inventory WHERE LEFT(property_tag_name, LENGTH('$filterType')) = '$filterType' || property_tag LIKE '%$filterType%' || LEFT(services, LENGTH('$filterType')) = '$filterType' || LEFT(remarks, LENGTH('$filterType')) = '$filterType' || LEFT(service_unserviceable, LENGTH('$filterType')) = '$filterType'";
$count_result = $this->conn->query($count_query);
$row = $count_result->fetch_assoc();
$total_count = $row['total_count'];

// Retrieve filtered records along with years of service use left function to extract the first characters in the input and compare records this function is when there are same characters within the datafield name
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_inventory WHERE LEFT(property_tag_name, LENGTH('$filterType')) = '$filterType' || property_tag LIKE '%$filterType%' || LEFT(services, LENGTH('$filterType')) = '$filterType' || LEFT(remarks, LENGTH('$filterType')) = '$filterType' || LEFT(service_unserviceable, LENGTH('$filterType')) = '$filterType' ORDER BY years_of_service DESC";

$get = $this->conn->query($sql);
$data = "";

$data .="
<div class='table-responsive'>
<table class='table table-hover'>
<thead>
<tr class='text-center'>
<th>Services</th>
<th>Tag Name</th>
<th>Description</th>
<th>Property Tag</th>
<th>Date Acquired</th>
<th>Years services</th>
<th>Actual user</th>
<th>Remarks</th>
<th>Specific</th>
<th>Status</th>
<th class='lastChild'>Actions</th>
</tr>
</thead>
<tbody>";

if ($get->num_rows > 0) {
while ($row_filter = $get->fetch_assoc()) {
$origDate = $row_filter["date_acquired"];
$dateTime = new DateTime($origDate);
$formatDate = $dateTime->format("M d, Y");

$data .= "
<tr class='text-center'>
<td>".$row_filter["services"]."</td>
<td width='1%'>".$row_filter["property_tag_name"]."</td>
<td width='20%'>".$row_filter["description"]."</td>
<td width='20%'>".$row_filter["property_tag"]."</td>
<td width='14%'>".$formatDate."</td>
<td width='1%'>".$row_filter["years_of_service"]."</td>
<td width='1%'>".$row_filter["actual_user"]."</td>
<td>".$row_filter["remarks"]."</td>
<td>".$row_filter["specify"]."</td>
<td>".$row_filter["service_unserviceable"]."</td>
<td width='10%' class='lastChild'>
<a href='#' type='button' class='btn btn-sm btn-outline-success edit-dataId' data-bs-toggle='modal' data-bs-target='#modalUpdate' id='".$row_filter["id"]."'><i class='fa fa-underline'></i></a> 
<a href='#' type='button' class='btn btn-sm btn-outline-danger delete-dataId' data-bs-toggle='modal' data-bs-target='#modalDelete' id='".$row_filter["id"]."'><i class='fa fa-eraser'></i></a>
</td>
</tr>";
}

$data .="

<tr>
<th class='border border-2 fw-bolder' colspan='2'>Total count:</th>
<td class='border border-2 text-success fw-bolder'>".$total_count."</td>
</tr>

";

} else {
$data .= "
<tr>
<td colspan='12' class='text-center fw-bolder'>
<h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
</td>
</tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
}
}

// trigger the filter
if (isset($_POST["filterType"])) {
$filterType = $_POST["filterType"];
include("config.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$liveSearch = new LiveTypeFilter($conn);
$liveSearch->performFilter($filterType);
$dbConnect->closeConnection();
}


// filter categories im pc archives
class LiveFilterArchives{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function performFilter($filterTypeArchive){

// Count the total number of rows based on the filter
$count_query = "SELECT COUNT(*) AS total_count FROM tbl_inventory_archive WHERE LEFT(property_tag_name, LENGTH('$filterTypeArchive')) = '$filterTypeArchive' || property_tag LIKE '%$filterTypeArchive%' || LEFT(services, LENGTH('$filterTypeArchive')) = '$filterTypeArchive' || LEFT(remarks, LENGTH('$filterTypeArchive')) = '$filterTypeArchive' || LEFT(service_unserviceable, LENGTH('$filterTypeArchive')) = '$filterTypeArchive'";
$count_result = $this->conn->query($count_query);
$row = $count_result->fetch_assoc();
$total_count = $row['total_count'];

// Retrieve filtered records along with years of service
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_inventory_archive WHERE LEFT(property_tag_name, LENGTH('$filterTypeArchive')) = '$filterTypeArchive' || property_tag LIKE '%$filterTypeArchive%' || LEFT(services, LENGTH('$filterTypeArchive')) = '$filterTypeArchive' || LEFT(remarks, LENGTH('$filterTypeArchive')) = '$filterTypeArchive' || LEFT(service_unserviceable, LENGTH('$filterTypeArchive')) = '$filterTypeArchive' ORDER BY years_of_service DESC";

$get = $this->conn->query($sql);
$data = "";

$data .="
<div class='table-responsive'>
<table class='table table-hover'>
<thead>
<tr class='text-center'>
<th>Services</th>
<th>Tag Name</th>
<th>Description</th>
<th>Property Tag</th>
<th>Date Acquired</th>
<th>Years services</th>
<th>Actual user</th>
<th>Remarks</th>
<th>Specific</th>
<th>Status</th>
<th class='lastChild'>Actions</th>
</tr>
</thead>
<tbody>";

if ($get->num_rows > 0) {
while ($row_filter = $get->fetch_assoc()) {
$origDate = $row_filter["date_acquired"];
$dateTime = new DateTime($origDate);
$formatDate = $dateTime->format("M d, Y");

$data .= "
<tr class='text-center'>
<td>".$row_filter["services"]."</td>
<td width='1%'>".$row_filter["property_tag_name"]."</td>
<td width='20%'>".$row_filter["description"]."</td>
<td width='20%'>".$row_filter["property_tag"]."</td>
<td width='14%'>".$formatDate."</td>
<td width='1%'>".$row_filter["years_of_service"]."</td>
<td width='1%'>".$row_filter["actual_user"]."</td>
<td>".$row_filter["remarks"]."</td>
<td>".$row_filter["specify"]."</td>
<td>".$row_filter["service_unserviceable"]."</td>
<td width='10%' class='lastChild'>

<a href='#' type='button' class='btn btn-outline-success restore-pc' data-bs-toggle='modal' data-bs-target='#modalPCRestore' id='".$row_filter['id']."'><i class='fa-solid fa-plus'></i></a>

</td>
</tr>";
}

$data .="

<tr>
<th class='border border-2 fw-bolder'>Total count:</th>
<td class='border border-2 text-success fw-bolder'>".$total_count."</td>
</tr>

";

} else {
$data .= "
<tr>
<td colspan='12' class='text-center fw-bolder'>
<h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
</td>
</tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
}
}

// trigger the filter
if (isset($_POST["filterTypeArchive"])) {
$filter = $_POST["filterTypeArchive"];
include("config.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$liveSearch = new LiveFilterArchives($conn);
$liveSearch->performFilter($filter);
$dbConnect->closeConnection();
}

// ===========================

class YearsofService{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function yearofService(){
$yrService = [];

$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_inventory ORDER BY years_of_service DESC";

$result = $this->conn->query($sql);
while ($rowYr = $result->fetch_assoc()) {
$yrService[] = $rowYr["years_of_service"];
}

// remove duplicate data in array
$uniqueYrService = array_unique($yrService);
return $uniqueYrService;
}

public function yearofLaptopService(){
$yrService = [];

$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_laptop_inventory ORDER BY years_of_service DESC";

$result = $this->conn->query($sql);
while ($rowYr = $result->fetch_assoc()) {
$yrService[] = $rowYr["years_of_service"];
}

// remove duplicate data in array
$uniqueYrService = array_unique($yrService);
return $uniqueYrService;
}

public function yearofAppleService(){
$yrService = [];

$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_apple_inventory ORDER BY years_of_service DESC";

$result = $this->conn->query($sql);
while ($rowYr = $result->fetch_assoc()) {
$yrService[] = $rowYr["years_of_service"];
}

// remove duplicate data in array
$uniqueYrService = array_unique($yrService);
return $uniqueYrService;
}

// scanner years of service
public function yearofScannerService(){
$yrService = [];

$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_scanner_inventory ORDER BY years_of_service DESC";

$result = $this->conn->query($sql);
while ($rowYr = $result->fetch_assoc()) {
$yrService[] = $rowYr["years_of_service"];
}

// remove duplicate data in array
$uniqueYrService = array_unique($yrService);
return $uniqueYrService;
}
}


class dateAcquiredService{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function getdateService(){

$dateCategory = [];

// $currentYear = date("Y");
$sql = "SELECT DISTINCT date_acquired FROM tbl_inventory ORDER BY date_acquired DESC";

$result = $this->conn->query($sql);

while ($row = $result->fetch_assoc()) {
// convert the date to m/d/y format
// $date = date("M d, Y", strtotime($row["date_acquired"]));
$date = $row["date_acquired"];
// $date = $row["date_acquired_formatted"];
$dateCategory[] = $date;
}

return $dateCategory;
}

public function getdateLaptopService(){

$dateCategory = [];

// $currentYear = date("Y");
$sql = "SELECT DISTINCT date_acquired FROM tbl_laptop_inventory ORDER BY date_acquired DESC";

$result = $this->conn->query($sql);

while ($row = $result->fetch_assoc()) {
// convert the date to m/d/y format
// $date = date("M d, Y", strtotime($row["date_acquired"]));
$date = $row["date_acquired"];
// $date = $row["date_acquired_formatted"];
$dateCategory[] = $date;
}

return $dateCategory;
}

public function getdateAppleService(){

$dateCategory = [];

// $currentYear = date("Y");
$sql = "SELECT DISTINCT date_acquired FROM tbl_apple_inventory ORDER BY date_acquired DESC";

$result = $this->conn->query($sql);

while ($row = $result->fetch_assoc()) {
// convert the date to m/d/y format
// $date = date("M d, Y", strtotime($row["date_acquired"]));
$date = $row["date_acquired"];
// $date = $row["date_acquired_formatted"];
$dateCategory[] = $date;
  }
return $dateCategory;
 }

// filter by date acquired
public function getScannerService(){
$dateCategory = [];

// $currentYear = date("Y");
$sql = "SELECT DISTINCT date_acquired FROM tbl_scanner_inventory ORDER BY date_acquired DESC";

$result = $this->conn->query($sql);

while ($row = $result->fetch_assoc()) {
// convert the date to m/d/y format
// $date = date("M d, Y", strtotime($row["date_acquired"]));
$date = $row["date_acquired"];
// $date = $row["date_acquired_formatted"];
$dateCategory[] = $date;
  }
return $dateCategory;
 }

}

// insert records
class InsertData{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function insertData($insertServices, $insertTagName, $insertProperty, $insertDesc, $insertActualUser, $insertDateAcquired, $insertRemarks, $insertSpecify, $insertStatus){

$sql = "INSERT INTO tbl_inventory(services, property_tag_name, property_tag, description, actual_user, date_acquired, remarks, specify, service_unserviceable) VALUES(?,?,?,?,?,?,?,?,?)";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("sssssssss", $insertServices, $insertTagName, $insertProperty, $insertDesc, $insertActualUser, $insertDateAcquired, $insertRemarks, $insertSpecify, $insertStatus);
$stmt->execute();
$stmt->close();
}

// laptop insert query
public function insertLaptopData($insertServices, $insertTagName, $insertProperty, $insertDesc, $insertActualUser, $insertDateAcquired, $insertRemarks, $insertSpecify, $insertStatus){

		$sql = "INSERT INTO tbl_laptop_inventory(services, property_tag_name, property_tag, description, actual_user, date_acquired, remarks, specify, service_unserviceable) VALUES(?,?,?,?,?,?,?,?,?)";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param("sssssssss", $insertServices, $insertTagName, $insertProperty, $insertDesc, $insertActualUser, $insertDateAcquired, $insertRemarks, $insertSpecify, $insertStatus);
		$stmt->execute();
		$stmt->close();
 }

 // insert apple query
 public function insertAppledata($insertServices, $insertTagName, $insertProperty, $insertDesc, $insertActualUser, $insertDateAcquired, $insertRemarks, $insertSpecify, $insertStatus){
 	$sql = "INSERT INTO tbl_apple_inventory(services, property_tag_name, property_tag, description, actual_user, date_acquired, remarks, specify, service_unserviceable) VALUES(?,?,?,?,?,?,?,?,?)";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param("sssssssss", $insertServices, $insertTagName, $insertProperty, $insertDesc, $insertActualUser, $insertDateAcquired, $insertRemarks, $insertSpecify, $insertStatus);
		$stmt->execute();
		$stmt->close();
 }

 // insert scanner query
 public function insertScannerData($insertServices, $insertTagName, $insertProperty, $insertDesc, $insertActualUser, $insertDateAcquired, $insertRemarks, $insertSpecify, $insertStatus){
	$sql = "INSERT INTO tbl_scanner_inventory(services, property_tag_name, property_tag, description, actual_user, date_acquired, remarks, specify, service_unserviceable) VALUES(?,?,?,?,?,?,?,?,?)";
	$stmt = $this->conn->prepare($sql);
	$stmt->bind_param("sssssssss", $insertServices, $insertTagName, $insertProperty, $insertDesc, $insertActualUser, $insertDateAcquired, $insertRemarks, $insertSpecify, $insertStatus);
	$stmt->execute();
	$stmt->close();	
}

// insert Ms365 data
public function insertMs365Data($insertMsUsername, $insertMsName, $insertMsDisplayName, $insertMsActualUser, $insertMsTempPass, $insertPerPass, $insertMsRemarks, $insertStatus){
	$sql = "INSERT INTO tbl_m365_acc(username, account_name, display_name, actual_user, temporary_pass, permanent_pass, remarks, status) VALUES(?,?,?,?,?,?,?,?)";
	$stmt = $this->conn->prepare($sql);
	$stmt->bind_param("ssssssss", $insertMsUsername, $insertMsName, $insertMsDisplayName, $insertMsActualUser, $insertMsTempPass, $insertPerPass, $insertMsRemarks, $insertStatus);
	$stmt->execute();
	$stmt->close();
}

public function addMsAccount($addUsername, $addAccountName, $addDisplayName){
	$sql = "INSERT INTO tbl_m365_acc(username, account_name, display_name) VALUES(?,?,?)";
	$stmt = $this->conn->prepare($sql);
	$stmt->bind_param("sss", $addUsername, $addAccountName, $addDisplayName);
	$stmt->execute();
	$stmt->close();
}

}

if (isset($_POST["btnAddAccount"])) {
	$dbConnect = new Connection($server, $user, $pass, $db_name);
	$conn = $dbConnect->connectDb();

	$addAccount = new InsertData($conn);
	$addAccount->addMsAccount($_POST["addUsername"], $_POST["addAccountName"], $_POST["addDisplayName"]);
	if ($addAccount) {
		showAlertSuccess();
	}
}

if (isset($_POST["btnInsertMs"])) {
	$dbConnect = new Connection($server, $user, $pass, $db_name);
	$conn = $dbConnect->connectDb();

	$insertM365Records = new InsertData($conn);

	$insertM365Records->insertMs365Data($_POST["insertMsUsername"], $_POST["insertMsName"], $_POST["insertMsDisplayName"], $_POST["insertMsActualUser"], $_POST["insertMsTempPass"], $_POST["insertPerPass"], $_POST["insertMsRemarks"], $_POST["insertStatus"]);
	if ($insertM365Records) {
		showAlertSuccess();
	}
}

if (isset($_POST["btnInsert"])) {
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$insertRecords = new InsertData($conn);

$insertRecords->insertData($_POST["insertServices"], $_POST["insertTagName"], $_POST["insertProperty"], $_POST["insertDesc"], $_POST["insertActualUser"], $_POST["insertDateAcquired"], $_POST["insertRemarks"], $_POST["insertSpecify"], $_POST["insertStatus"]);
if ($insertRecords) {
showAlertSuccess();
}
}

// insert laptop data
if (isset($_POST["btnLaptopInsert"])) {
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$insertRecords = new InsertData($conn);

$insertRecords->insertLaptopData($_POST["insertServices"], $_POST["insertTagName"], $_POST["insertProperty"], $_POST["insertDesc"], $_POST["insertActualUser"], $_POST["insertDateAcquired"], $_POST["insertRemarks"], $_POST["insertSpecify"], $_POST["insertStatus"]);
if ($insertRecords) {
showAlertSuccess();
}
}

// insert apple data trigger
if (isset($_POST["btnAppleInsert"])) {
	$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$insertRecords = new InsertData($conn);

$insertRecords->insertAppledata($_POST["insertServices"], $_POST["insertTagName"], $_POST["insertProperty"], $_POST["insertDesc"], $_POST["insertActualUser"], $_POST["insertDateAcquired"], $_POST["insertRemarks"], $_POST["insertSpecify"], $_POST["insertStatus"]);
if ($insertRecords) {
showAlertSuccess();
}
}

if (isset($_POST["btnScannerInsert"])) {
	$dbConnect = new Connection($server, $user, $pass, $db_name);
	$conn = $dbConnect->connectDb();

	$insertRecords = new InsertData($conn);

	$insertRecords->insertScannerData($_POST["insertServices"], $_POST["insertTagName"], $_POST["insertProperty"], $_POST["insertDesc"], $_POST["insertActualUser"], $_POST["insertDateAcquired"], $_POST["insertRemarks"], $_POST["insertSpecify"], $_POST["insertStatus"]);
	if ($insertRecords) {
		showAlertSuccess();
	}
}

class UpdateData{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function updateData($updateId, $updateServices, $updateTagName, $updateProperty, $updateDesc, $updateActualUser, $updateDate, $updateRemarks, $updateSpecify, $updateStatus){

$sql = "UPDATE tbl_inventory SET services=?, property_tag_name=?, property_tag=?, description=?, actual_user=?, date_acquired=?, remarks=?, specify=?, service_unserviceable=? WHERE id=?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("sssssssssi", $updateServices, $updateTagName, $updateProperty, $updateDesc, $updateActualUser, $updateDate, $updateRemarks, $updateSpecify, $updateStatus, $updateId);
$stmt->execute();
$stmt->close();
}

// laptop update

public function updateLaptopData($updateId, $updateServices, $updateTagName, $updateProperty, $updateDesc, $updateActualUser, $updateDate, $updateRemarks, $updateSpecify, $updateStatus){

$sql = "UPDATE tbl_laptop_inventory SET services=?, property_tag_name=?, property_tag=?, description=?, actual_user=?, date_acquired=?, remarks=?, specify=?, service_unserviceable=? WHERE id=?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("sssssssssi", $updateServices, $updateTagName, $updateProperty, $updateDesc, $updateActualUser, $updateDate, $updateRemarks, $updateSpecify, $updateStatus, $updateId);
$stmt->execute();
$stmt->close();
}

// apple update
public function updateAppleData($updateId, $updateServices, $updateTagName, $updateProperty, $updateDesc, $updateActualUser, $updateDate, $updateRemarks, $updateSpecify, $updateStatus){

$sql = "UPDATE tbl_apple_inventory SET services=?, property_tag_name=?, property_tag=?, description=?, actual_user=?, date_acquired=?, remarks=?, specify=?, service_unserviceable=? WHERE id=?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("sssssssssi", $updateServices, $updateTagName, $updateProperty, $updateDesc, $updateActualUser, $updateDate, $updateRemarks, $updateSpecify, $updateStatus, $updateId);
$stmt->execute();
$stmt->close();
}

public function updateScannerData($updateId, $updateServices, $updateTagName, $updateProperty, $updateDesc, $updateActualUser, $updateDate, $updateRemarks, $updateSpecify, $updateStatus){
$sql = "UPDATE tbl_scanner_inventory SET services=?, property_tag_name=?, property_tag=?, description=?, actual_user=?, date_acquired=?, remarks=?, specify=?, service_unserviceable=? WHERE id=?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("sssssssssi", $updateServices, $updateTagName, $updateProperty, $updateDesc, $updateActualUser, $updateDate, $updateRemarks, $updateSpecify, $updateStatus, $updateId);
$stmt->execute();
$stmt->close();	
 }

public function updateM365Data($updateMsId, $updateMsUsername, $updateMsName, $updateMsDisplayName, $updateMsActualUser, $updateMsTempPass, $updateMsPermPass, $updateMsRemarks, $updateMsStatus){
$sql = "UPDATE tbl_m365_acc SET username=?, account_name=?, display_name=?, actual_user=?, temporary_pass=?, permanent_pass=?, remarks=?, status=? WHERE id=?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("ssssssssi", $updateMsUsername, $updateMsName, $updateMsDisplayName, $updateMsActualUser, $updateMsTempPass, $updateMsPermPass, $updateMsRemarks, $updateMsStatus, $updateMsId);
$stmt->execute();
$stmt->close();
}

}

// update m365
if (isset($_POST["btnUpdateMs"])) {
	$dbConnect = new Connection($server, $user, $pass, $db_name);
	$conn = $dbConnect->connectDb();

	$dataMsUpdate = new UpdateData($conn);

	if (isset($_POST["updateMsId"])) {
		$updateMsId = $conn->escape_string(trim($_POST["updateMsId"]));
		$updateMsUsername = $conn->escape_string(trim($_POST["updateMsUsername"]));
		$updateMsName = $conn->escape_string(trim($_POST["updateMsName"]));
		$updateMsDisplayName = $conn->escape_string(trim($_POST["updateMsDisplayName"]));
		$updateMsActualUser = $conn->escape_string(trim($_POST["updateMsActualUser"]));
		$updateMsTempPass = $conn->escape_string(trim($_POST["updateMsTempPass"]));
		$updateMsPermPass = $conn->escape_string(trim($_POST["updateMsPermPass"]));
		$updateMsRemarks = $conn->escape_string(trim($_POST["updateMsRemarks"]));
		$updateMsStatus = $conn->escape_string(trim($_POST["updateMsStatus"]));

		$dataMsUpdate->updateM365Data($updateMsId, $updateMsUsername, $updateMsName, $updateMsDisplayName, $updateMsActualUser, $updateMsTempPass, $updateMsPermPass, $updateMsRemarks, $updateMsStatus);
			showAlertUpdate();
	}

	$dbConnect->closeConnection();
}

if (isset($_POST["btnUpdate"])) {
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$dataUpdate = new UpdateData($conn);

if (isset($_POST["updateId"])) {
		$updateId = $conn->escape_string(trim($_POST["updateId"]));
		$updateServices = $conn->escape_string(trim($_POST["updateServices"]));
		$updateTagName = $conn->escape_string(trim($_POST["updateTagName"]));
		$updateProperty = $conn->escape_string(trim($_POST["updateProperty"]));
		$updateDesc = $conn->escape_string(trim($_POST["updateDesc"]));
		$updateActualUser = $conn->escape_string(trim($_POST["updateActualUser"]));
		$updateDate = $conn->escape_string(trim($_POST["updateDate"]));
		$updateRemarks = $conn->escape_string(trim($_POST["updateRemarks"]));
		$updateSpecify = $conn->escape_string(trim($_POST["updateSpecify"]));
		$updateStatus = $conn->escape_string(trim($_POST["updateStatus"]));

		$dataUpdate->updateData($updateId, $updateServices, $updateTagName, $updateProperty, $updateDesc, $updateActualUser, $updateDate, $updateRemarks, $updateSpecify, $updateStatus);
			showAlertUpdate();
}

$dbConnect->closeConnection();
}

//===============================

// apple update button trigger
if (isset($_POST["btnAppleUpdate"])) {
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$dataUpdate = new UpdateData($conn);

if (isset($_POST["updateApple_id"])) {
		$updateId = $conn->escape_string(trim($_POST["updateApple_id"]));
		$updateServices = $conn->escape_string(trim($_POST["updateServices"]));
		$updateTagName = $conn->escape_string(trim($_POST["updateTagName"]));
		$updateProperty = $conn->escape_string(trim($_POST["updateProperty"]));
		$updateDesc = $conn->escape_string(trim($_POST["updateDesc"]));
		$updateActualUser = $conn->escape_string(trim($_POST["updateActualUser"]));
		$updateDate = $conn->escape_string(trim($_POST["updateDate"]));
		$updateRemarks = $conn->escape_string(trim($_POST["updateRemarks"]));
		$updateSpecify = $conn->escape_string(trim($_POST["updateSpecify"]));
		$updateStatus = $conn->escape_string(trim($_POST["updateStatus"]));

		$dataUpdate->updateAppleData($updateId, $updateServices, $updateTagName, $updateProperty, $updateDesc, $updateActualUser, $updateDate, $updateRemarks, $updateSpecify, $updateStatus);
			showAlertUpdate();
}

$dbConnect->closeConnection();
}
// ===============

// laptop update records
if (isset($_POST["btnLaptopUpdate"])) {
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$dataUpdate = new UpdateData($conn);

if (isset($_POST["updateLaptop_id"])) {
		$updateId = $conn->escape_string(trim($_POST["updateLaptop_id"]));
		$updateServices = $conn->escape_string(trim($_POST["updateServices"]));
		$updateTagName = $conn->escape_string(trim($_POST["updateTagName"]));
		$updateProperty = $conn->escape_string(trim($_POST["updateProperty"]));
		$updateDesc = $conn->escape_string(trim($_POST["updateDesc"]));
		$updateActualUser = $conn->escape_string(trim($_POST["updateActualUser"]));
		$updateDate = $conn->escape_string(trim($_POST["updateDate"]));
		$updateRemarks = $conn->escape_string(trim($_POST["updateRemarks"]));
		$updateSpecify = $conn->escape_string(trim($_POST["updateSpecify"]));
		$updateStatus = $conn->escape_string(trim($_POST["updateStatus"]));

		$dataUpdate->updateLaptopData($updateId, $updateServices, $updateTagName, $updateProperty, $updateDesc, $updateActualUser, $updateDate, $updateRemarks, $updateSpecify, $updateStatus);
			showAlertUpdate();
}

$dbConnect->closeConnection();
}
// ============================

// update scanner trigger
if (isset($_POST["btnScannerUpdate"])) {
 $dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$dataUpdate = new UpdateData($conn);

if (isset($_POST["updateScannerId"])) {
	$updateId = $conn->escape_string(trim($_POST["updateScannerId"]));
	$updateServices = $conn->escape_string(trim($_POST["updateServices"]));
	$updateTagName = $conn->escape_string(trim($_POST["updateTagName"]));
	$updateProperty = $conn->escape_string(trim($_POST["updateProperty"]));
	$updateDesc = $conn->escape_string(trim($_POST["updateDesc"]));
	$updateActualUser = $conn->escape_string(trim($_POST["updateActualUser"]));
	$updateDate = $conn->escape_string(trim($_POST["updateDate"]));
	$updateRemarks = $conn->escape_string(trim($_POST["updateRemarks"]));
	$updateSpecify = $conn->escape_string(trim($_POST["updateSpecify"]));
	$updateStatus = $conn->escape_string(trim($_POST["updateStatus"]));

	$dataUpdate->updateScannerData($updateId, $updateServices, $updateTagName, $updateProperty, $updateDesc, $updateActualUser, $updateDate, $updateRemarks, $updateSpecify, $updateStatus);
	showAlertUpdate();
 }
 $dbConnect->closeConnection();
}
// ================


// Category Services Page
class ServiceRecords{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function getServiceRecords($page = 1, $perPage = 7){
$offset = ($page - 1) * $perPage;
$sql = "SELECT * FROM tbl_services ORDER BY services_category ASC LIMIT $perPage OFFSET $offset";
$serviceRec = $this->conn->query($sql);
return $serviceRec;
}

public function getTotalServiceRecordCount(){
$sql = "SELECT COUNT(*) AS total_service_count FROM tbl_services";
$result = $this->conn->query($sql);
$row = $result->fetch_assoc();
return $row["total_service_count"];
}

}


class ServiceManager{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function getService(){

$services = [];

$sql = "SELECT * FROM tbl_services ORDER BY services_category ASC";
$result = $this->conn->query($sql);

while ($row = $result->fetch_assoc()) {
$services[] = $row["services_category"];
}

return $services;
}

public function getUsername(){
	$usernames = [];

	$sql = "SELECT username FROM tbl_m365_acc ORDER BY username ASC";
	$result = $this->conn->query($sql);

	while ($row = $result->fetch_assoc()) {
		$usernames[] = $row["username"];
	}
	$uniqueUsername = array_unique($usernames);
	return $uniqueUsername;
}

public function getAccountName(){
	$account_names = [];

	$sql = "SELECT account_name FROM tbl_m365_acc ORDER BY account_name ASC";
	$result = $this->conn->query($sql);

	while ($row = $result->fetch_assoc()) {
		$account_names[] = $row["account_name"];
	}

	$uniquAccountNames = array_unique($account_names);
	return $uniquAccountNames;
}

public function getDisplayName(){
	$display_names = [];

	$sql = "SELECT display_name FROM tbl_m365_acc ORDER BY display_name ASC";
	$result = $this->conn->query($sql);

	while ($row = $result->fetch_assoc()) {
		$display_names[] = $row["display_name"];
	}

	$uniqueDisplayName = array_unique($display_names);
	return $uniqueDisplayName;
}

}

class ServiceRetrieve{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function dataFetch($id){
$sql = "SELECT * FROM tbl_services WHERE id_services = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row_service = $result->fetch_assoc();
$stmt->close();
return $row_service;
}
}

class InsertServices{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function insertServiceRecords($cat_service){
$sql = "INSERT INTO tbl_services(services_category) VALUES(?)";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("s", $cat_service);
$stmt->execute();
$stmt->close();
}

}

if (isset($_POST["btnAddCat"])) {
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$insertService = new InsertServices($conn);
$insertService->insertServiceRecords($_POST["cat_service"]);
if ($insertService) {
showAlertSuccess();
}
}

class DeleteServices{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function deleteServiceRecords($del_idService){

if (isset($del_idService)) {
// $cat_del = intval($del_idService);
$delCat_records = "DELETE FROM tbl_services WHERE id_services = ?";
$del_stmt = $this->conn->prepare($delCat_records);
$del_stmt->bind_param("i", $del_idService);
$rs_del = $del_stmt->execute();

if ($rs_del == true) {
return true;
}
}

return false;

}
}

if (isset($_POST["btnCatDel"])) {

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$deletCatData = new DeleteServices($conn);

$del_idService = $_POST["del_idService"];

$deleted = $deletCatData->deleteServiceRecords($del_idService);

if ($deleted) {
showAlertDelete();
}

}

class UpdateServices{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function updateServiceRecords($id, $update_services){
$sql = "UPDATE tbl_services SET services_category = ? WHERE id_services = ?";
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("si", $update_services, $id);
$stmt->execute();
$stmt->close();
}
}

if (isset($_POST["btnEditCat"])) {

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$updateServiceData = new UpdateServices($conn);

if (isset($_POST["edit_idService"])) {
$id = $conn->escape_string(trim($_POST["edit_idService"]));
$update_services = $conn->escape_string(trim($_POST["edit_cat_service"]));

$updateServiceData->updateServiceRecords($id, $update_services);
showAlertUpdate();
}

$dbConnect->closeConnection();

}

class FilterServicesCat{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function performFilter($filterService){

$sql = "SELECT * FROM tbl_services WHERE services_category = '$filterService'";
$stmt = $this->conn->query($sql);
$total = $stmt->num_rows;
$data = "";

$data .="

<div id='showDataServices'>
<div  class='table-responsive'>
<table class='table table-hover table-bordered'>
<thead>
<tr class='text-center'>
<th>No.</th>
<th>Services</th>
<th>Actions</th>
</tr>
</thead>
<tbody>";

if ($total > 0) {
$ctr = 1;
while ($row = $stmt->fetch_assoc()) {

$data .="

<tr class='text-center'>
<td>".$ctr."</td>
<td>".$row['services_category']."</td>
<td>
<a href='#' type='button' class='btn btn-sm btn-outline-success service_editId' data-bs-toggle='modal' data-bs-target='#modalUpdateServices' id='".$row['id_services']."'><i class='fa fa-underline'></i></a> <a href='#' type='button' class='btn btn-sm btn-outline-danger service_delId' data-bs-toggle='modal' data-bs-target='#modalDeleteServices' id='".$row['id_services']."'><i class='fa fa-eraser'></i></a>
</td>
</tr>";

$ctr++;
}
}else{
$data .="
<tbody>
<tr>
<td colspan='3' class='text-center'><h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record Found</h3></td>
</tr>
</tbody>
";
}

$data .= "</tbody>
</table></div>";
echo $data;
}
}

if (isset($_POST["filterServices"])) {
$filterServices = $_POST["filterServices"];
include("config.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$filter_service = new FilterServicesCat($conn);
$filter_service->performFilter($filterServices);
$dbConnect->closeConnection();
}

//==========================================

class TotalNumRowsCount{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function getTotalServiceable(){   
$sql = "SELECT COUNT(*) AS total_serviceable FROM tbl_inventory WHERE service_unserviceable='Serviceable'";
$stmt = $this->conn->query($sql);
$row_serviceable = $stmt->fetch_assoc();
$total_serviceable = $row_serviceable["total_serviceable"];
return $total_serviceable;
}

public function getInventoryAging(){
$sql = "SELECT COUNT(*) AS pc_aging FROM tbl_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5";
$stmt = $this->conn->query($sql);
$row_aging = $stmt->fetch_assoc();
$total_aging = $row_aging["pc_aging"];
return $total_aging;
}


public function getTotalUnserviceable(){
$sql = "SELECT COUNT(*) AS total_unserviceable FROM tbl_inventory WHERE service_unserviceable='Unserviceable'";
$stmt = $this->conn->query($sql);
$row_unserviceable = $stmt->fetch_assoc();
$total_unserviceable = $row_unserviceable["total_unserviceable"];
return $total_unserviceable;
}

public function getTotalRecordsCount(){
$sql = "SELECT COUNT(*) AS total_records FROM tbl_inventory";
$stmt = $this->conn->query($sql);
$row_totalRec = $stmt->fetch_assoc();
$total_records = $row_totalRec["total_records"];
return $total_records;
}

// laptop Inventory
public function getLaptopTotalServiceable(){   
$sql = "SELECT COUNT(*) AS total_serviceable FROM tbl_laptop_inventory WHERE service_unserviceable='Serviceable'";
$stmt = $this->conn->query($sql);
$row_serviceable = $stmt->fetch_assoc();
$total_serviceable = $row_serviceable["total_serviceable"];
return $total_serviceable;
}

public function getLaptopInventoryAging(){
$sql = "SELECT COUNT(*) AS pc_aging FROM tbl_laptop_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5";
$stmt = $this->conn->query($sql);
$row_aging = $stmt->fetch_assoc();
$total_aging = $row_aging["pc_aging"];
return $total_aging;
}


public function getLaptopTotalUnserviceable(){
$sql = "SELECT COUNT(*) AS total_unserviceable FROM tbl_laptop_inventory WHERE service_unserviceable='Unserviceable'";
$stmt = $this->conn->query($sql);
$row_unserviceable = $stmt->fetch_assoc();
$total_unserviceable = $row_unserviceable["total_unserviceable"];
return $total_unserviceable;
}

public function getLaptopTotalRecordsCount(){
$sql = "SELECT COUNT(*) AS total_records FROM tbl_laptop_inventory";
$stmt = $this->conn->query($sql);
$row_totalRec = $stmt->fetch_assoc();
$total_records = $row_totalRec["total_records"];
return $total_records;
}
//---------------------------


// apple Inventory
public function getAppleTotalServiceable(){   
$sql = "SELECT COUNT(*) AS total_serviceable FROM tbl_apple_inventory WHERE service_unserviceable='Serviceable'";
$stmt = $this->conn->query($sql);
$row_serviceable = $stmt->fetch_assoc();
$total_serviceable = $row_serviceable["total_serviceable"];
return $total_serviceable;
}

public function getAppleInventoryAging(){
$sql = "SELECT COUNT(*) AS pc_aging FROM tbl_apple_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5";
$stmt = $this->conn->query($sql);
$row_aging = $stmt->fetch_assoc();
$total_aging = $row_aging["pc_aging"];
return $total_aging;
}


public function getAppleTotalUnserviceable(){
$sql = "SELECT COUNT(*) AS total_unserviceable FROM tbl_apple_inventory WHERE service_unserviceable='Unserviceable'";
$stmt = $this->conn->query($sql);
$row_unserviceable = $stmt->fetch_assoc();
$total_unserviceable = $row_unserviceable["total_unserviceable"];
return $total_unserviceable;
}

public function getAppleTotalRecordsCount(){
$sql = "SELECT COUNT(*) AS total_records FROM tbl_apple_inventory";
$stmt = $this->conn->query($sql);
$row_totalRec = $stmt->fetch_assoc();
$total_records = $row_totalRec["total_records"];
return $total_records;
}
// =========================

// scanner inventory
public function getScannerTotalServiceable(){   
$sql = "SELECT COUNT(*) AS total_serviceable FROM tbl_scanner_inventory WHERE service_unserviceable='Serviceable'";
$stmt = $this->conn->query($sql);
$row_serviceable = $stmt->fetch_assoc();
$total_serviceable = $row_serviceable["total_serviceable"];
return $total_serviceable;
}

public function getScannerInventoryAging(){
$sql = "SELECT COUNT(*) AS pc_aging FROM tbl_scanner_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5";
$stmt = $this->conn->query($sql);
$row_aging = $stmt->fetch_assoc();
$total_aging = $row_aging["pc_aging"];
return $total_aging;
}


public function getScannerTotalUnserviceable(){
$sql = "SELECT COUNT(*) AS total_unserviceable FROM tbl_scanner_inventory WHERE service_unserviceable='Unserviceable'";
$stmt = $this->conn->query($sql);
$row_unserviceable = $stmt->fetch_assoc();
$total_unserviceable = $row_unserviceable["total_unserviceable"];
return $total_unserviceable;
}

public function getScannerTotalRecordsCount(){
$sql = "SELECT COUNT(*) AS total_records FROM tbl_scanner_inventory";
$stmt = $this->conn->query($sql);
$row_totalRec = $stmt->fetch_assoc();
$total_records = $row_totalRec["total_records"];
return $total_records;
}

// =======================

// get overall total records count on every tables
public function getAllTotalRecordsCount(){
	$sql = "SELECT SUM(total_records) AS total_records FROM
	(SELECT COUNT(*) AS total_records FROM tbl_inventory 
		UNION ALL 
		SELECT COUNT(*) AS total_records FROM tbl_laptop_inventory 
		UNION ALL  
		SELECT COUNT(*) AS total_records FROM tbl_apple_inventory 
		UNION ALL
		SELECT COUNT(*) AS total_records FROM tbl_scanner_inventory) AS total_counts";

		$stmt = $this->conn->query($sql);
		$row = $stmt->fetch_assoc();
		return $row["total_records"];
}

}

class DesktopSummaryReports{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function getServicesTable(){
$sql = "SELECT DISTINCT services FROM tbl_inventory ORDER BY services ASC";
$service_record = $this->conn->query($sql);
return $service_record;
}

public function getYearsService(){

$sql = "SELECT DISTINCT TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_inventory WHERE date_acquired IS NOT NULL ORDER BY years_of_service DESC";
$recordsYears = $this->conn->query($sql);
return $recordsYears;
}

// itemcount per service base on the years of service if null the value must be 0
public function getItemCountByServiceAndYear($service, $years_of_service){
$sql = "SELECT COUNT(*) AS item_count FROM tbl_inventory WHERE services = '$service' AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) = '$years_of_service'";
$result = $this->conn->query($sql);
$row = $result->fetch_assoc();
return $row["item_count"];
}
}

// create pdf generation function
function generatePDFReport($conn){
// instantiate TCPDF object
$pdf = new TCPDF("L", "mm", [215.9, 330.2], true, "UTF-8", false); // Landscape orientation, folio size, Unicode support

// Set maximum margins
$pdf->SetMargins(10, 10, 10, true); // Set left, top, right, and auto-adjust bottom margin to true

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// remove default header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set font
$pdf->SetFont("helvetica", "", 14);

// Set title
$pdf->SetTitle("PC Inventory Summary");

// Add a page
$pdf->AddPage();

// Set position for text (adjust as needed)
$pdf->SetXY(0, 10);

$pdf->Cell(0, 10, "Pc Summary Report", 0, 5, "C");

$content = "";
// PDF content generation code here 

$content .="<div>
<table id='pdf-pc-table'>
<thead>
<tr>
<th id='table-header'>Office</th>";

// Fetch years of service
$recordYears = new DesktopSummaryReports($conn);
$yr_records = $recordYears->getYearsService();

// Add years of service to table header
foreach ($yr_records as $year) {
$content .= "<th>" . $year["years_of_service"] . " Yrs</th>";
}

$content .= "</tr>
</thead>
<tbody>";

// Fetch service records
$serviceRecords = new DesktopSummaryReports($conn);
$records_service = $serviceRecords->getServicesTable();

// Populate table rows with service records
while ($row_service = $records_service->fetch_assoc()) {
$content .= "<tr>
<td class='services'>" . $row_service["services"] . "</td>";

foreach ($yr_records as $year) {
    $itemCount = $serviceRecords->getItemCountByServiceAndYear($row_service["services"], $year["years_of_service"]);
    if($itemCount == 0){
		$content .= "<td></td>";
	}else{
		$content .= "<td>" . $itemCount . "</td>";
	}
}

$content .= "</tr>";
}

$content .= "
</tbody>
</table>
</div>

<style>
#pdf-pc-table{
	border-collapase: collapse;
}

#pdf-pc-table th:first-child, th, td{
	border: 1px solid #7E7D7C;
	text-align: center;
}

#pdf-pc-table .services{
	width: auto !important;
	max-width: 12% !important;
}
</style>
";


// Output PDF
// $pdf->writeHTML($content);
$pdf->writeHTML($content, false, false, false, false, '');
// $pdf->writeHTMLCell(300, 0, "", "", $content, 1);
$pdf->Output('pc_summary_report.pdf', 'I');
}


// Check if PDF generation requested
if(isset($_GET["generate_pdf"])){
include("../tcpdf/tcpdf.php");
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();
// Call PDF generation function
generatePDFReport($conn);
exit(); // Stop further execution
}

class UserAccountSettings{
private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function userAccount(){
$sql = "SELECT * FROM tbl_users WHERE user_id = '".$_SESSION["user_id"]."'";
$userRecords = $this->conn->query($sql);
return $userRecords;
}
}


// archives sections
class RecordsPcArchives{
private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function getArchivesRecords($page = 1, $perPage = 5){
$offset = ($page - 1) * $perPage;
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_inventory_archive ORDER BY services ASC LIMIT $perPage OFFSET $offset";
$records_archive = $this->conn->query($sql);
return $records_archive;

}

// get all the total records
public function getTotalRecordsCount(){
$sql = "SELECT COUNT(*) AS total_records FROM tbl_inventory_archive";
$result = $this->conn->query($sql);
$row = $result->fetch_assoc();
return $row["total_records"];
}

// get all the total records
public function getLaptopTotalRecordsCount(){
$sql = "SELECT COUNT(*) AS total_records FROM tbl_laptop_inventory_archive";
$result = $this->conn->query($sql);
$row = $result->fetch_assoc();
return $row["total_records"];
}

public function getLaptopArchivesRecords($page = 1, $perPage = 5){
$offset = ($page - 1) * $perPage;
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_laptop_inventory_archive ORDER BY services ASC LIMIT $perPage OFFSET $offset";
$records_archive = $this->conn->query($sql);
return $records_archive;

}
// =========================

// apple archives
public function getAppleTotalRecordsCount(){
$sql = "SELECT COUNT(*) AS total_records FROM tbl_apple_inventory_archive";
$result = $this->conn->query($sql);
$row = $result->fetch_assoc();
return $row["total_records"];
}

public function getAppleArchivesRecords($page = 1, $perPage = 5){
$offset = ($page - 1) * $perPage;
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_apple_inventory_archive ORDER BY services ASC LIMIT $perPage OFFSET $offset";
$records_archive = $this->conn->query($sql);
return $records_archive;

}
// scanner archives
public function getScannerTotalRecordsCount(){
$sql = "SELECT COUNT(*) AS total_records FROM tbl_scanner_inventory_archive";
$result = $this->conn->query($sql);
$row = $result->fetch_assoc();
return $row["total_records"];
}

public function getScannerArchivesRecords($page = 1, $perPage = 5){
$offset = ($page - 1) * $perPage;
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_scanner_inventory_archive ORDER BY services ASC LIMIT $perPage OFFSET $offset";
$records_archive = $this->conn->query($sql);
return $records_archive;
}

}


// pc aging 
class PcAgingManager{
private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function getInventoryAging(){
$sql = "SELECT COUNT(*) AS pc_aging FROM tbl_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5";
$stmt = $this->conn->query($sql);
$row_aging = $stmt->fetch_assoc();
$total_aging = $row_aging["pc_aging"];
return $total_aging;
}

public function getAgingRecords($page = 1, $perPage = 5){
$offset = ($page - 1) * $perPage;
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS pc_aging FROM tbl_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5 ORDER BY services ASC LIMIT $perPage OFFSET $offset";
$records = $this->conn->query($sql);
return $records;
}
}

// pc aging filter
class PcAgingFilter{
private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function performAgingFilter($pcAgeFilter){

$count_sql = "SELECT COUNT(*) AS total_count FROM tbl_inventory WHERE services IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5 AND services = '$pcAgeFilter'";
$count_res = $this->conn->query($count_sql);
$row_ctr = $count_res->fetch_assoc();
$total_count_service = $row_ctr["total_count"];

$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS pc_aging FROM tbl_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5 AND services = '$pcAgeFilter'";
$get = $this->conn->query($sql);
$data = "";

$data .="
<div class='table-responsive' id='showAgeData'>
<table class='table table-hover'>
	<thead>
	<tr class='text-center'>
		<th>No.</th>
		<th>Services</th>
		<th>Tag Name</th>
		<th>Description</th>
		<th>Property Tag</th>
		<th>Date Acquired</th>
		<th>Years services</th>
		<th>Actual user</th>
		<th>Remarks</th>
		<th>Specific</th>
		<th>Status</th>
	</tr>
</thead>
<tbody>
";

if($get->num_rows > 0){
  $ctr = 1;
  while($row_agingFilter = $get->fetch_assoc()){
	$origDate = $row_agingFilter["date_acquired"];
	$dateTime = new DateTime($origDate);
	$formatDate = $dateTime->format("M d, Y");
	
	$data .="
	
	<tr class='text-center'>
	<td>".$ctr."</td>
	<td>".$row_agingFilter["services"]."</td>
	<td width='20%'>".$row_agingFilter["property_tag_name"]."</td>
	<td width='20%'>".$row_agingFilter["description"]."</td>
	<td width='10%'>".$row_agingFilter["property_tag"]."</td>
	<td width='14%'>".$formatDate."</td>
	<td width='1%'>".$row_agingFilter["pc_aging"]."</td>
	<td width='20%'>".$row_agingFilter["actual_user"]."</td>
	<td>".$row_agingFilter["remarks"]."</td>
	<td>".$row_agingFilter["specify"]."</td>
	<td>".$row_agingFilter["service_unserviceable"]."</td>
	</tr>
	
	";

	$ctr++;	
}	

$data .="
	<tr>
	<th class='border border-2' colspan='4'><span class='text-primary fs-5'>Total Aging Count Per Service:</th>
	<td class='border border-2 fw-bolder text-primary fs-5'>".$total_count_service."</td>
	</tr>";

}else{
$data .= "
 <tr>
  <td colspan='10' class='text-center fw-bolder'>
   <h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
  </td>
 </tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
}
}

// trigger the aging filter
if(isset($_POST["pcAgeFilter"])){
$filter = $_POST["pcAgeFilter"];
include("config.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$pcAgingFilter = new PcAgingFilter($conn);
$pcAgingFilter->performAgingFilter($filter);
$dbConnect->closeConnection();
}

// filter laptop by years of service
class FilterLaptopYrsService{

	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}

	public function performFilterYrsLaptop($filter){

 $sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_laptop_inventory WHERE TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) = '$filter'";

$count_year = $this->conn->query($sql);
$row_yr = $count_year->fetch_assoc();
$year_count = $row_yr["years_of_service"];
$row_count = $count_year->num_rows;

$get = $this->conn->query($sql);

$data = "";

$data .="
<div class='table-responsive'>
<table class='table table-hover'>
<thead>
<tr class='text-center'>
<th>Services</th>
<th>Tag Name</th>
<th>Description</th>
<th>Property Tag</th>
<th>Date Acquired</th>
<th>Years services</th>
<th>Actual user</th>
<th>Remarks</th>
<th>Specific</th>
<th>Status</th>
<th class='lastChild'>Actions</th>
</tr>
</thead>
<tbody>";

if ($get->num_rows > 0) {
while ($row_filter = $get->fetch_assoc()) {
$origDate = $row_filter["date_acquired"];
$dateTime = new DateTime($origDate);
$formatDate = $dateTime->format("M d, Y");

$data .= "
<tr class='text-center'> 
<td>".$row_filter["services"]."</td>
<td width='1%'>".$row_filter["property_tag_name"]."</td>
<td width='20%'>".$row_filter["description"]."</td>
<td width='20%'>".$row_filter["property_tag"]."</td>
<td width='14%'>".$formatDate."</td>
<td width='1%'>".$row_filter["years_of_service"]."</td>
<td width='1%'>".$row_filter["actual_user"]."</td>
<td>".$row_filter["remarks"]."</td>
<td>".$row_filter["specify"]."</td>
<td>".$row_filter["service_unserviceable"]."</td>
<td width='10%' class='lastChild'>
<a href='#' type='button' class='btn btn-sm btn-outline-success editLaptopId' data-bs-toggle='modal' data-bs-target='#modalUpdate' id='".$row_filter['id']."'><i class='fa fa-underline'></i></a> <a href='#' type='button' class='btn btn-sm btn-outline-danger delLaptopId' data-bs-toggle='modal' data-bs-target='#modalDelete' id='".$row_filter['id']."'><i class='fa fa-eraser'></i></a>
</td>
</tr>";
}

$data .="

<tr>
<th class='border border-2' colspan='4'><span class='text-primary fs-5'>".$year_count."</span> year/s of service total count:</th>
<td class='border border-2 fw-bolder text-primary fs-5'>".$row_count."</td>
</tr>

";

} else {
$data .= "
<tr>
<td colspan='12' class='text-center fw-bolder'>
<h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
</td>
</tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
 }
}

if (isset($_POST["filterYrs"])) {
	$filter = $_POST["filterYrs"];
	include("config.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$laptopFilterYrs = new FilterLaptopYrsService($conn);
$laptopFilterYrs->performFilterYrsLaptop($filter);
$dbConnect->closeConnection();
}

class LiveLaptopTypeFilter{

	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}

	public function performLaptopFilterType($filterLaptopType){

$sql_count = "SELECT COUNT(*) AS total_count FROM tbl_laptop_inventory WHERE LEFT(property_tag_name, LENGTH('$filterLaptopType')) = '$filterLaptopType' || property_tag LIKE '%$filterLaptopType%' || LEFT(services, LENGTH('$filterLaptopType')) = '$filterLaptopType' || LEFT(remarks, LENGTH('$filterLaptopType')) = '$filterLaptopType' || LEFT(service_unserviceable, LENGTH('$filterLaptopType')) = '$filterLaptopType'";
$count_result = $this->conn->query($sql_count);
$row = $count_result->fetch_assoc();
$total_count = $row['total_count'];

$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_laptop_inventory WHERE LEFT(property_tag_name, LENGTH('$filterLaptopType')) = '$filterLaptopType' || property_tag LIKE '%$filterLaptopType%' || LEFT(services, LENGTH('$filterLaptopType')) = '$filterLaptopType' || LEFT(remarks, LENGTH('$filterLaptopType')) = '$filterLaptopType' || LEFT(service_unserviceable, LENGTH('$filterLaptopType')) = '$filterLaptopType' ORDER BY years_of_service DESC";

$get = $this->conn->query($sql);

$data = "";

$data .="
<div class='table-responsive'>
<table class='table table-hover'>
<thead>
<tr class='text-center'>
<th>Services</th>
<th>Tag Name</th>
<th>Description</th>
<th>Property Tag</th>
<th>Date Acquired</th>
<th>Years services</th>
<th>Actual user</th>
<th>Remarks</th>
<th>Specific</th>
<th>Status</th>
<th class='lastChild'>Actions</th>
</tr>
</thead>
<tbody>";

if ($get->num_rows > 0) {
while ($row_filter = $get->fetch_assoc()) {
$origDate = $row_filter["date_acquired"];
$dateTime = new DateTime($origDate);
$formatDate = $dateTime->format("M d, Y");

$data .= "
<tr class='text-center'> 
<td>".$row_filter["services"]."</td>
<td width='1%'>".$row_filter["property_tag_name"]."</td>
<td width='20%'>".$row_filter["description"]."</td>
<td width='20%'>".$row_filter["property_tag"]."</td>
<td width='14%'>".$formatDate."</td>
<td width='1%'>".$row_filter["years_of_service"]."</td>
<td width='1%'>".$row_filter["actual_user"]."</td>
<td>".$row_filter["remarks"]."</td>
<td>".$row_filter["specify"]."</td>
<td>".$row_filter["service_unserviceable"]."</td>
<td width='10%' class='lastChild'>
<a href='#' type='button' class='btn btn-sm btn-outline-success editLaptopId' data-bs-toggle='modal' data-bs-target='#modalUpdate' id='".$row_filter['id']."'><i class='fa fa-underline'></i></a> <a href='#' type='button' class='btn btn-sm btn-outline-danger delLaptopId' data-bs-toggle='modal' data-bs-target='#modalDelete' id='".$row_filter['id']."'><i class='fa fa-eraser'></i></a>
</td>
</tr>";
 }

$data .="
<tr>
	<th class='border border-2 fw-bolder' colspan='3'>Total count:</th>
	<td class='border border-2 text-success fw-bolder'>".$total_count."</td>
</tr>
";

} else {
$data .= "
<tr>
<td colspan='12' class='text-center fw-bolder'>
<h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
</td>
</tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
	}
}

// trigger the filter laptop type
if (isset($_POST["filterLaptopType"])) {
	$filter = $_POST["filterLaptopType"];
	include("config.php");

	$dbConnect = new Connection($server, $user, $pass, $db_name);
	$conn = $dbConnect->connectDb();

	$liveSearch = new LiveLaptopTypeFilter($conn);
	$liveSearch->performLaptopFilterType($filter);
	$dbConnect->closeConnection();
}

// filer laptop archives by type
class LiveLaptopTypeArchiveFiler{
	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}

	public function performLaptopFilterType($filterTypeArchLaptop){

	$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_laptop_inventory_archive WHERE LEFT(property_tag_name, LENGTH('$filterTypeArchLaptop')) = '$filterTypeArchLaptop' || property_tag LIKE '%$filterTypeArchLaptop%' || LEFT(services, LENGTH('$filterTypeArchLaptop')) = '$filterTypeArchLaptop' || LEFT(remarks, LENGTH('$filterTypeArchLaptop')) = '$filterTypeArchLaptop' || LEFT(service_unserviceable, LENGTH('$filterTypeArchLaptop')) = '$filterTypeArchLaptop' ORDER BY years_of_service DESC";

$get = $this->conn->query($sql);

$data = "";

$data .="
<div class='table-responsive'>
<table class='table table-hover'>
<thead>
<tr class='text-center'>
<th>No.</th>
<th>Services</th>
<th>Tag Name</th>
<th>Description</th>
<th>Property Tag</th>
<th>Date Acquired</th>
<th>Years services</th>
<th>Actual user</th>
<th>Remarks</th>
<th>Specific</th>
<th>Status</th>
<th class='lastChild'>Actions</th>
</tr>
</thead>
<tbody>";

if ($get->num_rows > 0) {
$ctr = 1;
while ($row_filter = $get->fetch_assoc()) {
$origDate = $row_filter["date_acquired"];
$dateTime = new DateTime($origDate);
$formatDate = $dateTime->format("M d, Y");

$data .= "
<tr class='text-center'> 
<td>".$ctr."</td>
<td>".$row_filter["services"]."</td>
<td width='1%'>".$row_filter["property_tag_name"]."</td>
<td width='20%'>".$row_filter["description"]."</td>
<td width='20%'>".$row_filter["property_tag"]."</td>
<td width='14%'>".$formatDate."</td>
<td width='1%'>".$row_filter["years_of_service"]."</td>
<td width='1%'>".$row_filter["actual_user"]."</td>
<td>".$row_filter["remarks"]."</td>
<td>".$row_filter["specify"]."</td>
<td>".$row_filter["service_unserviceable"]."</td>
<td width='10%' class='lastChild'>
<a href='#' type='button' class='btn btn-outline-success restore-laptop' data-bs-toggle='modal' data-bs-target='#modalPCRestore' id='".$row_filter['id']."'><i class='fa-solid fa-plus'></i></a>
</td>
</tr>";
 }
 $ctr++;
} else {
$data .= "
<tr>
<td colspan='12' class='text-center fw-bolder'>
<h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
</td>
</tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
	}
}

// trigger the filter laptop type
if (isset($_POST["filterTypeArchLaptop"])) {
	$filter = $_POST["filterTypeArchLaptop"];
	include("config.php");

	$dbConnect = new Connection($server, $user, $pass, $db_name);
	$conn = $dbConnect->connectDb();

	$liveSearch = new LiveLaptopTypeArchiveFiler($conn);
	$liveSearch->performLaptopFilterType($filter);
	$dbConnect->closeConnection();
}


class LaptopSummaryReport{
private $conn;

public function __construct($conn){
	$this->conn = $conn;
}

public function getServicesTable(){
$sql = "SELECT DISTINCT services FROM tbl_laptop_inventory ORDER BY services DESC";
$service_record = $this->conn->query($sql);
return $service_record;
}

public function getYearsService(){

$sql = "SELECT DISTINCT TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_laptop_inventory WHERE date_acquired IS NOT NULL ORDER BY years_of_service DESC";
$recordsYears = $this->conn->query($sql);
return $recordsYears;
}

// itemcount per service base on the years of service if null the value must be 0
public function getItemCountByServiceAndYear($service, $years_of_service){
$sql = "SELECT COUNT(*) AS item_count FROM tbl_laptop_inventory WHERE services = '$service' AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) = '$years_of_service'";
$result = $this->conn->query($sql);
$row = $result->fetch_assoc();
return $row["item_count"];
 }
}

// create pdf generation function
function generateLaptopPDFReport($conn){
// instantiate TCPDF object
$pdf = new TCPDF("L", "mm", [215.9, 330.2], true, "UTF-8", false); // Landscape orientation, folio size, Unicode support

// Set maximum margins
$pdf->SetMargins(10, 10, 10, true); // Set left, top, right, and auto-adjust bottom margin to true

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// remove default header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set font
$pdf->SetFont("helvetica", "", 14);

// Set title
$pdf->SetTitle("Laptop Inventory Summary");

// Add a page
$pdf->AddPage();

// Set position for text (adjust as needed)
$pdf->SetXY(0, 10);

$pdf->Cell(0, 10, "Laptop Summary Report", 0, 5, "C");

$content = "";
// PDF content generation code here 

$content .="<div>
<table id='pdf-laptop-table'>
<thead>
<tr>
<th id='table-header'>Office</th>";

// Fetch years of service
$recordYears = new LaptopSummaryReport($conn);
$yr_records = $recordYears->getYearsService();

// Add years of service to table header
foreach ($yr_records as $year) {
$content .= "<th>" . $year["years_of_service"] . " Yrs</th>";
}

$content .= "</tr>
</thead>
<tbody>";

// Fetch service records
$serviceRecords = new LaptopSummaryReport($conn);
$records_service = $serviceRecords->getServicesTable();

// Populate table rows with service records
while ($row_service = $records_service->fetch_assoc()) {
$content .= "<tr>
<td class='services'>" . $row_service["services"] . "</td>";

foreach ($yr_records as $year) {
    $itemCount = $serviceRecords->getItemCountByServiceAndYear($row_service["services"], $year["years_of_service"]);
    if($itemCount == 0){
		$content .= "<td></td>";
	}else{
		$content .= "<td>" . $itemCount . "</td>";
	}
}

$content .= "</tr>";
}

$content .= "
</tbody>
</table>
</div>

<style>
#pdf-laptop-table{
	border-collapase: collapse;
}

#pdf-laptop-table th:first-child, th, td{
	border: 1px solid #7E7D7C;
	text-align: center;
}

#pdf-laptop-table .services{
	width: auto !important;
	max-width: 12% !important;
}
</style>
";


// Output PDF
// $pdf->writeHTML($content);
$pdf->writeHTML($content, false, false, false, false, '');
// $pdf->writeHTMLCell(300, 0, "", "", $content, 1);
$pdf->Output('laptop_summary_report.pdf', 'I');
}


// Check if PDF generation requested
if(isset($_GET["generate_laptop_pdf"])){
include("../tcpdf/tcpdf.php");
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();
// Call PDF generation function
generateLaptopPDFReport($conn);
exit(); // Stop further execution
}

// laptop aging Manager
class LaptopAgingManager{
	private $conn;

	public function __construct($conn){
	 $this->conn = $conn;
 }

 public function getInventoryAging(){
$sql = "SELECT COUNT(*) AS pc_aging FROM tbl_laptop_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5";
$stmt = $this->conn->query($sql);
$row_aging = $stmt->fetch_assoc();
$total_aging = $row_aging["pc_aging"];
return $total_aging;
}

public function getAgingRecords($page = 1, $perPage = 5){
$offset = ($page - 1) * $perPage;
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS pc_aging FROM tbl_laptop_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5 ORDER BY services ASC LIMIT $perPage OFFSET $offset";
$records = $this->conn->query($sql);
return $records;
}

}

class LaptopAgeFilter{
		private $conn;

	public function __construct($conn){
	 $this->conn = $conn;
 }

 public function performLaptopAgeFilter($laptopAgeFilter){
$count_sql = "SELECT COUNT(*) AS total_count FROM tbl_laptop_inventory WHERE services IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5 AND services = '$laptopAgeFilter'";
$count_res = $this->conn->query($count_sql);
$row_ctr = $count_res->fetch_assoc();
$total_count_service = $row_ctr["total_count"];

$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS pc_aging FROM tbl_laptop_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5 AND services = '$laptopAgeFilter'";
$get = $this->conn->query($sql);
$data = "";

$data .="
<div class='table-responsive' id='showAgeData'>
<table class='table table-hover'>
	<thead>
	<tr class='text-center'>
		<th>No.</th>
		<th>Services</th>
		<th>Tag Name</th>
		<th>Description</th>
		<th>Property Tag</th>
		<th>Date Acquired</th>
		<th>Years services</th>
		<th>Actual user</th>
		<th>Remarks</th>
		<th>Specific</th>
		<th>Status</th>
	</tr>
</thead>
<tbody>
";

if($get->num_rows > 0){
  $ctr = 1;
  while($row_agingFilter = $get->fetch_assoc()){
	$origDate = $row_agingFilter["date_acquired"];
	$dateTime = new DateTime($origDate);
	$formatDate = $dateTime->format("M d, Y");
	
	$data .="
	
	<tr class='text-center'>
	<td>".$ctr."</td>
	<td>".$row_agingFilter["services"]."</td>
	<td width='20%'>".$row_agingFilter["property_tag_name"]."</td>
	<td width='20%'>".$row_agingFilter["description"]."</td>
	<td width='10%'>".$row_agingFilter["property_tag"]."</td>
	<td width='14%'>".$formatDate."</td>
	<td width='1%'>".$row_agingFilter["pc_aging"]."</td>
	<td width='20%'>".$row_agingFilter["actual_user"]."</td>
	<td>".$row_agingFilter["remarks"]."</td>
	<td>".$row_agingFilter["specify"]."</td>
	<td>".$row_agingFilter["service_unserviceable"]."</td>
	</tr>
	
	";

	$ctr++;	
}	

$data .="
	<tr>
	<th class='border border-2' colspan='4'><span class='text-primary fs-5'>Total Aging Count Per Service:</th>
	<td class='border border-2 fw-bolder text-primary fs-5'>".$total_count_service."</td>
	</tr>";

}else{
$data .= "
 <tr>
  <td colspan='10' class='text-center fw-bolder'>
   <h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
  </td>
 </tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
 }
}

// trigger the aging filter
if(isset($_POST["laptopAgeFilter"])){
$filter = $_POST["laptopAgeFilter"];
include("config.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$pcAgingFilter = new LaptopAgeFilter($conn);
$pcAgingFilter->performLaptopAgeFilter($filter);
$dbConnect->closeConnection();
}


// appple summary
class AppleSummaryReport{
private $conn;

public function __construct($conn){
	$this->conn = $conn;
}

public function getServicesTable(){
$sql = "SELECT DISTINCT services FROM tbl_apple_inventory ORDER BY services ASC";
$service_record = $this->conn->query($sql);
return $service_record;
}

public function getYearsService(){

$sql = "SELECT DISTINCT TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_apple_inventory WHERE date_acquired IS NOT NULL ORDER BY years_of_service DESC";
$recordsYears = $this->conn->query($sql);
return $recordsYears;
}

// itemcount per service base on the years of service if null the value must be 0
public function getItemCountByServiceAndYear($service, $years_of_service){
$sql = "SELECT COUNT(*) AS item_count FROM tbl_apple_inventory WHERE services = '$service' AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) = '$years_of_service'";
$result = $this->conn->query($sql);
$row = $result->fetch_assoc();
return $row["item_count"];
 }
}

// create pdf generation function
function generateApplePDFReport($conn){
// instantiate TCPDF object
$pdf = new TCPDF("L", "mm", [215.9, 330.2], true, "UTF-8", false); // Landscape orientation, folio size, Unicode support

// Set maximum margins
$pdf->SetMargins(10, 10, 10, true); // Set left, top, right, and auto-adjust bottom margin to true

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// remove default header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set font
$pdf->SetFont("helvetica", "", 14);

// Set title
$pdf->SetTitle("Apple Inventory Summary");

// Add a page
$pdf->AddPage();

// Set position for text (adjust as needed)
$pdf->SetXY(0, 10);

$pdf->Cell(0, 10, "Apple Summary Report", 0, 5, "C");

$content = "";
// PDF content generation code here 

$content .="<div>
<table id='pdf-laptop-table'>
<thead>
<tr>
<th id='table-header'>Office</th>";

// Fetch years of service
$recordYears = new AppleSummaryReport($conn);
$yr_records = $recordYears->getYearsService();

// Add years of service to table header
foreach ($yr_records as $year) {
$content .= "<th>" . $year["years_of_service"] . " Yrs</th>";
}

$content .= "</tr>
</thead>
<tbody>";

// Fetch service records
$serviceRecords = new AppleSummaryReport($conn);
$records_service = $serviceRecords->getServicesTable();

// Populate table rows with service records
while ($row_service = $records_service->fetch_assoc()) {
$content .= "<tr>
<td class='services'>" . $row_service["services"] . "</td>";

foreach ($yr_records as $year) {
    $itemCount = $serviceRecords->getItemCountByServiceAndYear($row_service["services"], $year["years_of_service"]);
    if($itemCount == 0){
		$content .= "<td></td>";
	}else{
		$content .= "<td>" . $itemCount . "</td>";
	}
}

$content .= "</tr>";
}

$content .= "
</tbody>
</table>
</div>

<style>
#pdf-laptop-table{
	border-collapase: collapse;
}

#pdf-laptop-table th:first-child, th, td{
	border: 1px solid #7E7D7C;
	text-align: center;
}

#pdf-laptop-table .services{
	width: auto !important;
	max-width: 12% !important;
}
</style>
";


// Output PDF
// $pdf->writeHTML($content);
$pdf->writeHTML($content, false, false, false, false, '');
// $pdf->writeHTMLCell(300, 0, "", "", $content, 1);
$pdf->Output('apple_summary_report.pdf', 'I');
}


// Check if PDF generation requested
if(isset($_GET["generate_apple_pdf"])){
include("../tcpdf/tcpdf.php");
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();
// Call PDF generation function
generateApplePDFReport($conn);
exit(); // Stop further execution
}

// ============================

// appple aging records
class AppleAgingManager{
	private $conn;

	public function __construct($conn){
	 $this->conn = $conn;
 }

 public function getInventoryAging(){
$sql = "SELECT COUNT(*) AS pc_aging FROM tbl_apple_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5";
$stmt = $this->conn->query($sql);
$row_aging = $stmt->fetch_assoc();
$total_aging = $row_aging["pc_aging"];
return $total_aging;
}

public function getAgingRecords($page = 1, $perPage = 5){
$offset = ($page - 1) * $perPage;
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS pc_aging FROM tbl_apple_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5 ORDER BY services ASC LIMIT $perPage OFFSET $offset";
$records = $this->conn->query($sql);
return $records;
}

}

// apple age filter
class AppleAgeFilter{
		private $conn;

	public function __construct($conn){
	 $this->conn = $conn;
 }

 public function performAppleAgeFilter($appleAgingFilter){
$count_sql = "SELECT COUNT(*) AS total_count FROM tbl_apple_inventory WHERE services IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5 AND services = '$appleAgingFilter'";
$count_res = $this->conn->query($count_sql);
$row_ctr = $count_res->fetch_assoc();
$total_count_service = $row_ctr["total_count"];

$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS pc_aging FROM tbl_apple_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5 AND services = '$appleAgingFilter'";
$get = $this->conn->query($sql);
$data = "";

$data .="
<div class='table-responsive'>
<table class='table table-hover'>
	<thead>
	<tr class='text-center'>
		<th>No.</th>
		<th>Services</th>
		<th>Tag Name</th>
		<th>Description</th>
		<th>Property Tag</th>
		<th>Date Acquired</th>
		<th>Years services</th>
		<th>Actual user</th>
		<th>Remarks</th>
		<th>Status</th>
	</tr>
</thead>
<tbody>
";

if($get->num_rows > 0){
  $ctr = 1;
  while($row_agingFilter = $get->fetch_assoc()){
	$origDate = $row_agingFilter["date_acquired"];
	$dateTime = new DateTime($origDate);
	$formatDate = $dateTime->format("M d, Y");
	
	$data .="
	
	<tr class='text-center'>
	<td>".$ctr."</td>
	<td>".$row_agingFilter["services"]."</td>
	<td width='20%'>".$row_agingFilter["property_tag_name"]."</td>
	<td width='20%'>".$row_agingFilter["description"]."</td>
	<td width='10%'>".$row_agingFilter["property_tag"]."</td>
	<td width='14%'>".$formatDate."</td>
	<td width='1%'>".$row_agingFilter["pc_aging"]."</td>
	<td width='20%'>".$row_agingFilter["actual_user"]."</td>
	<td>".$row_agingFilter["remarks"]."</td>
	<td>".$row_agingFilter["service_unserviceable"]."</td>
	</tr>
	
	";

	$ctr++;	
}	

$data .="
	<tr>
	<th class='border border-2' colspan='4'><span class='text-primary fs-5'>Total Aging Count Per Service:</th>
	<td class='border border-2 fw-bolder text-primary fs-5'>".$total_count_service."</td>
	</tr>";

}else{
$data .= "
 <tr>
  <td colspan='10' class='text-center fw-bolder'>
   <h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
  </td>
 </tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
 }
}

// trigger the aging filter
if(isset($_POST["appleAgingFilter"])){
$filter = $_POST["appleAgingFilter"];
include("config.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$pcAgingFilter = new AppleAgeFilter($conn);
$pcAgingFilter->performAppleAgeFilter($filter);
$dbConnect->closeConnection();
}

// filter apple by date acquired
class LiveSearchAppleBydateAcquired{

	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}

	// perform apple filter by date acquired
	public function performAppleBydate($filterApple){
		// Count the total number of rows based on the filter
$count_query = "SELECT COUNT(*) AS total_count FROM tbl_apple_inventory WHERE date_acquired = '$filterApple'";
$count_result = $this->conn->query($count_query);
$row = $count_result->fetch_assoc();
$total_count = $row['total_count'];

// Retrieve filtered records along with years of service
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_apple_inventory WHERE date_acquired = '$filterApple'";
$count_year = $this->conn->query($sql);
$row_yr = $count_year->fetch_assoc();
$year_count = $row_yr["years_of_service"];

$get = $this->conn->query($sql);
$data = "";

$data .="
<div class='table-responsive'>
<table class='table table-hover'>
<thead>
<tr class='text-center'>
<th>Services</th>
<th>Tag Name</th>
<th>Description</th>
<th>Property Tag</th>
<th>Date Acquired</th>
<th>Years services</th>
<th>Actual user</th>
<th>Remarks</th>
<th>Status</th>
<th class='lastChild'>Actions</th>
</tr>
</thead>
<tbody>";

if ($get->num_rows > 0) {
while ($row_filter = $get->fetch_assoc()) {
$origDate = $row_filter["date_acquired"];
$dateTime = new DateTime($origDate);
$formatDate = $dateTime->format("M d, Y");

$data .= "
<tr class='text-center'>
<td>".$row_filter["services"]."</td>
<td width='1%'>".$row_filter["property_tag_name"]."</td>
<td width='20%'>".$row_filter["description"]."</td>
<td width='20%'>".$row_filter["property_tag"]."</td>
<td width='14%'>".$formatDate."</td>
<td width='1%'>".$row_filter["years_of_service"]."</td>
<td width='1%'>".$row_filter["actual_user"]."</td>
<td>".$row_filter["remarks"]."</td>
<td>".$row_filter["service_unserviceable"]."</td>
<td width='10%' class='lastChild'>

<a href='#' type='button' class='btn btn-sm btn-outline-success editAppleId' data-bs-toggle='modal' data-bs-target='#modalUpdate' id='".$row_filter['id']."'><i class='fa fa-underline'></i></a> <a href='#' type='button' class='btn btn-sm btn-outline-danger delAppleId' data-bs-toggle='modal' data-bs-target='#modalDelete' id='".$row_filter['id']."'><i class='fa fa-eraser'></i></a>

</td>
</tr>";
}

$data .="

<tr>
<th class='border border-2' colspan='4'><span class='text-primary fs-5'>".$year_count."</span> year/s of service total count:</th>
<td class='border border-2 fw-bolder text-primary fs-5'>".$total_count."</td>
</tr>

";

} else {
$data .= "
<tr>
<td colspan='12' class='text-center fw-bolder'>
<h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
</td>
</tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
	}
}

// trigger the filter by date acquired
if (isset($_POST["filterApple"])) {
	$filter = $_POST["filterApple"];
	include("config.php");

	$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$liveFilter = new LiveSearchAppleBydateAcquired($conn);
$liveFilter->performAppleBydate($filter);
$dbConnect->closeConnection();
}
// ===========================

// filter apple by years
class FilterAppleYearsService{
	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}

	public function performFilter($filterAppleYrService){
		$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_apple_inventory WHERE TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) = '$filterAppleYrService'";
$count_year = $this->conn->query($sql);
$row_yr = $count_year->fetch_assoc();
$year_count = $row_yr["years_of_service"];
$row_count = $count_year->num_rows;

$get = $this->conn->query($sql);
$data = "";

$data .="
<div class='table-responsive'>
<table class='table table-hover'>
<thead>
<tr class='text-center'>
<th>Services</th>
<th>Tag Name</th>
<th>Description</th>
<th>Property Tag</th>
<th>Date Acquired</th>
<th>Years services</th>
<th>Actual user</th>
<th>Remarks</th>
<th>Status</th>
<th class='lastChild'>Actions</th>
</tr>
</thead>
<tbody>";

if ($get->num_rows > 0) {
while ($row_filter = $get->fetch_assoc()) {
$origDate = $row_filter["date_acquired"];
$dateTime = new DateTime($origDate);
$formatDate = $dateTime->format("M d, Y");

$data .= "
<tr class='text-center'> 
<td>".$row_filter["services"]."</td>
<td width='1%'>".$row_filter["property_tag_name"]."</td>
<td width='20%'>".$row_filter["description"]."</td>
<td width='20%'>".$row_filter["property_tag"]."</td>
<td width='14%'>".$formatDate."</td>
<td width='1%'>".$row_filter["years_of_service"]."</td>
<td width='1%'>".$row_filter["actual_user"]."</td>
<td>".$row_filter["remarks"]."</td>
<td>".$row_filter["service_unserviceable"]."</td>
<td width='10%' class='lastChild'>

<a href='#' type='button' class='btn btn-sm btn-outline-success editAppleId' data-bs-toggle='modal' data-bs-target='#modalUpdate' id='".$row_filter['id']."'><i class='fa fa-underline'></i></a> <a href='#' type='button' class='btn btn-sm btn-outline-danger delAppleId' data-bs-toggle='modal' data-bs-target='#modalDelete' id='".$row_filter['id']."'><i class='fa fa-eraser'></i></a>

</td>
</tr>";
}

$data .="

<tr>
<th class='border border-2' colspan='4'><span class='text-primary fs-5'>".$year_count."</span> year/s of service total count:</th>
<td class='border border-2 fw-bolder text-primary fs-5'>".$row_count."</td>
</tr>

";

} else {
$data .= "
<tr>
<td colspan='12' class='text-center fw-bolder'>
<h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
</td>
</tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
	}
}

// trigger the filter apple by years
if (isset($_POST["filterAppleYrService"])) {
$filter = $_POST["filterAppleYrService"];
include("config.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$liveFilterYrs = new FilterAppleYearsService($conn);
$liveFilterYrs->performFilter($filter);
$dbConnect->closeConnection();
}

// ===============================

// filter apple by categories
class LiveFilterAppleType{
	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}

	public function performFilter($filterAppleType){

// Count the total number of rows based on the filter
$count_query = "SELECT COUNT(*) AS total_count FROM tbl_apple_inventory WHERE LEFT(property_tag_name, LENGTH('$filterAppleType')) = '$filterAppleType' || property_tag LIKE '%$filterAppleType%' || LEFT(services, LENGTH('$filterAppleType')) = '$filterAppleType' || LEFT(remarks, LENGTH('$filterAppleType')) = '$filterAppleType' || LEFT(service_unserviceable, LENGTH('$filterAppleType')) = '$filterAppleType'";
$count_result = $this->conn->query($count_query);
$row = $count_result->fetch_assoc();
$total_count = $row['total_count'];

// Retrieve filtered records along with years of service
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_apple_inventory WHERE LEFT(property_tag_name, LENGTH('$filterAppleType')) = '$filterAppleType' || property_tag LIKE '%$filterAppleType%' || LEFT(services, LENGTH('$filterAppleType')) = '$filterAppleType' || LEFT(remarks, LENGTH('$filterAppleType')) = '$filterAppleType' || LEFT(service_unserviceable, LENGTH('$filterAppleType')) = '$filterAppleType' ORDER BY years_of_service DESC";

$get = $this->conn->query($sql);
$data = "";

$data .="
<div class='table-responsive'>
<table class='table table-hover'>
<thead>
<tr class='text-center'>
<th>Services</th>
<th>Tag Name</th>
<th>Description</th>
<th>Property Tag</th>
<th>Date Acquired</th>
<th>Years services</th>
<th>Actual user</th>
<th>Remarks</th>
<th>Specific</th>
<th>Status</th>
<th class='lastChild'>Actions</th>
</tr>
</thead>
<tbody>";

if ($get->num_rows > 0) {
while ($row_filter = $get->fetch_assoc()) {
$origDate = $row_filter["date_acquired"];
$dateTime = new DateTime($origDate);
$formatDate = $dateTime->format("M d, Y");

$data .= "
<tr class='text-center'>
<td>".$row_filter["services"]."</td>
<td width='1%'>".$row_filter["property_tag_name"]."</td>
<td width='20%'>".$row_filter["description"]."</td>
<td width='20%'>".$row_filter["property_tag"]."</td>
<td width='14%'>".$formatDate."</td>
<td width='1%'>".$row_filter["years_of_service"]."</td>
<td width='1%'>".$row_filter["actual_user"]."</td>
<td>".$row_filter["remarks"]."</td>
<td>".$row_filter["specify"]."</td>
<td>".$row_filter["service_unserviceable"]."</td>
<td width='10%' class='lastChild'>

<a href='#' type='button' class='btn btn-sm btn-outline-success editAppleId' data-bs-toggle='modal' data-bs-target='#modalUpdate' id='".$row_filter['id']."'><i class='fa fa-underline'></i></a> <a href='#' type='button' class='btn btn-sm btn-outline-danger delAppleId' data-bs-toggle='modal' data-bs-target='#modalDelete' id='".$row_filter['id']."'><i class='fa fa-eraser'></i></a>


</td>
</tr>";
}

$data .="

<tr>
<th class='border border-2' colspan='3'>Total count:</th>
<td class='border border-2 fw-bolder'>".$total_count."</td>
</tr>

";

} else {
$data .= "
<tr>
<td colspan='12' class='text-center fw-bolder'>
<h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
</td>
</tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
	}
}

// trigger the filter apple categories
if (isset($_POST["filterAppleType"])) {
	$filter = $_POST["filterAppleType"];
	include("config.php");

	$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$liveSearch = new LiveFilterAppleType($conn);
$liveSearch->performFilter($filter);
$dbConnect->closeConnection();
}

// scanner summary report
class ScannerSummaryReport{

private $conn;

public function __construct($conn){
	$this->conn = $conn;
}

public function getServicesTable(){
$sql = "SELECT DISTINCT services FROM tbl_scanner_inventory ORDER BY services ASC";
$service_record = $this->conn->query($sql);
return $service_record;
}

public function getYearsService(){

$sql = "SELECT DISTINCT TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_scanner_inventory WHERE date_acquired IS NOT NULL ORDER BY years_of_service DESC";
$recordsYears = $this->conn->query($sql);
return $recordsYears;
}

// itemcount per service base on the years of service if null the value must be 0
public function getItemCountByServiceAndYear($service, $years_of_service){
$sql = "SELECT COUNT(*) AS item_count FROM tbl_scanner_inventory WHERE services = '$service' AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) = '$years_of_service'";
$result = $this->conn->query($sql);
$row = $result->fetch_assoc();
return $row["item_count"];
 }
}

// create pdf generation function
function generateScannerReport($conn){
// instantiate TCPDF object
$pdf = new TCPDF("L", "mm", [215.9, 330.2], true, "UTF-8", false); // Landscape orientation, folio size, Unicode support

// Set maximum margins
$pdf->SetMargins(10, 10, 10, true); // Set left, top, right, and auto-adjust bottom margin to true

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// remove default header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set font
$pdf->SetFont("helvetica", "", 14);

// Set title
$pdf->SetTitle("Scanner Inventory Summary");

// Add a page
$pdf->AddPage();

// Set position for text (adjust as needed)
$pdf->SetXY(0, 10);

$pdf->Cell(0, 10, "Scanner Summary Report", 0, 5, "C");

$content = "";
// PDF content generation code here 

$content .="<div>
<table id='pdf-laptop-table'>
<thead>
<tr>
<th id='table-header'>Office</th>";

// Fetch years of service
$recordYears = new ScannerSummaryReport($conn);
$yr_records = $recordYears->getYearsService();

// Add years of service to table header
foreach ($yr_records as $year) {
$content .= "<th>" . $year["years_of_service"] . " Yrs</th>";
}

$content .= "</tr>
</thead>
<tbody>";

// Fetch service records
$serviceRecords = new ScannerSummaryReport($conn);
$records_service = $serviceRecords->getServicesTable();

// Populate table rows with service records
while ($row_service = $records_service->fetch_assoc()) {
$content .= "<tr>
<td class='services'>" . $row_service["services"] . "</td>";

foreach ($yr_records as $year) {
    $itemCount = $serviceRecords->getItemCountByServiceAndYear($row_service["services"], $year["years_of_service"]);
    if($itemCount == 0){
		$content .= "<td></td>";
	}else{
		$content .= "<td>" . $itemCount . "</td>";
	}
}

$content .= "</tr>";
}

$content .= "
</tbody>
</table>
</div>

<style>
#pdf-laptop-table{
	border-collapase: collapse;
}

#pdf-laptop-table th:first-child, th, td{
	border: 1px solid #7E7D7C;
	text-align: center;
}

#pdf-laptop-table .services{
	width: auto !important;
	max-width: 12% !important;
}
</style>
";


// Output PDF
// $pdf->writeHTML($content);
$pdf->writeHTML($content, false, false, false, false, '');
// $pdf->writeHTMLCell(300, 0, "", "", $content, 1);
$pdf->Output('scanner_summary_report.pdf', 'I');
}


// Check if PDF generation requested
if(isset($_GET["generate_scanner_pdf"])){
include("../tcpdf/tcpdf.php");
$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();
// Call PDF generation function
generateScannerReport($conn);
exit(); // Stop further execution
}

// ====================

// scanner aging manager
class ScannerAgingManager{
	private $conn;

	public function __construct($conn){
	 $this->conn = $conn;
 }

 public function getInventoryAging(){
$sql = "SELECT COUNT(*) AS pc_aging FROM tbl_scanner_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5";
$stmt = $this->conn->query($sql);
$row_aging = $stmt->fetch_assoc();
$total_aging = $row_aging["pc_aging"];
return $total_aging;
}

public function getAgingRecords($page = 1, $perPage = 5){
$offset = ($page - 1) * $perPage;
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS pc_aging FROM tbl_scanner_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5 ORDER BY services ASC LIMIT $perPage OFFSET $offset";
$records = $this->conn->query($sql);
return $records;
}

}

// ====================================


// live search filter scanner by date acquired
class LiveScannerFilter{
	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}

	// perform apple filter by date acquired
	public function performScannerBydate($filterScanner){
		// Count the total number of rows based on the filter
$count_query = "SELECT COUNT(*) AS total_count FROM tbl_scanner_inventory WHERE date_acquired = '$filterScanner'";
$count_result = $this->conn->query($count_query);
$row = $count_result->fetch_assoc();
$total_count = $row['total_count'];

// Retrieve filtered records along with years of service
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_scanner_inventory WHERE date_acquired = '$filterScanner'";
$count_year = $this->conn->query($sql);
$row_yr = $count_year->fetch_assoc();
$year_count = $row_yr["years_of_service"];

$get = $this->conn->query($sql);
$data = "";

$data .="
<div class='table-responsive'>
<table class='table table-hover'>
<thead>
<tr class='text-center'>
<th>Services</th>
<th>Tag Name</th>
<th>Description</th>
<th>Property Tag</th>
<th>Date Acquired</th>
<th>Years services</th>
<th>Actual user</th>
<th>Remarks</th>
<th>Specific</th>
<th>Status</th>
<th class='lastChild'>Actions</th>
</tr>
</thead>
<tbody>";

if ($get->num_rows > 0) {
while ($row_filter = $get->fetch_assoc()) {
$origDate = $row_filter["date_acquired"];
$dateTime = new DateTime($origDate);
$formatDate = $dateTime->format("M d, Y");

$data .= "
<tr class='text-center'>
<td>".$row_filter["services"]."</td>
<td width='1%'>".$row_filter["property_tag_name"]."</td>
<td width='20%'>".$row_filter["description"]."</td>
<td width='20%'>".$row_filter["property_tag"]."</td>
<td width='14%'>".$formatDate."</td>
<td width='1%'>".$row_filter["years_of_service"]."</td>
<td width='1%'>".$row_filter["actual_user"]."</td>
<td>".$row_filter["remarks"]."</td>
<td>".$row_filter["specify"]."</td>
<td>".$row_filter["service_unserviceable"]."</td>
<td width='10%' class='lastChild'>

<a href='#' type='button' class='btn btn-sm btn-outline-success editScannereId' data-bs-toggle='modal' data-bs-target='#modalUpdate' id='".$row_filter['id']."'><i class='fa fa-underline'></i></a> <a href='#' type='button' class='btn btn-sm btn-outline-danger delScannereId' data-bs-toggle='modal' data-bs-target='#modalDelete' id='".$row_filter['id']."'><i class='fa fa-eraser'></i></a>

</td>
</tr>";
}

$data .="

<tr>
<th class='border border-2' colspan='4'><span class='text-primary fs-5'>".$year_count."</span> year/s of service total count:</th>
<td class='border border-2 fw-bolder text-primary fs-5'>".$total_count."</td>
</tr>

";

} else {
$data .= "
<tr>
<td colspan='12' class='text-center fw-bolder'>
<h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
</td>
</tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
	}
}

// trigger the live filter
if (isset($_POST["filterScanner"])) {
$filter = $_POST["filterScanner"];
include("config.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$liveFilter = new LiveScannerFilter($conn);
$liveFilter->performScannerBydate($filter);
$dbConnect->closeConnection();
}

// ================================

// scanner filter by years of service
class FilterScannerYrs{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function performFilter($filterScannerYrService){

$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_scanner_inventory WHERE TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) = '$filterScannerYrService'";
$count_year = $this->conn->query($sql);
$row_yr = $count_year->fetch_assoc();
$year_count = $row_yr["years_of_service"];
$row_count = $count_year->num_rows;

$get = $this->conn->query($sql);
$data = "";

$data .="
<div class='table-responsive'>
<table class='table table-hover'>
<thead>
<tr class='text-center'>
<th>Services</th>
<th>Tag Name</th>
<th>Description</th>
<th>Property Tag</th>
<th>Date Acquired</th>
<th>Years services</th>
<th>Actual user</th>
<th>Remarks</th>
<th>Specific</th>
<th>Status</th>
<th class='lastChild'>Actions</th>
</tr>
</thead>
<tbody>";

if ($get->num_rows > 0) {
while ($row_filter = $get->fetch_assoc()) {
$origDate = $row_filter["date_acquired"];
$dateTime = new DateTime($origDate);
$formatDate = $dateTime->format("M d, Y");

$data .= "
<tr class='text-center'> 
<td>".$row_filter["services"]."</td>
<td width='1%'>".$row_filter["property_tag_name"]."</td>
<td width='20%'>".$row_filter["description"]."</td>
<td width='20%'>".$row_filter["property_tag"]."</td>
<td width='14%'>".$formatDate."</td>
<td width='1%'>".$row_filter["years_of_service"]."</td>
<td width='1%'>".$row_filter["actual_user"]."</td>
<td>".$row_filter["remarks"]."</td>
<td>".$row_filter["specify"]."</td>
<td>".$row_filter["service_unserviceable"]."</td>
<td width='10%' class='lastChild'>

<a href='#' type='button' class='btn btn-sm btn-outline-success editScannereId' data-bs-toggle='modal' data-bs-target='#modalUpdate' id='".$row_filter['id']."'><i class='fa fa-underline'></i></a> <a href='#' type='button' class='btn btn-sm btn-outline-danger delScannereId' data-bs-toggle='modal' data-bs-target='#modalDelete' id='".$row_filter['id']."'><i class='fa fa-eraser'></i></a>

</td>
</tr>";
}

$data .="

<tr>
<th class='border border-2' colspan='4'><span class='text-primary fs-5'>".$year_count."</span> year/s of service total count:</th>
<td class='border border-2 fw-bolder text-primary fs-5'>".$row_count."</td>
</tr>

";

} else {
$data .= "
<tr>
<td colspan='12' class='text-center fw-bolder'>
<h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
</td>
</tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
 }
}


// trigger the filter
if (isset($_POST["filterScannerYrService"])) {
$filter = $_POST["filterScannerYrService"];
include("config.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$liveFilterYrs = new FilterScannerYrs($conn);
$liveFilterYrs->performFilter($filter);
$dbConnect->closeConnection();
}

// ++++++++++++++++++++++++
// ==================================

// filter scanner by categories
class FilterScannerCategory{

private $conn;

public function __construct($conn){
$this->conn = $conn;
}

public function performFilter($filterScannerType){

$sql_count = "SELECT COUNT(*) AS total_count FROM tbl_scanner_inventory WHERE LEFT(property_tag_name, LENGTH('$filterScannerType')) = '$filterScannerType' || property_tag LIKE '%$filterScannerType%' || LEFT(services, LENGTH('$filterScannerType')) = '$filterScannerType' || LEFT(remarks, LENGTH('$filterScannerType')) = '$filterScannerType' || LEFT(service_unserviceable, LENGTH('$filterScannerType')) = '$filterScannerType'";
$count_result = $this->conn->query($sql_count);
$row = $count_result->fetch_assoc();
$total_count = $row['total_count'];

// Retrieve filtered records along with years of service
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_scanner_inventory WHERE LEFT(property_tag_name, LENGTH('$filterScannerType')) = '$filterScannerType' || property_tag LIKE '%$filterScannerType%' || LEFT(services, LENGTH('$filterScannerType')) = '$filterScannerType' || LEFT(remarks, LENGTH('$filterScannerType')) = '$filterScannerType' || LEFT(service_unserviceable, LENGTH('$filterScannerType')) = '$filterScannerType' ORDER BY years_of_service DESC";
$count_year = $this->conn->query($sql);
$row_yr = $count_year->fetch_assoc();
// $year_count = $row_yr["years_of_service"];
$row_count = $count_year->num_rows;

$get = $this->conn->query($sql);
$data = "";

$data .="
<div class='table-responsive'>
<table class='table table-hover'>
<thead>
<tr class='text-center'>
<th>Services</th>
<th>Tag Name</th>
<th>Description</th>
<th>Property Tag</th>
<th>Date Acquired</th>
<th>Years services</th>
<th>Actual user</th>
<th>Remarks</th>
<th>Specific</th>
<th>Status</th>
<th class='lastChild'>Actions</th>
</tr>
</thead>
<tbody>";

if ($get->num_rows > 0) {
while ($row_filter = $get->fetch_assoc()) {
$origDate = $row_filter["date_acquired"];
$dateTime = new DateTime($origDate);
$formatDate = $dateTime->format("M d, Y");

$data .= "
<tr class='text-center'> 
<td>".$row_filter["services"]."</td>
<td width='1%'>".$row_filter["property_tag_name"]."</td>
<td width='20%'>".$row_filter["description"]."</td>
<td width='20%'>".$row_filter["property_tag"]."</td>
<td width='14%'>".$formatDate."</td>
<td width='1%'>".$row_filter["years_of_service"]."</td>
<td width='1%'>".$row_filter["actual_user"]."</td>
<td>".$row_filter["remarks"]."</td>
<td>".$row_filter["specify"]."</td>
<td>".$row_filter["service_unserviceable"]."</td>
<td width='10%' class='lastChild'>

<a href='#' type='button' class='btn btn-sm btn-outline-success editScannereId' data-bs-toggle='modal' data-bs-target='#modalUpdate' id='".$row_filter['id']."'><i class='fa fa-underline'></i></a> <a href='#' type='button' class='btn btn-sm btn-outline-danger delScannereId' data-bs-toggle='modal' data-bs-target='#modalDelete' id='".$row_filter['id']."'><i class='fa fa-eraser'></i></a>

</td>
</tr>";
 }
 $data .="

<tr>
<th class='border border-2 fw-bolder' colspan='2'>Total count:</th>
<td class='border border-2 text-success fw-bolder'>".$total_count."</td>
</tr>

";
} else {
$data .= "
<tr>
<td colspan='12' class='text-center fw-bolder'>
<h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
</td>
</tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
 }
}

if (isset($_POST["filterScannerType"])) {
	$filter = $_POST["filterScannerType"];
	include("config.php");

	$dbConnect = new Connection($server, $user, $pass, $db_name);
	$conn = $dbConnect->connectDb();

	$liveSearch = new FilterScannerCategory($conn);
	$liveSearch->performFilter($filter);
	$dbConnect->closeConnection();
}
// ========================

// filter age scanner
class ScannerAgeFilter{
	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}

public function performScannerFilter($scannerAgeFilter){
$count_sql = "SELECT COUNT(*) AS total_count FROM tbl_scanner_inventory WHERE services IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5 AND services = '$scannerAgeFilter'";
$count_res = $this->conn->query($count_sql);
$row_ctr = $count_res->fetch_assoc();
$total_count_service = $row_ctr["total_count"];

$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS pc_aging FROM tbl_scanner_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5 AND services = '$scannerAgeFilter'";
$get = $this->conn->query($sql);
$data = "";

$data .="
<div class='table-responsive'>
<table class='table table-hover'>
	<thead>
	<tr class='text-center'>
		<th>No.</th>
		<th>Services</th>
		<th>Tag Name</th>
		<th>Description</th>
		<th>Property Tag</th>
		<th>Date Acquired</th>
		<th>Years services</th>
		<th>Actual user</th>
		<th>Remarks</th>
		<th>Specific</th>
		<th>Status</th>
	</tr>
</thead>
<tbody>
";

if($get->num_rows > 0){
  $ctr = 1;
  while($row_agingFilter = $get->fetch_assoc()){
	$origDate = $row_agingFilter["date_acquired"];
	$dateTime = new DateTime($origDate);
	$formatDate = $dateTime->format("M d, Y");
	
	$data .="
	
	<tr class='text-center'>
	<td>".$ctr."</td>
	<td>".$row_agingFilter["services"]."</td>
	<td width='20%'>".$row_agingFilter["property_tag_name"]."</td>
	<td width='20%'>".$row_agingFilter["description"]."</td>
	<td width='10%'>".$row_agingFilter["property_tag"]."</td>
	<td width='14%'>".$formatDate."</td>
	<td width='1%'>".$row_agingFilter["pc_aging"]."</td>
	<td width='20%'>".$row_agingFilter["actual_user"]."</td>
	<td>".$row_agingFilter["remarks"]."</td>
	<td>".$row_agingFilter["specify"]."</td>
	<td>".$row_agingFilter["service_unserviceable"]."</td>
	</tr>
	
	";

	$ctr++;	
}	

$data .="
	<tr>
	<th class='border border-2' colspan='4'><span class='text-primary fs-5'>Total Aging Count Per Service:</th>
	<td class='border border-2 fw-bolder text-primary fs-5'>".$total_count_service."</td>
	</tr>";

}else{
$data .= "
 <tr>
  <td colspan='10' class='text-center fw-bolder'>
   <h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
  </td>
 </tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
	}
}

// trigger the filter scanner aging
if (isset($_POST["scannerAgeFilter"])) {
	$filter = $_POST["scannerAgeFilter"];

	include("config.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$scannerAgerFilter = new ScannerAgeFilter($conn);
$scannerAgerFilter->performScannerFilter($filter);
$dbConnect->closeConnection();
}



// class getting all aging to print
class SummaryRecordsAging {
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    // pc aging inventory
    public function getInventoryAging() {
        $sql = "SELECT COUNT(*) AS pc_aging FROM tbl_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5";
        $stmt = $this->conn->query($sql);

        if ($stmt && $stmt->num_rows > 0) {
            $row = $stmt->fetch_assoc();
            $total_aging = $row["pc_aging"];
            return $total_aging;
        } 
    }

    public function getAgingRecords() {
        $sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS pc_aging FROM tbl_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5 ORDER BY services ASC";
        $result = $this->conn->query($sql);
        
        $records = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $records[] = $row;
            }
        }
        return $records; // Return the records array
    }
    // ==================================

    // laptop aging inventory
				public function getLaptopInventoryAging() {
        $sql = "SELECT COUNT(*) AS pc_aging FROM tbl_laptop_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5";
        $stmt = $this->conn->query($sql);

        if ($stmt && $stmt->num_rows > 0) {
            $row = $stmt->fetch_assoc();
            $total_aging = $row["pc_aging"];
            return $total_aging;
        } 
    }

    public function getLaptopAgingRecords() {
        $sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS pc_aging FROM tbl_laptop_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5 ORDER BY services ASC";
        $result = $this->conn->query($sql);
        
        $records = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $records[] = $row;
            }
        }
        return $records; // Return the records array
    }

     // apple aging inventory
				public function getAppleInventoryAging() {
        $sql = "SELECT COUNT(*) AS pc_aging FROM tbl_apple_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5";
        $stmt = $this->conn->query($sql);

        if ($stmt && $stmt->num_rows > 0) {
            $row = $stmt->fetch_assoc();
            $total_aging = $row["pc_aging"];
            return $total_aging;
        } 
    }

    public function getAppleAgingRecords() {
        $sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS pc_aging FROM tbl_apple_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5 ORDER BY services ASC";
        $result = $this->conn->query($sql);
        
        $records = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $records[] = $row;
            }
        }
        return $records; // Return the records array
    }
    // ===================================

    public function getScannerInventoryAging() {
        $sql = "SELECT COUNT(*) AS pc_aging FROM tbl_scanner_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5";
        $stmt = $this->conn->query($sql);

        if ($stmt && $stmt->num_rows > 0) {
            $row = $stmt->fetch_assoc();
            $total_aging = $row["pc_aging"];
            return $total_aging;
        } 
    }

    public function getScannerAgingRecords() {
        $sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS pc_aging FROM tbl_scanner_inventory WHERE date_acquired IS NOT NULL AND TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) > 5 ORDER BY services ASC";
        $result = $this->conn->query($sql);
        
        $records = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $records[] = $row;
            }
        }
        return $records; // Return the records array
    }

}

class LiveAppleArchives{

	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}

	public function performFilter($filterAppleArchiveType){

		$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_apple_inventory_archive WHERE LEFT(property_tag_name, LENGTH('$filterAppleArchiveType')) = '$filterAppleArchiveType' || property_tag LIKE '%$filterAppleArchiveType%' || LEFT(services, LENGTH('$filterAppleArchiveType')) = '$filterAppleArchiveType' || LEFT(remarks, LENGTH('$filterAppleArchiveType')) = '$filterAppleArchiveType' || LEFT(service_unserviceable, LENGTH('$filterAppleArchiveType')) = '$filterAppleArchiveType' ORDER BY years_of_service DESC";

$get = $this->conn->query($sql);
$data = "";

$data .="

<div class='table-responsive'>
<table class='table table-hover'>
<thead>
<tr class='text-center'>
<th>Services</th>
<th>Tag Name</th>
<th>Description</th>
<th>Property Tag</th>
<th>Date Acquired</th>
<th>Years services</th>
<th>Actual user</th>
<th>Remarks</th>
<th>Specific</th>
<th>Status</th>
<th class='lastChild'>Actions</th>
</tr>
</thead>
<tbody>
";

if ($get->num_rows > 0) {
	while ($row_filter = $get->fetch_assoc()) {
		$origDate = $row_filter["date_acquired"];
		$dateTime = new DateTime($origDate);
		$formatDate = $dateTime->format("M d, Y");

		$data .="

		<tr class='text-center'>
    <td>".$row_filter["services"]."</td>
    <td width='1%'>".$row_filter["property_tag_name"]."</td>
    <td width='20%'>".$row_filter["description"]."</td>
    <td width='20%'>".$row_filter["property_tag"]."</td>
    <td width='14%'>".$formatDate."</td>
    <td width='1%'>".$row_filter["years_of_service"]."</td>
    <td width='1%'>".$row_filter["actual_user"]."</td>
    <td>".$row_filter["remarks"]."</td>
    <td>".$row_filter["specify"]."</td>
    <td>".$row_filter["service_unserviceable"]."</td>
    <td width='10%'>
        <a href='#' type='button' class='btn btn-outline-success restore-apple' data-bs-toggle='modal' data-bs-target='#modalPCRestore' id='".$row_filter["id"]."' ?>><i class='fa-solid fa-plus'></i></a>
    </td>
		";
	}
}else{
	$data .= "
<tr>
<td colspan='12' class='text-center fw-bolder'>
<h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
</td>
</tr>";	
}

$data .= "</tbody>
</table></div>";
echo $data;
	}
}

if (isset($_POST["filterAppleArchiveType"])) {
	$filter = $_POST["filterAppleArchiveType"];
	include("config.php");

	$dbConnect = new Connection($server, $user, $pass, $db_name);
	$conn = $dbConnect->connectDb();

$liveSearch = new LiveAppleArchives($conn);
$liveSearch->performFilter($filter);
$dbConnect->closeConnection();
}

// filter scanner type archive
class filterScannerArch{
	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}

	public function performFilter($filterScannerType){

$sql_count = "SELECT COUNT(*) AS total_count FROM tbl_scanner_inventory_archive WHERE LEFT(property_tag_name, LENGTH('$filterScannerType')) = '$filterScannerType' || property_tag LIKE '%$filterScannerType%' || LEFT(services, LENGTH('$filterScannerType')) = '$filterScannerType' || LEFT(remarks, LENGTH('$filterScannerType')) = '$filterScannerType' || LEFT(service_unserviceable, LENGTH('$filterScannerType')) = '$filterScannerType'";
$count_result = $this->conn->query($sql_count);
$row = $count_result->fetch_assoc();
$total_count = $row['total_count'];

// Retrieve filtered records along with years of service
$sql = "SELECT *, TIMESTAMPDIFF(YEAR, STR_TO_DATE(date_acquired, '%m/%d/%Y'), CURDATE()) AS years_of_service FROM tbl_scanner_inventory_archive WHERE LEFT(property_tag_name, LENGTH('$filterScannerType')) = '$filterScannerType' || property_tag LIKE '%$filterScannerType%' || LEFT(services, LENGTH('$filterScannerType')) = '$filterScannerType' || LEFT(remarks, LENGTH('$filterScannerType')) = '$filterScannerType' || LEFT(service_unserviceable, LENGTH('$filterScannerType')) = '$filterScannerType' ORDER BY years_of_service DESC";
$count_year = $this->conn->query($sql);
$row_yr = $count_year->fetch_assoc();
// $year_count = $row_yr["years_of_service"];
$row_count = $count_year->num_rows;

$get = $this->conn->query($sql);
$data = "";

$data .="
<div class='table-responsive'>
<table class='table table-hover'>
<thead>
<tr class='text-center'>
<th>Services</th>
<th>Tag Name</th>
<th>Description</th>
<th>Property Tag</th>
<th>Date Acquired</th>
<th>Years services</th>
<th>Actual user</th>
<th>Remarks</th>
<th>Specific</th>
<th>Status</th>
<th class='lastChild'>Actions</th>
</tr>
</thead>
<tbody>";

if ($get->num_rows > 0) {
while ($row_filter = $get->fetch_assoc()) {
$origDate = $row_filter["date_acquired"];
$dateTime = new DateTime($origDate);
$formatDate = $dateTime->format("M d, Y");

$data .= "
<tr class='text-center'> 
<td>".$row_filter["services"]."</td>
<td width='1%'>".$row_filter["property_tag_name"]."</td>
<td width='20%'>".$row_filter["description"]."</td>
<td width='20%'>".$row_filter["property_tag"]."</td>
<td width='14%'>".$formatDate."</td>
<td width='1%'>".$row_filter["years_of_service"]."</td>
<td width='1%'>".$row_filter["actual_user"]."</td>
<td>".$row_filter["remarks"]."</td>
<td>".$row_filter["specify"]."</td>
<td>".$row_filter["service_unserviceable"]."</td>
<td width='10%' class='lastChild'>

<a href='#' type='button' class='btn btn-sm btn-outline-success editScannereId' data-bs-toggle='modal' data-bs-target='#modalUpdate' id='".$row_filter['id']."'><i class='fa fa-underline'></i></a> <a href='#' type='button' class='btn btn-sm btn-outline-danger delScannereId' data-bs-toggle='modal' data-bs-target='#modalDelete' id='".$row_filter['id']."'><i class='fa fa-eraser'></i></a>

</td>
</tr>";
 }
 $data .="

<tr>
<th class='border border-2 fw-bolder' colspan='2'>Total count:</th>
<td class='border border-2 text-success fw-bolder'>".$total_count."</td>
</tr>

";
} else {
$data .= "
<tr>
<td colspan='12' class='text-center fw-bolder'>
<h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
</td>
</tr>";
}

$data .= "</tbody>
</table></div>";
echo $data;
 }
}

if (isset($_POST["filterArchScannerType"])) {
	$filter = $_POST["filterArchScannerType"];
	include("config.php");

	$dbConnect = new Connection($server, $user, $pass, $db_name);
	$conn = $dbConnect->connectDb();

	$liveSearch = new filterScannerArch($conn);
	$liveSearch->performFilter($filter);
	$dbConnect->closeConnection();
}
// ===========================


// filter m365 account
class FilterM365{
	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}

	public function performFilter($m365Filter){
		$sql = "SELECT * FROM tbl_m365_acc WHERE username LIKE '%$m365Filter%' || display_name LIKE '%$m365Filter%' || actual_user LIKE '%$m365Filter%' || status LIKE '%$m365Filter%' || account_name LIKE '%$m365Filter%'";
		$get = $this->conn->query($sql);
		$data = "";

		$data .="

<div class='table-responsive' id='showM365Data'>
<table class='table table-hover'>
	<thead>
	<tr class='text-center'>
		<th>No.</th>
		<th>Username</th>
		<th>Account Name</th>
		<th>Display Name</th>
		<th>Actual User</th>
		<th>Temporary Password</th>
		<th>Permanent Password</th>
		<th>Remarks</th>
		<th class='lastChild'>Status</th>
		<th class='lastChild'>Options</th>
	</tr>
</thead>
<tbody>
		";

if ($get->num_rows > 0) {
	$ctr = 1;
	while ($row = $get->fetch_assoc()) {
		$data .="

<tr class='text-center'>
<td>".$ctr."</td>
<td>".$row['username']."</td>
<td>".$row['account_name']."</td>
<td>".$row['display_name']."</td>
<td>".$row['actual_user']."</td>
<td>".$row['temporary_pass']."</td>
<td>".$row['permanent_pass']."</td>
<td>".$row['remarks']."</td>
<td class='lastChild'>".$row['status']."</td>
<td class='lastChild'>
  <a href='#' type='button' class='btn btn-sm btn-outline-success editM365Id' data-bs-toggle='modal' data-bs-target='#modalUpdate' id='".$row['id']."'><i class='fa fa-underline'></i></a> <a href='#' type='button' class='btn btn-sm btn-outline-danger delM365Id' data-bs-toggle='modal' data-bs-target='#modalDelete' id='".$row['id']."'><i class='fa fa-eraser'></i></a>
</td>
</tr>

		";
		$ctr++;
	}
}else{
	$data .="
	<tr>
  <td colspan='10' class='text-center fw-bolder'>
   <h3 class='text-danger fw-bolder animated fadeIn infinite'>No Record</h3>
  </td>
 </tr>
	";
}
$data .="
<tbody>
</table>
</div>
";

echo $data;
	}
}

// trigger m365 filter
if (isset($_POST["m365Filter"])) {
	$filter = $_POST["m365Filter"];
	include("config.php");

	$dbConnect = new Connection($server, $user, $pass, $db_name);
	$conn = $dbConnect->connectDb();

	$filterM365 = new FilterM365($conn);
	$filterM365->performFilter($filter);
	$dbConnect->closeConnection();
}

class M365RecordsPrint{
	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}

	public function getM365(){
		$sql = "SELECT * FROM tbl_m365_acc ORDER BY display_name, account_name ASC";
		$result = $this->conn->query($sql);

		$records = array();
		if ($result && $result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$records[] = $row;
			}
		}
		return $records;
	}
}


class M365Value{
	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}

	public function fetchdata($selected){
		$sql = "SELECT * FROM tbl_m365_acc WHERE username = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param("s", $selected);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			return $row;
		}
	}
}

class OverallM365{
	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}

	public function m375query(){
		$sql = "WITH occurrences AS (SELECT display_name, COUNT(*) AS count FROM tbl_m365_acc GROUP BY display_name), filtered AS (SELECT display_name, count FROM occurrences WHERE count >= 1 AND count <= 3) SELECT SUM(count) AS overall_total FROM filtered";
		$result = $this->conn->query($sql);
		$row = $result->fetch_assoc();
		return $row["overall_total"];
	}
}

?> 
