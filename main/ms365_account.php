<?php
include("../inc/config.php");
include("../inc/session.php");
include("../inc/class.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$account_type = "admin" ? /* true condition */ : header("location: ../logout");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" href="../img/logo/ca-logo.png">
  <title>Ca PC Inventory</title>

  <link rel="stylesheet" type="text/css" href="../css/m365Custom.css">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../css/animate.css">
  <link rel="stylesheet" type="text/css" href="../css/all.min.css">
  <link rel="stylesheet" type="text/css" href="../css/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css" href="../css/ui-lightness-jquery-ui/jquery-ui-1.8.16.custom.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <script type="text/javascript" src="../js/jQuery-3.7.1.min.js"></script>
  <script type="text/javascript" src="../js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="../js/print-schortcut-val.js"></script>
  <script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>
  <script type="text/javascript" src="../js/dtPicker.js"></script>
  <script type="text/javascript" src="../js/all.min.js"></script>
  <script type="text/javascript" src="../js/number.js"></script>
</head>

<body>

<?php
require_once "template-parts/navbar.php";
require_once "modal/modalM365.php";
?>

<section id="pc-aged" class="mt-5 pt-2">

<div class="container-fluid">
<?php require_once "template-parts/m365_quick_access.php"; ?>
    <div class="row mt-4">
        <div class="col-md-12 text-center">
            <h3 class="animated pulse infinite text-info text-uppercase">M365 License Account</h3>
        </div>
    </div>

    <div class="row mb-5">
    <div class="col-md-12">
      <button class="btn btn-primary btn-sm position-fixed" id="printM365"><i class="fa-solid fa-print"></i></button>
      <button class="btn btn-primary btn-sm position-fixed" id="printFilterM365"><i class="fa-solid fa-print"></i></button>
    </div>
  </div>

  <div class="row">
<div class="col-md-7">
<a href="#" class="btn btn-sm btn-outline-primary mt-md-4" type="button" data-bs-toggle="modal" data-bs-target="#modalInsert">Insert Record</a> <a href="#" class="btn btn-sm btn-outline-success mt-md-4" type="button" data-bs-toggle="modal" data-bs-target="#modalAddAccount">Add Account</a>
</div>

<div class="col-md-4">
    <small class="text-primary fw-bold">By Category</small>
    <div class="d-flex">
      <label class="fw-bolder mt-2 me-1">Filter:</label>
      <input type="text" id="filterM365" class="form-control resetSearch">
    </div>
</div>

<div class="col-md-1 text-md-center">
  <a href="ms365_account" type="button" class="btn btn-outline-danger btn-sm mt-md-4 mt-3 d-md-block d-none">Reset</a>
    <a href="ms365_account" type="button" class="btn btn-outline-danger btn-sm mt-md-4 mt-3 d-md-none">Reset</a>
</div>

</div>

</div>

<div class="container-fluid">
<div class="row">

<div class="col-md-12">

<div class="table-responsive" id="showM365Data">
<table class="table table-hover">
  <thead>
  <tr class="text-center">
    <th>No.</th>
    <th>Username</th>
    <th>Account Name</th>
    <th>Display Name</th>
    <th>Actual User</th>
    <th>Temporary Password</th>
    <th>Permanent Password</th>
    <th>Remarks</th>
    <th>Status</th>
    <th class="lastChild">Options</th>
  </tr>
</thead>
<tbody>
<?php
class RecordView{

private $recordM365;

public function __construct($recordM365){
$this->recordM365 = $recordM365;
} 

public function displayRecords(){
  $ctr = 1;
while ($row = $this->recordM365->fetch_assoc()) {
?>
<tr class="text-center">
<td><?= $ctr; ?></td>
<td><?= $row["username"]; ?></td>
<td><?= $row["account_name"]; ?></td>
<td><?= $row["display_name"]; ?></td>
<td><?= $row["actual_user"]; ?></td>
<td><?= $row["temporary_pass"]; ?></td>
<td><?= $row["permanent_pass"]; ?></td>
<td><?= $row["remarks"]; ?></td>
<td><?= $row["status"]; ?></td>
<td class="lastChild">
  <a href="#" type="button" class="btn btn-sm btn-outline-success editM365Id" data-bs-toggle="modal" data-bs-target="#modalUpdate" id="<?= $row['id']; ?>"><i class="fa fa-underline"></i></a> <a href="#" type="button" class="btn btn-sm btn-outline-danger delM365Id" data-bs-toggle="modal" data-bs-target="#modalDelete" id="<?= $row['id']; ?>"><i class="fa fa-eraser"></i></a>
</td>
</tr>
<?php
$ctr++;
  }
 }
}

$page = isset($_GET["page"]) ? $_GET["page"] : 1;
$recordsPerPage = 5;

$recordManager = new RecordsManager($conn);
$totalM365Records = $recordManager->countM365Records(); //count total records
$pageCount = ceil($totalM365Records / $recordsPerPage);

$records = $recordManager->getM365Records($page, $recordsPerPage); // Modify method to accept offset and limit
$recordView = new RecordView($records);
$recordView->displayRecords();
?>
</tbody>
</table>

<div>
  <?php
    $overallM365 = new OverallM365($conn);
    $overallTotal = $overallM365->m375query();
  ?>
  <h6>Total Account: <?= $overallTotal; ?></h6>
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

<script type="text/javascript" src="js/print-m365-inventory.js"></script>
<?php require_once "template-parts/bottom.php"; ?>
</body>
</html>
