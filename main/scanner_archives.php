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

<section class="mt-5 pt-2" id="pc-archives">
<div class="container-fluid mb-3">
<?php require_once "template-parts/scanner_quick_access.php"; ?>
<div class="row">
    <div class="col-md-12 text-center">
        <h3 class="text-info text-uppercase animated pulse infinite slow">Archives</h3>
    </div>
</div>

<div class="row">
<div class="col-md-3">
<small class="text-primary fw-bold">By Category</small>
<input type="text" id="filterScannerTypeArchive" class="form-control resetSearch">
</div>

<div class="col-md-1">
<a href="pc_archives" type="button" class="btn btn-outline-danger mt-md-4 mt-2">Reset</a>
</div>

</div> <!-- end of row -->
</div> <!-- end of container -->
<hr> 

<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<div class="table-responsive" id="showScannerArchivesTable">

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
    <th>Actions</th>
</tr>
</thead>
<tbody>

<?php
 class ViewArchivesScanner{
 
 private $records_archive;

 public function __construct($records_archive){
    $this->records_archive = $records_archive;
 }

 public function displayArchivePC(){
 $ctr = 1;
 while($row_archive = $this->records_archive->fetch_assoc()){
    $origDate = $row_archive["date_acquired"];
	$dateTime = new DateTime($origDate);
	$formatDate = $dateTime->format("M d, Y");
?>

<tr class="text-center">
    <td><?= $ctr; ?></td>
    <td><?= $row_archive["services"]; ?></td>
    <td width="1%"><?= $row_archive["property_tag_name"]; ?></td>
    <td width="20%"><?= $row_archive["description"]; ?></td>
    <td width="20%"><?= $row_archive["property_tag"]; ?></td>
    <td width="14%"><?= $formatDate; ?></td>
    <td width="1%"><?= $row_archive["years_of_service"]; ?></td>
    <td width="1%"><?= $row_archive["actual_user"]; ?></td>
    <td><?= $row_archive["remarks"]; ?></td>
    <td><?= $row_archive["service_unserviceable"]; ?></td>
    <td width="10%">
        <a href="#" type="button" class="btn btn-outline-success restore-scanner-arch" data-bs-toggle="modal" data-bs-target="#modalPCRestore" id="<?= $row_archive["id"]; ?>"><i class="fa-solid fa-plus"></i></a>
    </td>
</tr>

<?php
   $ctr++;
  }
 }
}

// pagination
$page = isset($_GET["page"]) ? $_GET["page"] : 1;
$recordsPerPage = 5;

$recordsArchiveManager = new RecordsPcArchives($conn);
$totalPCRecords = $recordsArchiveManager->getScannerTotalRecordsCount();

$pageCount = ceil($totalPCRecords / $recordsPerPage);

$rec = $recordsArchiveManager->getScannerArchivesRecords($page, $recordsPerPage);

$recordArchiveView = new ViewArchivesScanner($rec);
$recordArchiveView->displayArchivePC();
?>
</tbody>
</table>

<hr>

<!-- pagination -->
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
<!--  -->
</div>
</div> <!-- end of column -->
</div> <!-- end of row -->
</div> <!-- end of container --> 
</section>

<?php require_once "template-parts/bottom.php"; ?>
</body>
</html>

