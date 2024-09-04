<?php

include("../inc/config.php");
include("../inc/session.php");
include("../inc/class.php");

$dbConnect = new Connection($server, $user, $pass, $db_name);
$conn = $dbConnect->connectDb();

$account_type == "admin" ? /* true condition */ : header("location: ../logout");

?>


<?php require_once "template-parts/header.php"; ?>
<body>

<?php
require_once "template-parts/navbar.php";
require_once "modal/modal.php";
?>

<section id="account-settings" class="mt-md-4 pt-md-4">
<div class="container-fluid mt-md-3 mt-5">
<div class="row">
<div class="col-md-12">

<div class="table-responsive">

<table class="table table-hover">
<thead>
    <tr class="text-center">
        <th>No.</th>
        <th>Last Name</th>
        <th>First Name</th>
        <th>Email</th>
        <th>Username</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
<?php
    class ViewUserAccount{
    private $userRecords;

    public function __construct($userRecords){
        $this->userRecords = $userRecords;
    }

    public function displayUserAccount(){
    $ctr = 1;
    while($row_user_account = $this->userRecords->fetch_assoc()){
?>
    <tr class="text-center">
        <td><?= $ctr; ?></td>
        <td><?= $row_user_account["last_name"]; ?></td>
        <td><?= $row_user_account["first_name"]; ?></td>
        <td><?= $row_user_account["email"]; ?></td>
        <td><?= $row_user_account["username"]; ?></td>
        <td>
            <a href="#" id="<?= $row_user_account["user_id"]; ?>" class="btn btn-outline-success edit_userpass btn-sm" data-bs-toggle="modal" data-bs-target="#modaluserEdit"><i class="fa-solid fa-rotate-left"></i></a>
        </td>
    </tr>

<?php
        $ctr++;
      }
     }
    }

    $userAccountRecords = new UserAccountSettings($conn);
    $userAccount = $userAccountRecords->userAccount();

    $viewUserAccount = new ViewUserAccount($userAccount);
    $viewUserAccount->displayUserAccount();
?>
</tbody>
</table>

</div>

</div><!-- end of column -->
</div> <!-- end of row -->

</div>
</section>

<?php require_once "template-parts/bottom.php"; ?>
</body>
</html>

