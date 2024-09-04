<nav class="navbar bg-info bg-gradient fixed-top mb-5">

<div class="container-fluid">

<button class="navbar-toggler d-flex bg-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
  <span class="navbar-toggler-icon"></span>
</button>
<h5 class="animated pulse infinite position-absolute mt-2 d-md-block d-none ps-3 text-light text-decoration-none ms-5">CA IT Equipment Inventory</h5>
<!-- <span class="position-absolute d-md-block d-none ps-5 ms-3 text-light"><?= $full_name; ?></span> -->

<div class="offcanvas offcanvas-start" id="offcanvasDarkNavbar" tabindex="-1" aria-labelledby="offcanvasDarkNavbarLabel">

<div class="offcanvas-header">
<div class="row">
  <div class="col-md-12 text-end">
    <button type="button" class="btn-close btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="col-md-1"></div>
  <div class="col-md-10 p-0 border">
     <img src="../upload/<?= $img; ?>" class="img-fluid w-100">
  </div>
  <div class="col-md-1"></div>

  <div class="col-md-12">
      <h6 class="offcanvas-title text-muted text-center fw-bolder"><?= $full_name; ?></h6>
  </div>
</div>
</div><hr class="border border-2 border-muted">

<div class="offcanvas-body">
<div class="text-dark">Dashboard</div><hr class="border border-1 border-dark">


<ul class="navbar-nav justify-content-end flex-grow-1 pe-3">

 <li class="nav-item mb-3">
  <a href="index" type="button" class="text-decoration-none text-muted">Home</a>
 </li>

 <li class="nav-item mb-3">
  <a href="services" type="button" class="text-decoration-none text-muted">Add Services/Department</a>
 </li>

<li class="nav-item dropdown mb-3">
    <a href="#" class="text-decoration-none text-muted dropdown-toggle" type="button" aria-expanded="false" data-bs-toggle="dropdown">Computer</a>
   <ul class="dropdown-menu">
    <li class="nav-item mb-2">
    <button class="btn btn-info form-control btn-sm">
    <a href="pc_inventory" type="button" class="text-decoration-none text-light">Inventory</a>
    </button>
    </li>

    <li class="nav-item mb-2">
    <button class="btn btn-info form-control btn-sm">
    <a href="pc_summary" type="button" class="text-decoration-none text-light">Summary</a>
    </button>
    </li>

    <li class="nav-item mb-2">
      <button class="btn btn-info btn-sm form-control">
        <a href="pc_aging" class="text-decoration-none text-light">Pc Aging</a>
      </button>
    </li>

    <li class="nav-item mb-2">
      <button class="btn btn-info btn-sm form-control">
        <a href="pc_archives" class="text-decoration-none text-light">Archives</a>
      </button>
    </li>

   </ul>
 </li>

 <li class="nav-item dropdown mb-3">
    <a href="#" class="text-decoration-none text-muted dropdown-toggle" type="button" aria-expanded="false" data-bs-toggle="dropdown">Laptop</a>
   <ul class="dropdown-menu">
    <li class="nav-item mb-2">
    <button class="btn btn-info form-control btn-sm">
    <a href="laptop_inventory" type="button" class="text-decoration-none text-light">Inventory</a>
    </button>
    </li>

    <li class="nav-item mb-2">
    <button class="btn btn-info form-control btn-sm">
    <a href="laptop_summary" type="button" class="text-decoration-none text-light">Summary</a>
    </button>
    </li>

    <li class="nav-item mb-2">
      <button class="btn btn-info btn-sm form-control">
        <a href="laptop_aging" class="text-decoration-none text-light">Aging</a>
      </button>
    </li>

    <li class="nav-item mb-2">
      <button class="btn btn-info btn-sm form-control">
        <a href="laptop_archives" class="text-decoration-none text-light">Archives</a>
      </button>
    </li>
   </ul>
 </li>

<li class="nav-item dropdown mb-3">
    <a href="#" class="text-decoration-none text-muted dropdown-toggle" type="button" aria-expanded="false" data-bs-toggle="dropdown">Apple</a>
   <ul class="dropdown-menu">
      <li class="nav-item mb-2">
       <button class="btn btn-info form-control btn-sm">
        <a href="apple_inventory" type="button" class="text-decoration-none text-light">Inventory</a>
        </button>
      </li>
       <li class="nav-item mb-2">
       <button class="btn btn-info form-control btn-sm">
        <a href="apple_summary" type="button" class="text-decoration-none text-light">Summary</a>
        </button>
      </li>
       <li class="nav-item mb-2">
       <button class="btn btn-info form-control btn-sm">
        <a href="apple_aging" type="button" class="text-decoration-none text-light">Aging</a>
        </button>
      </li>
       <li class="nav-item mb-2">
       <button class="btn btn-info form-control btn-sm">
        <a href="apple_archives" type="button" class="text-decoration-none text-light">Archives</a>
        </button>
      </li>
    </ul>
 </li> 

<li class="nav-item dropdown mb-3">
    <a href="#" class="text-decoration-none text-muted dropdown-toggle" type="button" aria-expanded="false" data-bs-toggle="dropdown">Scanner Inventory</a>
   <ul class="dropdown-menu">
      <li class="nav-item mb-2">
       <button class="btn btn-info form-control btn-sm">
        <a href="scanner_inventory" type="button" class="text-decoration-none text-light">Inventory</a>
        </button>
      </li>
       <li class="nav-item mb-2">
       <button class="btn btn-info form-control btn-sm">
        <a href="scanner_summary" type="button" class="text-decoration-none text-light">Summary</a>
        </button>
      </li>
       <li class="nav-item mb-2">
       <button class="btn btn-info form-control btn-sm">
        <a href="scanner_aging" type="button" class="text-decoration-none text-light">Aging</a>
        </button>
      </li>
       <li class="nav-item mb-2">
       <button class="btn btn-info form-control btn-sm">
        <a href="scanner_archives" type="button" class="text-decoration-none text-light">Archives</a>
        </button>
      </li>
    </ul>
 </li>

<li class="nav-item mb-3">
  <a href="ms365_account" class="text-decoration-none dropdown-toggle text-muted" type="button" aria-expanded="false" data-bs-toggle="dropdown">MS 365 Account</a>
  <ul class="dropdown-menu">
    <li class="nav-item mb-2">
      <button class="btn btn-info form-control btn-sm">
        <a href="ms365_account" type="button" class="text-decoration-none text-light">Active Users</a>
      </button>
    </li>
    <li class="nav-item mb-2">
      <button class="btn btn-info form-control btn-sm">
        <a href="ms365_account_archives" type="button" class="text-decoration-none text-light">Archive Users</a>
      </button>
    </li>
  </ul>
</li>

<li class="nav-item mb-3">
 <a href="account_settings" class="text-decoration-none text-muted">Account Settings</a>
</li> 
	
<li class="nav-item mb-3">
	<button class="btn btn-danger btn-sm form-control"><a href="../logout" type="button" class="text-decoration-none text-light">Logout</a></button>
</li>

</ul>

</div>

</div>

</div>

</nav>
