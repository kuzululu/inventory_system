<?php

include("../../inc/config.php");
include("../../inc/class.php");


$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

// pc aging fetch records in class
$pcAgingManager = new SummaryRecordsAging($conn); //Instantiate the SummaryRecordsAging class

// call the method inside the class
$recordsPcAging = $pcAgingManager->getAgingRecords();
$totalPcAging = $pcAgingManager->getInventoryAging();

$dataPc = array(
	"records" => $recordsPcAging,
	"total_aging" => $totalPcAging
);

// Send JSON response
header('Content-Type: application/json');
echo json_encode($dataPc);
// =========================

?>