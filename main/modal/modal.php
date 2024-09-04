<!-- pc inventory page -->

<!-- data-bs-backdrop="static" use to disable modal outside click
data-bs-keyboard="false" use to disable modal esc key -->

<div class="modal fade" id="modalInsert" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	
<div class="modal-dialog modal-dialog-scrollable modal-lg">
	
<div class="modal-content">
	
<div class="modal-header">
	<h4 class="text-primary fw-bolder animated fadeIn slow infinite">Entry</h4>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div><!--  end header -->

<div class="modal-body">
	
<form class="row needs-validation" novalidate="" method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	
<div class="col-md-3 mb-3">
<?php
	$serviceManager = new ServiceManager($conn);
	$services = $serviceManager->getService();
?>
	<label>Services</label>
	<div class="input-group">
		<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-users"></i></span>
		<select name="insertServices" class="form-control" required="">
			<option name="insertServices" value=""></option>
			<?php foreach ($services as $service) { ?>
			<option name="insertServices" value="<?= $service; ?>"><?= $service; ?></option>
			<?php 	} ?>
		</select>
	</div>
</div>

<div class="col-md-5 mb-3">
<label>Tag Name</label>
	<div class="input-group">
		<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-user"></i></span>
		<input type="text" id="insertTagName" name="insertTagName" class="form-control" required="">
	</div>
</div>

<div class="col-md-4 mb-3">
<label>Property Tag</label>
	<div class="input-group">
		<span class="input-group-text bg-info bg-gradient"><i class="text-light fa-solid fa-tag"></i></span>
		<input type="text" name="insertProperty" class="form-control" required="">
	</div>
</div>

<div class="col-md-12 mb-3">
<label>Description</label>
	<div class="input-group">
		<span class="input-group-text bg-info bg-gradient"><i class="text-light fa-regular fa-comment"></i></span>
		<input type="text" name="insertDesc" class="form-control" required="">
	</div>
</div>

<div class="col-md-6 mb-3">
<label>Actual User</label>
	<div class="input-group">
		<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-user"></i></span>
		<input type="text" id="insertActualUser" name="insertActualUser" class="form-control" required="">
	</div>
</div>


<div class="col-md-6 mb-3">
<label>Date Acquired</label>
	<div class="input-group">
		<span class="input-group-text bg-info bg-gradient"><i class="fa fa-calendar text-light"></i></span>
		<input type="text" name="insertDateAcquired" class="datePicker form-control" required="">
	</div>
</div>

<div class="col-md-6 mb-3">
<label>Remarks</label>
	<div class="input-group">
		<span class="input-group-text bg-info bg-gradient"><i class="fa-solid fa-marker text-light"></i></span>
		<select id="insertOthers" name="insertRemarks" class="form-control">
			<option name="insertRemarks" value=""></option>
			<option name="insertRemarks" value="Reverted to GS">Reverted to GS</option>
			<option name="insertRemarks" value="Reverting to GS">Reverting to GS</option>
			<option name="insertRemarks" value="Others">Others</option>
		</select>
	</div>
</div>

<div class="col-md-6 mb-3">
	<label>Status</label>
	<div class="input-group">
		<span class="input-group-text bg-info bg-gradient"><i class="fa-solid fa-chart-simple text-light"></i></span>
		<select name="insertStatus" class="form-control" required="">
			<option name="insertStatus" value=""></option>
			<option name="insertStatus" value="Serviceable">Serviceable</option>
			<option name="insertStatus" value="Unserviceable">Unserviceable</option>
		</select>
	</div>
</div>

<div class="col-md-12 mb-3 others">
	<label>Specify:</label>
	<div class="input-group">
		<span class="input-group-text bg-info bg-gradient"><i class="fa-regular fa-note-sticky text-light"></i></span>
		<input type="text" id="insertSpecify" name="insertSpecify" class="form-control">
	</div>
</div>

<div class="col-md-12 mb-3 text-end">
	<input type="submit" class="btn btn-outline-primary btn-sm" name="btnInsert" value="Add">
</div>

</form>

</div> <!-- end of body -->

</div> <!-- end of content -->

</div> <!-- end dialog -->

</div>

<div class="modal fade" id="modalUpdate" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	
<div class="modal-dialog modal-dialog-scrollable modal-lg">
	
<div class="modal-content">
	
<div class="modal-header">
	<h4 class="text-success fw-bolder animated fadeIn slow infinite">Update</h4>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div><!--  end header -->

<div class="modal-body">

<form class="row needs-validation" novalidate="" method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

<input type="hidden" id="updateId" name="updateId">
	

<div class="col-md-3 mb-3">
<?php
	$serviceManager = new ServiceManager($conn);
	$services = $serviceManager->getService();
