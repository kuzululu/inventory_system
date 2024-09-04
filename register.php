<?php

include("inc/config.php");
include("inc/class.php");

$connection = new Connection($server, $user, $pass, $db_name);
$conn = $connection->connectDb();

?>

<?php require_once "template-parts/header.php"; ?>
<body id="body">

<section id="registration">

<div class="container mt-3">
<div class="row">

<div class="col-md-12">
<h4 class="fw-bolder text-light animated infinite slideInDown text-center slow">Registration</h4><hr>
</div>
</div>


<div class="row">

<div class="col-md-12 border text-light animated slower fadeInDown border-light border-2 p-3" id="glass">
	
<form class="row needs-validation p-2" novalidate="" enctype="multipart/form-data" method="POST" action="<?php htmlspecialchars("PHP_SELF"); ?>">

<div class="col-md-5 mb-3">
	<label class="fw-bolder">Last Name: </label>
	<div class="input-group">
		<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-user"></i></span><input type="text" name="lname" class="form-control" required="" pattern="[a-zA-Z ]+" title="letters only">
	</div>
</div>

<div class="col-md-4 mb-3">
	<label class="fw-bolder">First Name: </label>
	<div class="input-group">
		<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-user"></i></span><input type="text" name="fname" class="form-control" required="">
	</div>
</div>

<div class="col-md-3 mb-3">
	<label class="fw-bolder">Given Name: </label>
	<div class="input-group">
		<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-user"></i></span><input type="text" name="mname" class="form-control" required="">
	</div>
</div>

<div class="col-md-4 mb-3">
	<label class="fw-bolder">Contact: </label>
	<div class="input-group">
		<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-phone"></i></span><input type="number" name="contact" class="form-control number" placeholder="ex: xxxxxxxxxxx" maxlength="11" min="0" minlength="8" step="1" required="">
	</div>
</div>

<div class="col-md-4 mb-3">
	<label class="fw-bolder">Email: </label>
	<div class="input-group">
		<span class="bg-info bg-gradient input-group-text"><i class="text-light fa fa-envelope"></i></span><input type="email" name="email" class="form-control" required="">
	</div>
</div>

<div class="col-md-4 mb-3">
	<label class="fw-bolder">Username: </label>
	<div class="input-group">
		<span class="bg-info bg-gradient input-group-text"><i class="text-light fa fa-user"></i></span><input type="text" name="user" class="form-control" pattern="[a-zA-Z ]+" title="letters only" required="">
	</div>
</div>

<div class="col-md-7 mb-3">
	<label class="fw-bolder">Password: </label>
	<div class="input-group">
		<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-lock"></i></span><input type="password" name="pass" class="form-control togglePassword" title="Please enter a password with at least one capital letter, one special character, and a minimum of 8 characters" required="" pattern="^(?=.*[A-Z]).{8,}$">
		<span class="bg-danger bg-gradient input-group-text toggleIcon">
			<i class="text-light fa fa-eye-slash d-none hide_eyes"></i>
			<i class="text-light fa fa-eye show_eyes"></i>
		</span>
	</div>
</div>

<div class="col-md-5 mb-3">
	<label class="fw-bolder">Upload Profile: </label>
	<div class="input-group">
		<span class="input-group-text bg-info bg-gradient"><i class="text-light fa fa-file"></i></span><input type="file" name="file" class="form-control" required="" accept="image/*">
	</div>
</div>

<div class="col-md-12 mb-3 text-end">
	<input type="submit" name="btnRegister" class="btn btn-primary btn-sm"> <a href="index" class="btn btn-danger btn-sm">Back</a>
</div>
	
</form>

  </div>
 </div>
</div>

</section>


<?php require_once "template-parts/bottom.php"; ?>
</body>
</html>