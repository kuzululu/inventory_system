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

<section id="login-page" class="mt-5 pt-2">

<div class="container mb-3 mt-3">
<div class="row d-md-block d-none">
	<div class="col-md-12 text-end">
		<div>
			<small class="text-decoration-underline text-danger">Quick Access</small>
		</div>
		<a href="index" class="text-decoration-none text-info fw-bolder">Home</a>
	</div>
</div>

<div class="row">
<div class="col-md-4">
<a href="#" class="btn btn-sm btn-outline-primary mt-md-1" type="button" data-bs-toggle="modal" data-bs-target="#modalInsertServices">Insert Services</a>
</div>

<div class="col-md-8 d-md-flex">
<label class="fw-bolder me-2 mt-1">Filter:</label>
<!-- <input type="search" class="form-control resetSearch me-1 mb-md-0 mb-2" id="filterServices"> -->
<?php
	$serviceManager = new ServiceManager($conn);
	$services = $serviceManager->getService();
?>
<select id="filterServices" class="form-control resetSearch me-1 mb-md-0 mb-2">
	<option value=""></option>
	<?php foreach ($services as $service) { ?>
			<option value="<?= $service; ?>"><?= $service; ?></option>
			<?php 	} ?>
</select>
<a href="services" class="btn btn-outline-danger btn-sm" type="button">Reset</a>
</div>
</div>
</div>

<div class="container">
<div class="row">

<div class="col-md-2"></div>
<div class="col-md-8">
<div id="showDataServices">
<div  class="table-responsive">
<table class="table table-hover table-bordered">
<thead>
<tr class="text-center">
	<th>No.</th>
	<th>Services</th>
	<th>Actions</th>
</tr>
</thead>
<tbody>

<?php
	class ServiceView{
	
	private $serviceRec;

	public function __construct($serviceRec){
		$this->serviceRec = $serviceRec;
	}		

	public function displayService(){
	$ctr = 1;
	while ($row_services = $this->serviceRec->fetch_assoc()) {
?>

<tr class="text-center">
	<td><?= $ctr; ?></td>
	<td><?= $row_services["services_category"]; ?></td>
	<td>
		<a href="#" type="button" class="btn btn-sm btn-outline-success service_editId" data-bs-toggle="modal" data-bs-target="#modalUpdateServices" id="<?= $row_services['id_services']; ?>"><i class="fa fa-underline"></i></a> <a href="#" type="button" class="btn btn-sm btn-outline-danger service_delId" data-bs-toggle="modal" data-bs-target="#modalDeleteServices" id="<?= $row_services['id_services']; ?>"><i class="fa fa-eraser"></i></a>
	</td>
</tr>

<?php
 $ctr++;
 }

 }
}

// pagination
$page = isset($_GET["page"]) ? $_GET["page"] : 1;
$recordsServicePage = 3;

$serviceRecords = new ServiceRecords($conn);
$totalServiceRecords = $serviceRecords->getTotalServiceRecordCount();

$pageCount = ceil($totalServiceRecords / $recordsServicePage);

$services = $serviceRecords->getServiceRecords($page, $recordsServicePage);

$serviceView = new ServiceView($services);
$serviceView->displayService();
?>

</tbody>
</table>
</div>
<hr class="border border-primary border-1"> 

<nav aria-label="Page navigation example">
	<ul class="pagination justify-content-start">
		
		<li class="page-item <?php echo ($page <=1 ) ? 'disabled' : ''; ?>">
			<a class="page-link" href="?page<?= ($page - 1); ?>">Previous</a>
		</li>

<?php
$range = 2;
$start = max(1, $page - $range);
$end = min($pageCount, $page + $range);

if ($start > 1) { ?>

<li class="page-item">
	<a class="page-link" href="?page=1" class="page-link">1</a>
</li>

<li class="page-item disabled">
	<span class="page-linkg">..</span>
</li>

<?php
}

for ($i = $start; $i <= $end; $i++) { ?> 

<li class="page-item <?php echo ($i == $page) ? 'active' : '' ?>">
	<a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
</li>

<?php	
}

if ($end < $pageCount) { ?>
	
<li class="page-item disabled">
	<span class="page-ling">..</span>
</li>

<li class="page-item">
	<a class="page-link" href="?page=<?= $pageCount; ?>"><?= $pageCount; ?></a>
</li>

<?php } ?>

<li class="page-item <?php echo ($page >= $pageCount) ? 'disabled' : '' ; ?>">
	<a class="page-link" href="?page=<?= ($page + 1); ?>">Next</a>
</li>


	</ul>	
</nav>


</div>
</div>
<div class="col-md-2"></div>

</div>
</div>
</section>


<?php require_once "template-parts/bottom.php"; ?>
</body>
</html>

