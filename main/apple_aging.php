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
require_once "modal/modal.php";
?>

<section id="pc-aged" class="mt-5 pt-2">

<div class="container-fluid">
<?php require_once "template-parts/apple_quick_access.php"; ?>
    <div class="row">
        <div class="col-md-12 text-center">
            <h3 class="animated pulse infinite text-info text-uppercase">Aging</h3>
        </div>
    </div>

    <div class="row mb-5">
      <div class="col-md-12">
        <button class="btn btn-primary btn-sm position-fixed" id="printAppleAging"><i class="fa-solid fa-print"></i></button>
      </div>
    </div>

    <div class="row">
        <div class="col-md-1 text-md-end mt-md-0 mt-3">
          <label class="fw-bolder mt-2">Services:</label>
        </div>

        <div class="col-md-4">
          <?php
            $serviceManager = new ServiceManager($conn);
            $services = $serviceManager->getService();
           ?>
            <div class="input-group">
              <select id="appleAgeFilter" class="form-control resetSearch">
                <option name="insertServices" value=""></option>
                <?php foreach ($services as $service) { ?>
                <option name="insertServices" value="<?= $service; ?>"><?= $service; ?></option>
                <?php 	} ?>
              </select>
              <span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-users"></i></span>
            </div>
        </div>

        <div class="col-md-3">
            <a href="apple_aging" class="btn btn-outline-danger btn-sm mt-md-1 mt-3">Reset</a>
        </div>

    </div>
</div>

<div class="container-fluid">
<div class="row">

<div class="col-md-12">

<div class="table-responsive" id="showAgeAppleData">
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
		<th>Status</th>
	</tr>
</thead>
<tbody>
<?php
class ViewAgingRecords{

private $records;

public function __construct($records){
$this->records = $records;
}	

public function displayRecords(){
	$ctr = 1;
while ($row = $this->records->fetch_assoc()) {
	$origDate = $row["date_acquired"];
	$dateTime = new DateTime($origDate);
	$formatDate = $dateTime->format("M d, Y");
?>
<tr class="text-center">
<td><?= $ctr; ?></td>
<td><?= $row["services"]; ?></td>
<td width="20%"><?= $row["property_tag_name"]; ?></td>
<td width="20%"><?= $row["description"]; ?></td>
<td width="10%"><?= $row["property_tag"]; ?></td>
<td width="14%"><?= $formatDate; ?></td>
<td width="1%"><?= $row["pc_aging"]; ?></td>
<td width="20%"><?= $row["actual_user"]; ?></td>
<td><?= $row["remarks"]; ?></td>
<td><?= $row["service_unserviceable"]; ?></td>
</tr>
<?php
$ctr++;
  }
 }
}

// pagination
$page = isset($_GET["page"]) ? $_GET["page"] : 1; //get the current page
$recordsPerPage = 5; //number of records to display per page

$recordsManager = new AppleAgingManager($conn);
$totalRecords = $recordsManager->getInventoryAging(); //method to get the total number of records

$pageCount = ceil($totalRecords / $recordsPerPage); //calculate total number pages


$rec = $recordsManager->getAgingRecords($page, $recordsPerPage);

$recordView = new ViewAgingRecords($rec);
$recordView->displayRecords();
?>
</tbody>
</table>

<div>
  <?php 
    $pcAge = new AppleAgingManager($conn);
    $viewNum = $pcAge->getInventoryAging();
?>
    <p class="text-uppercase">Total Pc Aged <i><span class="fs-4 text-success fw-bolder"><?= $viewNum; ?></span></i></p>
<?php
  ?>
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


<?php require_once "template-parts/bottom.php"; ?>
<script type="text/javascript" src="js/print-apple.js"></script>
</body>
</html>

