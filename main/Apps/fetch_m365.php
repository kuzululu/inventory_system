<?php

include("../../inc/config.php");
include("../../inc/class.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$m365 = new M365RecordsPrint($conn);

$recordM365 = $m365->getM365();

$data = array(
	"records" => $recordM365	
);

header("Content-Type: application/json");
echo json_encode($data);

?>