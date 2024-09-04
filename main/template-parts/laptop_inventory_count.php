<div class="container-fluid">
<div class="row">
	<h3 class="h1-info text-center text-light h1-title">Laptop Details</h3>
</div>

<div class="row">
		<div class="col-md-4 text-center"></div>
		<div class="col-md-4 text-center info">
		<h4 class="h2-title">Total Records</h4>
		<div class="p-md-3">
		<?php
			$totalRecordsCount = new TotalNumRowsCount($conn);
			$total = $totalRecordsCount->getLaptopTotalRecordsCount();
			echo "<h1 class='h1-info text-light'>".$total."</h1>";
		?>
		<small><a href="laptop_summary" class="btn btn-sm btn-dark" type="button">View Details</a></small>
		</div>
		</div>
		<div class="col-md-4 text-center"></div>	
	</div>

	<div class="row mt-3">

		<div class="col-md-4 text-center info">
			<h4 class="h2-title">Unserviceable</h4>
			<div class="p-md-3">
			<?php
				$unserviceRec = new TotalNumRowsCount($conn);
				$total_unserviceRec = $unserviceRec->getLaptopTotalUnserviceable();
				echo "<h1 class='h1-info text-light'>".$total_unserviceRec."</h1>";
			?>	
			</div>
		</div>

		<div class="col-md-4 text-center info">
			<h4 class="h2-title">Serviceable</h4>
			<div class="p-md-3">
			<?php
				$serviceRec = new TotalNumRowsCount($conn);
				$total_serviceRec = $serviceRec->getLaptopTotalServiceable();
				echo "<h1 class='h1-info text-light'>".$total_serviceRec."</h1>";
			?>
			</div>
		</div>

		<div class="col-md-4 text-center info">
			<h4 class="h2-title">Aged</h4>
			<div class="p-md-3">
			  <?php
			 	$aging = new TotalNumRowsCount($conn);
				$total_aging = $aging->getLaptopInventoryAging();
				echo "<h1 class='h1-info text-light'>".$total_aging."</h1>"; 
			  ?>
			</div>
		</div>

	</div>
</div>