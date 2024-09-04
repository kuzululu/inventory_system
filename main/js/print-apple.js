"use strict";

// pc aging print aging records
let printAgingPcRecords = () => {
// Make an AJAX request to fetch aging records from PHP
let xhr = new XMLHttpRequest();
xhr.open("GET", "Apps/fetch_apple.php", true);
xhr.onreadystatechange = function () {
if (xhr.readyState === 4) {
if (xhr.status === 200) {
 try {
     let responseData = JSON.parse(xhr.responseText);

     // get the key array records in fetch_aging_printRecords
     let records = responseData.records;
     let totalPcAging = responseData.total_aging; 
    
     // Format the records for printing
     let printArea = "<h2 class='text-center text-success'>Apple Aging Records</h2>";
      printArea += "<h5 class='fw-bolder'>Total Aging Count: <span class='fw-bolder text-success'>"+totalPcAging+"</span></h5>";
     printArea += "<div class='table-responsive'>";
     printArea += "<table class='table table-hover'>";
     printArea += "<thead><tr class='text-center'><th>No.</th> <th>Services</th> <th>Tag Name</th> <th>Description</th> <th>Property Tag</th> <th>Date Acquired</th> <th>Years Services</th> <th>Actual User</th> <th>Remarks</th> <th>Status</th></tr></thead>";
     let counter = 1;
     records.forEach(record => {
         // Parse and format the date
         let dateAcquired = new Date(record.date_acquired);
         let formattedDate = dateAcquired.toLocaleDateString("en-US", { month: "short", day: "2-digit", year: "numeric" });

         printArea += "<tbody><tr class='text-center'>";
         printArea += "<td>" + counter++ + "</td>";
         printArea += "<td>" + record.services + "</td>";
         printArea += "<td width='20%'>" + record.property_tag_name + "</td>";
         printArea += "<td width='20%'>" + record.description + "</td>";
         printArea += "<td width='15%'>" + record.property_tag + "</td>";
         printArea += "<td width='14%'>" + formattedDate + "</td>";
         printArea += "<td width='1%'>" + record.pc_aging + "</td>";
         printArea += "<td width='20%'>" + record.actual_user + "</td>";
         printArea += "<td width='5%'>" + record.remarks + "</td>";
         printArea += "<td width='10%'>" + record.service_unserviceable + "</td>";
         printArea += "</tr></tbody>";
     });
       printArea += "</table></div>";
     

     // Print the records
     let restorePage = document.body.innerHTML;
     document.body.innerHTML = printArea;
     window.print();
     document.body.innerHTML = restorePage;
     window.location.href = window.location.href;
 } catch (error) {
     // Display error message to the user
     alert("An error occurred while processing the data. Please try again later.");
     console.error("Error parsing JSON:", error);
 }
} else {
 // Display error message to the user
 alert("Error fetching data. Please try again later.");
 console.error("Error fetching data. Status code:", xhr.status);
}
}
};
xhr.send();
};
// ===================================================================



// functions

// trigger buttons
let btnPcAgingPrint = document.querySelector("#printAppleAging");
btnPcAgingPrint.addEventListener("click", printAgingPcRecords, false);


// ======================================================================
