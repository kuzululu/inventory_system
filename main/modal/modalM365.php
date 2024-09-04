<!-- pc inventory page -->

<!-- data-bs-backdrop="static" use to disable modal outside click
data-bs-keyboard="false" use to disable modal esc key -->
<div class="modal fade" id="modalAddAccount" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">

<div class="modal-dialog modal-dialog-scrollable modal-lg">

<div class="modal-content">

<div class="modal-header">
<h4 class="text-primary fw-bolder animated fadeIn slow infinite">Add Account</h4>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div><!--  end header -->

<div class="modal-body">

<form class="row needs-validation" novalidate="" method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

<div class="col-md-12 mb-3">
<label>Username</label>
<div class="input-group">
<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-user"></i></span>
<input type="text" name="addUsername" class="form-control" required="">
</div>
</div>

<div class="col-md-12 mb-3">
<label>Account Name</label>
<div class="input-group">
<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-user"></i></span>
<input type="text" name="addAccountName" class="form-control" required="">
</div>
</div>

<div class="col-md-12 mb-3">
<label>Display Name</label>
<div class="input-group">
<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-users"></i></span>
<input name="addDisplayName" id="addDisplayName" required="" class="form-control">
</div>
</div>

<div class="col-md-12 mb-3 text-end">
<input type="submit" class="btn btn-outline-primary btn-sm" name="btnAddAccount" value="Add">
</div>

</form>

</div> <!-- end of body -->

</div> <!-- end of content -->

</div> <!-- end dialog -->

</div>


<!--  -->

<div class="modal fade" id="modalInsert" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">

<div class="modal-dialog modal-dialog-scrollable modal-lg">

<div class="modal-content">

<div class="modal-header">
<h4 class="text-primary fw-bolder animated fadeIn slow infinite">Entry</h4>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div><!--  end header -->

<div class="modal-body">

<form class="row needs-validation" novalidate="" method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

<div class="col-md-6 mb-3">
<?php
$usernameService = new ServiceManager($conn);
$usernames = $usernameService->getUsername();
?>
<label>Username</label>
<div class="input-group">
<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-user"></i></span>
<select name="insertMsUsername" required="" class="form-control" id="insertUsername">
	<option name="insertMsUsername" value=""></option>
	<?php foreach ($usernames as $username) { ?>
	<option name="insertMsUsername" value="<?= $username; ?>"><?= $username; ?></option>
  <?php	} ?>
</select>
</div>
</div>

<div class="col-md-6 mb-3">
<?php

?>
<label>Account Name</label>
<?php
$account_nameServices = new ServiceManager($conn);
$account_names = $account_nameServices->getAccountName();
?>
<div class="input-group">
<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-user"></i></span>
<select name="insertMsName" required="" class="form-control" id="insertMsName">
	<option name="insertMsName" value=""></option>
	<?php foreach ($account_names as $account_name) { ?>
	<option name="insertMsName" value="<?= $account_name; ?>"><?= $account_name; ?></option>
  <?php	} ?>
</select>
</div>
</div>

<div class="col-md-6 mb-3">
<?php
$display_namesServices = new ServiceManager($conn);
$display_names = $display_namesServices->getDisplayName();
?>
<label>Display Name</label>
<div class="input-group">
<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-users"></i></span>
<select name="insertMsDisplayName" id="insertMsDisplayName" required="" class="form-control">
	<option name="insertMsDisplayName" value=""></option>
	<?php foreach ($display_names as $display_name) { ?>
	<option name="insertMsDisplayName" value="<?= $display_name; ?>"><?= $display_name; ?></option>
  <?php	} ?>
</select>
</div>
</div>

<div class="col-md-6 mb-3">
<label>Actual User</label>
<div class="input-group">
<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-user"></i></span>
<input type="text" name="insertMsActualUser" class="form-control" required="">
</div>
</div>

<div class="col-md-3 mb-3">
<label>Temporary Password</label>
<div class="input-group">
<span class="input-group-text bg-info bg-gradient"><i class="fa fa-lock text-light"></i></span>
<input type="text" class="form-control" name="insertMsTempPass" id="insertMsTempPass">
</div>
</div>

<div class="col-md-3 mb-3">
<label>Permanent Password</label>
<div class="input-group">
<span class="input-group-text bg-info bg-gradient"><i class="fa fa-calendar text-light"></i></span>
<input type="text" class="form-control" name="insertPerPass" id="insertPerPass">
</div>
</div>

<div class="col-md-3 mb-3">
<label>Remarks</label>
<div class="input-group">
<span class="input-group-text bg-info bg-gradient"><i class="fa-solid fa-marker text-light"></i></span>
<select id="insertMsRemarks" name="insertMsRemarks" class="form-control" required="">
	<option name="inserMstRemarks" value=""></option>
	<option name="inserMstRemarks" value="Licensed">Licensed</option>
	<option name="inserMstRemarks" value="Unlicensed">Unlicensed</option>
</select>
</div>
</div>

