<?php

include("../inc/config.php");
include("../inc/session.php");
include("../inc/class.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$account_type = "admin" ? /* true condition */ : header("location: ../logout");

?>


<?php require_once "template-parts/header.php"; ?>
<body>

<?php
require_once "template-parts/navbar.php";
require_once "modal/scannerModal.php";
?>

<section id="scanner-inventory" class="mt-5 pt-2">

<div class="container-fluid mb-3 mt-3">
<?php require_once "template-parts/scanner_quick_access.php"; ?>
<div class="row">
        <div class="col-md-12 text-center">
            <h3 class="animated pulse infinite text-info fw-bolder text-uppercase">Scanner Inventory</h3>
        </div>
    </div>

<div class="row">
<div class="col-md-2">
<a href="#" class="btn btn-sm btn-outline-primary mt-md-4" type="button" data-bs-toggle="modal" data-bs-target="#modalInsert">Insert Record</a>
</div>

<div class="col-md-1 text-md-end mt-md-0 mt-3">
	<label class="fw-bolder mt-md-4">Filter:</label>
</div>

<div class="col-md-3">
<?php

$dateService_scanner = new dateAcquiredService($conn);
$dateCat_scanner = $dateService_scanner->getScannerService();

?>
<!-- <input type="search" class="form-control resetSearch me-1 mb-md-0 mb-2" id="filter"> -->
<small class="text-primary fw-bold">By Date Acquired</small>
 <select id="filterScanner" class="form-control resetSearch me-1 mb-md-0 mb-2">
 	<option value=""></option>
 	<?php  foreach ($dateCat_scanner as $dateService_scanner) { ?>
 	<option value="<?= $dateService_scanner; ?>"><?= $dateService_scanner; ?></option>
 	<?php } ?>
 </select>
</div>

<div class="col-md-2">
		<?php
			$yrService_scanner = new YearsofService($conn);
			$yrsService_scanner = $yrService_scanner->yearofScannerService();
		?>
		<small class="text-primary fw-bold">By Years of Service</small>
		<select id="filterScannerYrService" class="form-control resetSearch me-1 mb-md-0 mb-2">
		<option value=""></option>
		<?php foreach ($yrsService_scanner as $yrServices_scanner) { ?>
		<option value="<?= $yrServices_scanner; ?>"><?= $yrServices_scanner; ?></option>
		<?php } ?>
		</select>
</div>

<div class="col-md-3">
		<small class="text-primary fw-bold">By Category</small>
		<input type="text" id="filterScannerType" class="form-control resetSearch">
</div>

<div class="col-md-1 text-md-center">
	<a href="scanner_inventory" type="button" class="btn btn-outline-danger btn-sm mt-md-4 mt-3 d-md-block d-none">Reset</a>
		<a href="scanner_inventory" type="button" class="btn btn-outline-danger btn-sm mt-md-4 mt-3 d-md-none">Reset</a>
</div>

</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<button class="btn btn-primary btn-sm position-fixed" id="print"><i class="fa-solid fa-print"></i></button>
		</div>
	</div>
</div>

<div class="container-fluid">
<div class="row">

<div class="col-md-12">
<div id="showdataScanner">
<div class="table-responsive">
<table class="table table-hover">
	<thead>
	<tr class="text-center">
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
		<th class="lastChild">Actions</th>
	</tr>
</thead>
<tbody>
<?php
class ViewRecords{

private $recordScanner;

public function __construct($recordScanner){
$this->recordScanner = $recordScanner;
}	

public function displayRecords(){
	$ctr = 1;
while ($row = $this->recordScanner->fetch_assoc()) {
	$origDate = $row["date_acquired"];
	$dateTime = new DateTime($origDate);
	$formatDate = $dateTime->format("M d, Y");
?>
<tr class="text-center">
<td><?= $ctr; ?></td>
<td><?= $row["services"]; ?></td>
<td width="1%"><?= $row["property_tag_name"]; ?></td>
<td width="20%"><?= $row["description"]; ?></td>
<td width="20%"><?= $row["property_tag"]; ?></td>
<td width="14%"><?= $formatDate; ?></td>
<td width="1%"><?= $row["years_of_service"]; ?></td>
<td width="1%"><?= $row["actual_user"]; ?></td>
<td><?= $row["remarks"]; ?></td>
<td><?= $row["specify"]; ?></td>
<td><?= $row["service_unserviceable"]; ?></td>
<td width="10%" class="lastChild">
<a href="#" type="button" class="btn btn-sm btn-outline-success editScannereId" data-bs-toggle="modal" data-bs-target="#modalUpdate" id="<?= $row['id']; ?>"><i class="fa fa-underline"></i></a> <a href="#" type="button" class="btn btn-sm btn-outline-danger delScannereId" data-bs-toggle="modal" data-bs-target="#modalDelete" id="<?= $row['id']; ?>"><i class="fa fa-eraser"></i></a>
</td>
</tr>
<?php
$ctr++;
  }
 }
}

// pagination
$page = isset($_GET["page"]) ? $_GET["page"] : 1; //get the current page
$recordsPerPage = 5; //number of records to display per page

$recordsManager = new RecordsManager($conn);
$totalRecords = $recordsManager->getScannerTotalRecordsCount(); //method to get the total number of records

$pageCount = ceil($totalRecords / $recordsPerPage); //calculate total number pages


$rec = $recordsManager->getScannerRecords($page, $recordsPerPage);

$recordView = new ViewRecords($rec);
$recordView->displayRecords();
?>
</tbody>
</table>
</div>
<hr class="border border-primary border-1">
<!-- pagination link -->
<nav aria-label="Page navigation example">
<ul class="pagination justify-content-end">

<li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
 <a class="page-link" href="?page=<?= ($page - 1); ?>" tabindex="-1">Previous</a>
</li>

<?php
// Define the range of pages to display
$range = 2; // Number of pages to show before and after the current page
$start = max(1, $page - $range);
$end = min($pageCount, $page + $range);

// Add link for first page
if ($start > 1) { ?>

 <li class="page-item">
     <a class="page-link" href="?page=1">1</a>
 </li>
 <li class="page-item disabled">
     <span class="page-link">..</span>
 </li>

 <?php
}

for ($i = $start; $i <= $end; $i++) { ?>
 <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
     <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
 </li>

 <?php
}

// Add link for last page
if ($end < $pageCount) { ?>
 <li class="page-item disabled">
     <span class="page-link">..</span>
 </li>
 <li class="page-item">
     <a class="page-link" href="?page=<?= $pageCount; ?>"><?= $pageCount; ?></a>
 </li>
 <?php } ?>

<li class="page-item <?php echo ($page >= $pageCount) ? 'disabled' : ''; ?>">
 <a class="page-link" href="?page=<?= ($page + 1); ?>">Next</a>
</li>

</ul>
</nav>


   </div>
  </div>
 </div>
</div>
</section>

<script type="text/javascript" src="js/print-scanner-inventory.js"></script>
<?php require_once "template-parts/bottom.php"; ?>
</body>
</html>

