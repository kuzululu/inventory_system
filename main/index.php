<?php

include("../inc/config.php");
include("../inc/session.php");
include("../inc/class.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$account_type == "admin" ? /* true condition */ : header("location: ../logout");

?>

<?php require_once "template-parts/header.php"; ?>
<body id="body">

<?php
require_once "template-parts/navbar.php";
?>

<section id="main-page" class="mt-5 mt-md-5">

<div class="container-fluid mt-md-3 pt-md-5 mt-3 pt-3">

	<div class="row">
		<div class="col-md-12 text-center">
		<h2 class="h2-info fw-bolder animated fadeIn infinite slow text-warning-emphasis text-uppercase">Information</h2>
		</div>
	</div>

	<div class="row mb-md-0 mb-3">
		<div class="col-md-4 text-center"></div>
		<div class="col-md-4 text-center info animated zoomIn slower border border-primary border-1">
		<h4 class="h2-title">Total Records</h4>
		<div class="p-md-3">
		<?php
			$totalRecordsCount = new TotalNumRowsCount($conn);
			$total = $totalRecordsCount->getAllTotalRecordsCount();
			echo "<h1 class='h1-info text-light'>".$total."</h1>";
		?>
		</div>
		</div>
		<div class="col-md-4 text-center"></div>
	</div>

	<div class="row">
	  
	<div class="col-md-6 p-0 border animated slideInLeft border-primary border-1">
		<?php require_once "template-parts/pc_inventory_count.php"; ?>
	</div>

	<div class="col-md-6 p-0 border border-primary animated slideInRight border-1 mt-md-0 mt-3">
		<?php require_once "template-parts/laptop_inventory_count.php"; ?>
	</div>

	</div> <!-- end of row -->

	<div class="row mt-3">
		<div class="col-md-6 p-0 border animated slideInRight border-primary border-1">
			<?php require_once "template-parts/apple_inventory_count.php"; ?>
		</div>

		<div class="col-md-6 p-0 border border-primary animated slideInLeft border-1 mt-md-0 mt-3">
			<?php require_once "template-parts/scanner_inventory_count.php"; ?>
		</div>		
	</div>

</div> <!-- end of container-->

</section>


<?php require_once "template-parts/bottom.php"; ?>
</body>
</html>