<div class="col-md-3 mb-3">
	<label>Status</label>
	<div class="input-group">
		<span class="input-group-text bg-info bg-gradient"><i class="fa-regular fa-chart-bar text-light"></i></span>
		<select id="insertStatus" name="insertStatus" class="form-control">
			<option name="insertStatus" value=""></option>
			<option name="insertStatus" value="Pending">Pending</option>
			<option name="insertStatus" value="Complete">Complete</option>
		</select>
	</div>
</div>

<div class="col-md-12 mb-3 text-end">
<input type="submit" class="btn btn-outline-primary btn-sm" name="btnInsertMs" value="Add">
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

<input type="hidden" id="updateMsId" name="updateMsId">
<div class="col-md-6 mb-3">
<?php
$usernameService = new ServiceManager($conn);
$usernames = $usernameService->getUsername();
?>
<label>Username</label>
<div class="input-group">
<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-user"></i></span>
<select name="updateMsUsername" required="" class="form-control" id="updateMsUsername">
	<option name="updateMsUsername" value=""></option>
	<?php foreach ($usernames as $username) { ?>
	<option name="updateMsUsername" value="<?= $username; ?>"><?= $username; ?></option>
  <?php	} ?>
</select>

</div>
</div>

<div class="col-md-6 mb-3">
<?php
$account_nameServices = new ServiceManager($conn);
$account_names = $account_nameServices->getAccountName();
?>
<label>Account Name</label>
<div class="input-group">
<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-user"></i></span>
<select name="updateMsName" required="" class="form-control" id="updateMsName">
	<option name="updateMsName" value=""></option>
	<?php foreach ($account_names as $account_name) { ?>
	<option name="updateMsName" value="<?= $account_name; ?>"><?= $account_name; ?></option>
  <?php	} ?>
</select>
</div>
</div>

<div class="col-md-6 mb-3">
<?php
$display_namesServices = new ServiceManager($conn);
$display_names = $display_namesServices->getDisplayName();
?>
<label>Display Name</label>
<div class="input-group">
<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-users"></i></span>
<select name="updateMsDisplayName" id="updateMsDisplayName" required="" class="form-control">
	<option name="updateMsDisplayName" value=""></option>
	<?php foreach ($display_names as $display_name) { ?>
	<option name="updateMsDisplayName" value="<?= $display_name; ?>"><?= $display_name; ?></option>
  <?php	} ?>
</select>
</div>
</div>

<div class="col-md-6 mb-3">
<label>Actual User</label>
<div class="input-group">
<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-user"></i></span>
<input type="text" name="updateMsActualUser" id="updateMsActualUser" class="form-control" required="">
</div>
</div>

<div class="col-md-3 mb-3">
<label>Temporary Password</label>
<div class="input-group">
<span class="input-group-text bg-info bg-gradient"><i class="fa fa-lock text-light"></i></span>
<input type="text" class="form-control" name="updateMsTempPass" id="updateMsTempPass">
</div>
</div>

<div class="col-md-3 mb-3">
<label>Permanent Password</label>
<div class="input-group">
<span class="input-group-text bg-info bg-gradient"><i class="fa fa-calendar text-light"></i></span>
<input type="text" class="form-control" name="updateMsPermPass" id="updateMsPermPass">
</div>
</div>

<div class="col-md-3 mb-3">
<label>Remarks</label>
<div class="input-group">
<span class="input-group-text bg-info bg-gradient"><i class="fa-solid fa-marker text-light"></i></span>
<select id="updateMsRemarks" name="updateMsRemarks" class="form-control" required="">
	<option name="updateRemarks" value=""></option>
	<option name="updateRemarks" value="Licensed">Licensed</option>
	<option name="updateRemarks" value="Unlicensed">Unlicensed</option>
</select>
</div>
</div>

<div class="col-md-3 mb-3">
	<label>Status</label>
	<div class="input-group">
		<span class="input-group-text bg-info bg-gradient"><i class="fa-regular fa-chart-bar text-light"></i></span>
		<select id="updateMsStatus" name="updateMsStatus" class="form-control" required="">
			<option name="updateMsStatus" value=""></option>
			<option name="updateMsStatus" value="Pending">Pending</option>
			<option name="updateMsStatus" value="Complete">Complete</option>
		</select>
	</div>
</div>

<div class="col-md-12 mb-3 text-end">
<input type="submit" class="btn btn-outline-primary btn-sm" name="btnUpdateMs" value="Update">
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
<h3>The actual user <i><span id="delMs-dataActualUser" class="text-danger text-decoration-underline"></span></i> under the <i><span id="delMs-accountName" class="text-danger text-decoration-underline"></span></i> records will be deleted?</h3>
<form class="row" method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
<input type="hidden" name="del_MsId" id="del_MsId">
<div class="col-md-12 text-end">
	<input type="submit" class="btn btn-outline-danger btn-sm" name="btnDeleteMs" value="Delete">
</div>
</form>

</div> <!-- end of body -->

</div> <!-- end of content -->

</div> <!-- end dialog -->

</div>
<!--  -->

<script type="text/javascript" src="js/input.js"></script>