?>
	<label>Services</label>
	<div class="input-group">
		<span class="input-group-text bg-success bg-gradient"><i class="text-light fa fa-users"></i></span>
		<select name="updateServices" id="updateServices" class="form-control" required="">
			<option name="updateServices" value="" id="updateServices"></option>
			<?php foreach ($services as $service) { ?>
			<option name="updateServices" value="<?= $service; ?>"><?= $service; ?></option>
			<?php 	} ?>
		</select>
	</div>
</div>

<div class="col-md-5 mb-3">
<label>Tag Name</label>
	<div class="input-group">
		<span class="input-group-text bg-success bg-gradient"><i class="text-light fa fa-user"></i></span>
		<input type="text" name="updateTagName" id="updateTagName" class="form-control" required="">
	</div>
</div>

<div class="col-md-4 mb-3">
<label>Property Tag</label>
	<div class="input-group">
		<span class="input-group-text bg-success bg-gradient"><i class="text-light fa-solid fa-tag"></i></span>
		<input type="text" id="updateProperty" name="updateProperty" class="form-control" required="">
	</div>
</div>

<div class="col-md-12 mb-3">
<label>Description</label>
	<div class="input-group">
		<span class="input-group-text bg-success bg-gradient"><i class="text-light fa-regular fa-comment"></i></span>
		<input type="text" id="updateDesc" name="updateDesc" class="form-control" required="">
	</div>
</div>

<div class="col-md-6 mb-3">
<label>Actual User</label>
	<div class="input-group">
		<span class="input-group-text bg-success bg-gradient"><i class="text-light fa fa-user"></i></span>
		<input type="text" name="updateActualUser" id="updateActualUser" class="form-control" required="">
	</div>
</div>


<div class="col-md-6 mb-3">
<label>Date Acquired</label>
	<div class="input-group">
		<span class="input-group-text bg-success bg-gradient"><i class="fa fa-calendar text-light"></i></span>
		<input type="text" name="updateDate" id="updateDate" class="datePicker form-control" required="">
	</div>
</div>

<div class="col-md-6 mb-3">
<label>Remarks</label>
	<div class="input-group">
		<span class="input-group-text bg-success bg-gradient"><i class="fa-solid fa-marker text-light"></i></span>
		<select id="updateOthers" name="updateRemarks" class="form-control">
			<option name="updateRemarks" value=""></option>
			<option name="updateRemarks" value="Reverted to GS">Reverted to GS</option>
			<option name="updateRemarks" value="Reverting to GS">Reverting to GS</option>
			<option name="updateRemarks" value="Others">Others</option>
		</select>
	</div>
</div>

<div class="col-md-6 mb-3">
	<label>Status</label>
	<div class="input-group">
		<span class="input-group-text bg-success bg-gradient"><i class="fa-solid fa-chart-simple text-light"></i></span>
		<select name="updateStatus" id="updateStatus" class="form-control" required="">
			<option name="updateStatus" value=""></option>
			<option name="updateStatus" value="Serviceable">Serviceable</option>
			<option name="updateStatus" value="Unserviceable">Unserviceable</option>
		</select>
	</div>
</div>

<div class="col-md-12 mb-3 others">
	<label>Specify:</label>
	<div class="input-group">
		<span class="input-group-text bg-success bg-gradient"><i class="fa-regular fa-note-sticky text-light"></i></span>
		<input type="text" id="updateSpecify" name="updateSpecify" class="form-control">
	</div>
</div>

<div class="col-md-12 mb-3 text-end">
	<input type="submit" class="btn btn-outline-primary btn-sm" name="btnUpdate" value="Update">
</div>

</form>
	
</div> <!-- end of body -->

</div> <!-- end of content -->

</div> <!-- end dialog -->

</div>

<div class="modal fade" id="modalDelete" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	
<div class="modal-dialog modal-dialog-scrollable modal-lg">
	
<div class="modal-content">
	
<div class="modal-header">
	<h4 class="text-danger fw-bolder animated fadeIn slow infinite">Delete</h4>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div><!--  end header -->

<div class="modal-body">
<h3>The <i><span id="del-data" class="text-danger text-decoration-none"></span></i> records will be deleted?</h3>
	<form class="row" method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<input type="hidden" name="delete_dataId" id="delete_dataId">
		<div class="col-md-12 text-end">
			<input type="submit" class="btn btn-outline-danger btn-sm" name="btnDelete" value="Delete">
		</div>
	</form>
	
</div> <!-- end of body -->

</div> <!-- end of content -->

</div> <!-- end dialog -->

</div>
<!--  -->

<!-- services category page -->

<div class="modal fade" id="modalInsertServices" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	
<div class="modal-dialog modal-dialog-scrollable">
	
