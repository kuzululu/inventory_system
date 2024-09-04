<?php

include("../inc/config.php");
include("../inc/session.php");
include("../inc/class.php");


$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$account_type == "admin" ? /* true condition */ : header("location: ../logout");

?>

<?php require_once "template-parts/header.php"; ?>
<body>

<?php
require_once "template-parts/navbar.php";
?>

<section id="pc-summary" class="mt-5 pt-3">
<div class="container-fluid">
<?php require_once "template-parts/laptop_quick_access.php"; ?>
<div class="row">
<div class="col-md-12 text-center">
<h3 class="text-info fw-bolder animated pulse slow infinite">Laptop Summary Report</h3>
</div> <!-- end of row -->
</div>

<div class="row">
<div class="col-md-12">
<a target="_blank" href="?generate_laptop_pdf=true" class="btn btn-primary btn-sm position-fixed" id="printSummary"><i class="fa-solid fa-print"></i></a>
</div>
</div> <!-- end of row -->

<div class="row">
<div class="col-md-12">

<div class="table-responsive" id="showPcSummaryReport">
<table class="table table-hover table-bordered border border-2 border-primary">
<thead>
<tr>
<th class="text-center">Office</th>
<?php  
class ViewYrs{

private $recordsYears;

public function __construct($recordsYears){
$this->recordsYears = $recordsYears;
}

public function displayYrsRecords(){
while($row_years = $this->recordsYears->fetch_assoc()){
$year = $row_years["years_of_service"];
?>  

<th class="text-center"><?= $year; ?> Years</th>
<?php 
}   
} 
}


$recordYears = new LaptopSummaryReport($conn);
$yr_records = $recordYears->getYearsService();

$viewYears = new ViewYrs($yr_records);
$viewYears->displayYrsRecords();
?>
</tr>
</thead>
<tbody>
<?php
class ViewServiceRecords{
private $service_record;
private $conn;

public function __construct($service_record, $conn){
$this->service_record = $service_record;
$this->conn = $conn;
}

public function displayServiceRecords($services, $years){
while($row_service = $this->service_record->fetch_assoc()){
echo "<tr class='text-center'>";
echo "<td width='10%'>" . $row_service["services"] . "</td>";

foreach($years as $year){
    $itemCount = $this->conn->getItemCountByServiceAndYear($row_service["services"], $year["years_of_service"]);
    if($itemCount == 0){
        echo "<td></td>";
    }else{
        echo "<td>" . $itemCount . "</td>";
    }
    
}

echo "</tr>";
}
}
}

$serviceRecords = new LaptopSummaryReport($conn);
$records_service = $serviceRecords->getServicesTable();

$recordView = new ViewServiceRecords($records_service, $recordYears);
$recordView->displayServiceRecords($records_service, $yr_records);
?>
</tbody>
</table>

</div> <!-- end of table responsive -->

</div> <!-- end of column -->
</div> <!-- end of row -->

</div> <!-- end of container -->
</section>


<?php require_once "template-parts/bottom.php"; ?>
</body>
</html>
