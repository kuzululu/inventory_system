<?php

// alerts here if function outside the class no need the keyword public
function showAlertSuccess(){
echo "<script type='text/javascript' src='js/sweetalert2.all.min.js'></script>";
echo "<script type='text/javascript'>
document.addEventListener('DOMContentLoaded', ()=>{
Swal.fire({
position: 'top-end',
title: 'Add Successful!',
icon: 'success',
allowOutsideClick: false,
showConfirmButton: false,
allowEscapeKey: false
});
setTimeout(()=>{
window.location.href = window.location.href;
},1000);
});
</script>";
}

function showAlertRestore(){
echo "<script type='text/javascript' src='js/sweetalert2.all.min.js'></script>";
echo "<script type='text/javascript'>
document.addEventListener('DOMContentLoaded', ()=>{
Swal.fire({
position: 'top-end',
title: 'Restore Data Successful!',
icon: 'success',
allowOutsideClick: false,
showConfirmButton: false,
allowEscapeKey: false
});
setTimeout(()=>{
window.location.href = window.location.href;
},1000);
});
</script>";
}

function showAlertVerifiedPasswords(){
echo "<script type='text/javascript' src='js/sweetalert2.all.min.js'></script>";
echo "<script type='text/javascript'>
document.addEventListener('DOMContentLoaded', ()=>{
Swal.fire({
position: 'top-end',
title: 'Password has been updated Successful!',
icon: 'success',
allowOutsideClick: false,
showConfirmButton: false,
allowEscapeKey: false
});
setTimeout(()=>{
window.location.href='index';
},1000);
});
</script>";
}

function showAlertRegistrationSuccess(){
echo "<script type='text/javascript' src='js/sweetalert2.all.min.js'></script>";
echo "<script type='text/javascript'>
document.addEventListener('DOMContentLoaded', ()=>{
Swal.fire({
position: 'top-end',
title: 'Add Successful!',
icon: 'success',
allowOutsideClick: false,
showConfirmButton: false,
allowEscapeKey: false
});
setTimeout(()=>{
window.location.href='index';
},1000);
});
</script>";
}

function showAlertUpdate(){
echo "<script type='text/javascript' src='js/sweetalert2.all.min.js'></script>";
echo "<script type='text/javascript'>
document.addEventListener('DOMContentLoaded', ()=>{
Swal.fire({
position: 'top-end',
title: 'Update Successful!',
icon: 'success',
allowOutsideClick: false,
showConfirmButton: false,
allowEscapeKey: false,
});
setTimeout(()=>{
window.location.href = window.location.href;
},1000);
});
</script>";
}

function showAlertDelete(){
echo "<script type='text/javascript' src='js/sweetalert2.all.min.js'></script>";
echo "<script type='text/javascript'>
document.addEventListener('DOMContentLoaded', ()=>{
Swal.fire({
position: 'top-end',
title: 'Delete Successful!',
icon: 'error',
allowOutsideClick: false,
showConfirmButton: false,
allowEscapeKey: false,
});
setTimeout(()=>{
window.location.href = window.location.href;
},1000);
});
</script>";
}

function showAlertError(){
echo "<script type='text/javascript' src='js/sweetalert2.all.min.js'></script>";
echo "<script type='text/javascript'>
document.addEventListener('DOMContentLoaded', ()=>{
Swal.fire({
position: 'top-end',
title: 'Error',
icon: 'warning',
allowOutsideClick: false,
showConfirmButton: false,
allowEscapeKey: false,
});
setTimeout(()=>{
window.location.href = window.location.href;
},1000);
});
</script>";
}

function showAlertLoginError($result){ //<-- get the result variable in the statement of login
echo "<script type='text/javascript' src='js/sweetalert2.all.min.js'></script>";
echo "<script type='text/javascript'>
document.addEventListener('DOMContentLoaded', ()=>{
Swal.fire({
position: 'top-end',
title: '$result!',
icon: 'warning',
allowOutsideClick: false,
showConfirmButton: false,
allowEscapeKey: false,
timer: 1500
});
});
</script>";
}

?>