"use strict";

let fullName = "";

// Fetch the session data
let xhrSession = new XMLHttpRequest();
xhrSession.open("GET", "Apps/get_session_data.php", true);
xhrSession.onreadystatechange = function() {
    if (xhrSession.readyState === 4 && xhrSession.status === 200) {
        try {
            let sessionData = JSON.parse(xhrSession.responseText);
            fullName = sessionData.full_name || "";

            if (fullName) {
                console.log("Full Name is available: " + fullName);
            } else {
                console.error("Full Name is not available.");
            }
        } catch (error) {
            console.error("Error parsing session data:", error);
        }
    }
};
xhrSession.send();

let printM365 = () =>{

// Make an AJAX request to fetch aging records from PHP
let xhr = new XMLHttpRequest();
xhr.open("GET", "Apps/fetch_m365.php", true);
xhr.onreadystatechange = function(){
if (xhr.readyState === 4) {
if (xhr.status === 200) {
try{
	let responseData = JSON.parse(xhr.responseText);

	// get the key array records in fetch_m365
	let records = responseData.records;

	// Format the records for printing
	 let printArea = "<h4 class='text-center'>Commission On Appointmets</h4><h5 class='text-center text-dark'>Active M365 Licensed List</h5>";
	 printArea += "<div class='table-responsive'>";
	 printArea += "<table class='table table-hover'>";
	 printArea += "<thead><tr class='text-center'><th class='border border-dark'>No.</th><th class='border border-dark'>Display Name</th><th class='border border-dark'>Account Name</th><th class='border border-dark'>Actual User</th><th class='border border-dark'>Remarks</th></tr></thead>";
	 let counter = 1;

	 records.forEach(record =>{
	 	printArea += "<tbody><tr class='text-center border border-dark'>";
	 	printArea += "<td class='border border-dark'>"+ counter++ +"</td>";
	 	printArea += "<td class='border border-dark'>"+ record.display_name +"</td>";
	 	printArea += "<td class='border border-dark'>"+ record.account_name +"</td>";
	 	printArea += "<td class='border border-dark'>"+ record.actual_user +"</td>";
	 	printArea += "<td class='border border-dark'>"+ record.remarks +"</td>";
	 	printArea += "</tr></tbody>";
	 });

	 printArea += "</table>";
	 printArea += "</div>";
	 printArea += "<div class='row mt-3'>";
	 printArea += "<div class='col-6'><label class='text-dark'>Prepared by: <span class='fw-bolder'>"+fullName+"</span></label><br>LSEII</div>";
	 printArea += "<div class='col-6 text-end'><label class='text-dark'>Noted by: <span class='fw-bolder'>Juvy Balaoeg</span></label><br>OIC, DBLS</div>";
	 printArea +="</div>";

	 // print the records
	 let restorePage = document.body.innerHTML;
	 document.body.innerHTML = printArea;
	 window.print();

	 document.body.innerHTML = restorePage;
	 window.location.href = window.location.href;

}catch(error){
	// Display error message to the user
 alert("An error occurred while processing the data. Please try again later.");
 console.error("Error parsing JSON:", error);
}
}else{
// Display error message to the user
alert("Error fetching data. Please try again later.");
console.error("Error fetching data. Status code:", xhr.status);
}
}
};
xhr.send();
};

// calling function and trigger button
let btnM365 = document.querySelector("#printM365");
btnM365.addEventListener("click", printM365, false);

let btnmFilterm365 = document.querySelector("#printFilterM365");

btnmFilterm365.addEventListener("click", ()=>{
let restorePage = document.body.innerHTML;
let printArea = "<h4 class='text-center'>Commission On Appointmets</h4><h5 class='text-center'>Active M365 Licensed List</h5>";
printArea += document.querySelector("#showM365Data").innerHTML;


document.body.innerHTML = printArea;
window.print();

document.body.innerHTML = restorePage;
window.location.href = window.location.href;
},false);