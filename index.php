<?php

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

include("inc/config.php");
include("inc/class.php");

$connection = new Connection($server, $user, $pass, $db_name);
$conn = $connection->connectDb();

?>

<?php require_once "template-parts/header.php"; ?>
<body id="body">

<section id="login-page mt-3 mt-md-5">
	
<div class="container mt-md-5 pt-md-5 mt-1 pt-1">
	<div class="row">
		<div class="col-md-3"></div>
	<div class="col-md-6 text-light border animated zoomIn" id="glass">
		<h4 class="fw-bolder animated slideInDown slow infinite pulse text-center">Login</h4><hr>

		<form class="row needs-validation" novalidate="" method="POST" action="<?php htmlspecialchars("PHP_SELF"); ?>">
			
		<div class="col-md-12 mb-3">
			<label>Username:</label>
			<div class="input-group">
				<span class="bg-info bg-gradient input-group-text"><i class="text-light fa fa-user"></i></span>
				<input type="text" name="userLog" class="form-control" required="" autofocus="">
			</div>
		</div>

		<div class="col-md-12 mb-3">
			<label>Password:</label>
			<div class="input-group">
				<span class="bg-info bg-gradient input-group-text"><i class="text-light fa fa-lock"></i></span>
					<input type="password" name="passLog" class="form-control togglePassword" required="">
					<span class="bg-danger bg-gradient input-group-text toggleIcon">
					<i class="text-light fa fa-eye-slash d-none hide_eyes"></i>
					<i class="text-light fa fa-eye show_eyes"></i>
			</span>
			</div>
		</div>

		<div class="col-md-12 text-end mb-3">
			<input type="submit" class="btn btn-primary btn-sm" value="Login" name="btnLogin"> 
			<div class="text-center">
				<small class="text-center fw-bolder">No Account click <a href="register" class="animated fadeIn infinite text-light">here</a> to Register</small>
			</div>
		</div>

		</form>

	</div>
	<div class="col-md-3"></div>
	</div>
</div>

</section>


<?php require_once "template-parts/bottom.php"; ?>
</body>
</html>