<div class="modal-content">
	
<div class="modal-header">
	<h4 class="text-primary fw-bolder animated fadeIn slow infinite">Entry</h4>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div><!--  end header -->

<div class="modal-body">
	<form class="row needs-validation" novalidate="" method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		
		<div class="col-md-12 mb-3">
		<label class="fw-bolder">Services</label>
		<div class="input-group">
			<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-users"></i></span>
			<input type="text" name="cat_service" class="form-control" required="">
		</div>
		</div>

		<div class="col-md-12 text-end mb-2">
			<input type="submit" class="btn btn-outline-info btn-sm" name="btnAddCat" value="Add">
		</div>
	</form>
</div> <!-- end of body -->

</div> <!-- end of content -->

</div> <!-- end dialog -->

</div>

<div class="modal fade" id="modalUpdateServices" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	
<div class="modal-dialog modal-dialog-scrollable">
	
<div class="modal-content">
	
<div class="modal-header">
	<h4 class="text-success fw-bolder animated fadeIn slow infinite">Update</h4>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div><!--  end header -->

<div class="modal-body">
		<form class="row needs-validation" novalidate="" method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<input type="hidden" name="edit_idService" id="edit_idService">
		<div class="col-md-12 mb-3">
		<label class="fw-bolder">Services</label>
		<div class="input-group">
			<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-users"></i></span>
			<input type="text" name="edit_cat_service" class="form-control" required="" id="edit_cat_service">
		</div>
		</div>

		<div class="col-md-12 text-end mb-2">
			<input type="submit" class="btn btn-outline-info btn-sm" name="btnEditCat" value="Update">
		</div>
	</form>
</div> <!-- end of body -->

</div> <!-- end of content -->

</div> <!-- end dialog -->

</div>

<div class="modal fade" id="modalDeleteServices" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	
<div class="modal-dialog modal-dialog-scrollable modal-lg">
	
<div class="modal-content">
	
<div class="modal-header">
	<h4 class="text-danger fw-bolder animated fadeIn slow infinite">Delete</h4>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div><!--  end header -->

<div class="modal-body">
	<h3>the <i><span id="del-service" class="text-danger text-decoration-none"></span></i> services will be deleted?</h3>
	<form class="row" method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<input type="hidden" name="del_idService" id="del_idService">
		<div class="col-md-12 text-end">
			<input type="submit" class="btn btn-outline-danger btn-sm" name="btnCatDel" value="Delete">
		</div>
	</form>
</div> <!-- end of body -->

</div> <!-- end of content -->

</div> <!-- end dialog -->

</div>
<!--  -->

<div class="modal fade" id="modaluserEdit" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    
<div class="modal-dialog modal-dialog-scrollable modal-lg">
    
<div class="modal-content">
    
<div class="modal-header">
    <h4 class="text-success fw-bolder animated fadeIn slow infinite">Update Password</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div><!--  end header -->

<div class="modal-body">

<form class="row needs-validation" novalidate="" method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

<input type="hidden" id="updatePass" name="updatePass">

<div class="col-md-6">
	<label>Password Update</label>
	<div class="input-group">
				<span class="bg-info bg-gradient input-group-text"><i class="text-light fa fa-lock"></i></span>
					<input type="password" name="updatPassword" id="update-password" class="form-control togglePassword" required="">
					<span class="bg-danger bg-gradient input-group-text toggleIcon">
					<i class="text-light fa fa-eye-slash d-none hide_eyes"></i>
					<i class="text-light fa fa-eye show_eyes"></i>
			</span>
			</div>
</div>

<div class="col-md-6">
	<input type="submit" name="btnUpdatePass" class="mt-md-4 btn-sm btn btn-outline-primary" value="Update">
</div>
    
</form>
    
</div> <!-- end of body -->

</div> <!-- end of content -->

</div> <!-- end dialog -->

</div>

<div class="modal fade" id="modalPCRestore" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	
<div class="modal-dialog modal-dialog-scrollable modal-lg">
	
<div class="modal-content">
	
<div class="modal-header">
	<h4 class="text-success fw-bolder animated fadeIn slow infinite">Restore</h4>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div><!--  end header -->

<div class="modal-body">
<h3>The <i><span id="restore-data" class="text-success text-decoration-none"></span></i> records will be Restored?</h3>
	<form class="row" method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<input type="hidden" name="restore_id" id="restore_id">
		<div class="col-md-12 text-end">
			<input type="submit" class="btn btn-outline-success btn-sm" name="btnDeleteArchive" value="Restore">
		</div>
	</form>
	
</div> <!-- end of body -->

</div> <!-- end of content -->

</div> <!-- end dialog -->

</div>
<!--  -->