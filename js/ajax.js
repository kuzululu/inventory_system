// reset page if field is blank
$(document).ready(function(){
$(".resetSearch").on("keyup", function(e){
e.preventDefault();
let resetSearch = $(this).val();
resetSearch == "" ? window.location.href = window.location.href : null;
});

$(".resetSearch").on("change", function(e){
e.preventDefault();
let resetSearch = $(this).val();
resetSearch == "" ? window.location.href = window.location.href : null;
});
});


// service category page
// retrieve data
$(document).on("click", ".service_editId", function(e){
e.preventDefault();
let restore_editData = $(this).attr("id");
$.ajax({
url: "retrieve.php",
method: "POST",
data:{
edit_idService: restore_editData
},
dataType: "json",
success: function(data){
$("#edit_idService").val(data.id_services);
$("#edit_cat_service").val(data.services_category);
}

});
});

$(document).on("click", ".service_delId", function(e){
e.preventDefault();
let restore_delData = $(this).attr("id");
$.ajax({
url: "retrieve.php",
method: "POST",
data:{
del_idService: restore_delData
},
dataType: "json",
success: function(data){
$("#del_idService").val(data.id_services);
$("#del-service").html(data.services_category);
}
});
});

$(document).on("click", ".edit-dataId", function(e){
e.preventDefault();
let retrieve_dataId = $(this).attr("id");
$.ajax({
url: "retrieve.php",
method: "POST",
data:{
updateId: retrieve_dataId
},
dataType: "json",
success: function(data){
$("#updateId").val(data.id);
$("#updateServices").val(data.services);
$("#updateTagName").val(data.property_tag_name);
$("#updateProperty").val(data.property_tag);
$("#updateDesc").val(data.description);
$("#updateActualUser").val(data.actual_user);
$("#updateDate").val(data.date_acquired);
$("#updateOthers").val(data.remarks);
$("#updateSpecify").val(data.specify);
$("#updateStatus").val(data.service_unserviceable);
}
});
});

$(document).on("click", ".delete-dataId", function(e){
e.preventDefault();
let retrieve_dataId = $(this).attr("id");
$.ajax({
url: "retrieve.php",
method: "POST",
data:{
delete_dataId: retrieve_dataId
},
dataType: "json",
success: function(data){
$("#delete_dataId").val(data.id);
$("#del-data").html(data.property_tag_name);
}
});
});

$(document).on("click", ".restore-scanner-arch", function(e){
	e.preventDefault();
	let restore_id = $(this).attr("id");
	$.ajax({
		url: "retrieve.php",
		method: "POST",
		data:{
			restore_id: restore_id
		},
		dataType: "json",
		success: function(data){
			$("#restore_scannerId").val(data.id);
			$("#restore-ScannerData").html(data.services);
			$("#restore-ScannerTag").html(data.property_tag_name);
		}
	});
});

$(document).on("click", ".restore-pc", function(e){
e.preventDefault();
let restore_id = $(this).attr("id");
$.ajax({
url: "retrieve.php",
method: "POST",
data:{
restore_id: restore_id
},
dataType: "json",
success: function(data){
$("#restore_id").val(data.id);
$("#restore-data").html(data.property_tag_name);
}
});
});

$(document).on("click", ".edit_userpass", function(e){
e.preventDefault();
let edit_userpass = $(this).attr("id");
$.ajax({
url: "retrieve.php",
method: "POST",
data:{
updatePass: edit_userpass
},
dataType: "json",
success: function(data){
$("#updatePass").val(data.user_id);
}
});
});


// pc age filter 
$(document).ready(function(){
$("#pcAgeFilter").on("change", function(){
let filter = $(this).val();
$.ajax({
url: "../inc/class.php",
method: "POST",
data:{
	pcAgeFilter: filter
},
success: function(response){
	$("#showAgeData").html(response);
}
});
});
});

// laptop age filter 
$(document).ready(function(){
$("#laptopAgeFilter").on("change", function(){
let filter = $(this).val();
$.ajax({
url: "../inc/class.php",
method: "POST",
data:{
	laptopAgeFilter: filter
},
success: function(response){
	$("#showAgeLaptopData").html(response);
}
});
});
});


// filter pc inventory page
$(document).ready(function(){
$("#filter").on("change", function(){
let filter = $(this).val();
$.ajax({
url: "../inc/class.php",
method: "POST",
data:{
	filter: filter
},
success: function(response){
	$("#showdatarecords").html(response);
}
});
});
});

// laptop filter by date acquired
$(document).ready(function(){
$("#filterLaptop").on("change", function(){
let filterLaptopDate = $(this).val();
$.ajax({
url: "../inc/class.php",
method: "POST",
data:{
	filterDateLaptop: filterLaptopDate
},
success: function(response){
	$("#showdatalaptoprecords").html(response);
}
 });
 });
});

$(document).ready(function(){
	$("#filterLaptopYrService").on("change", function(){
		let filterYrs = $(this).val();
		$.ajax({
			url: "../inc/class.php",
			method: "POST",
			data:{
				filterYrs: filterYrs
			},
			success: function(response){
				$("#showdatalaptoprecords").html(response);
			}
		});
	});
});

// filter by category
$(document).ready(function(){
$("#filterType").on("keyup", function(){
let filter = $(this).val();
$.ajax({
url: "../inc/class.php",
method: "POST",
data:{
	filterType: filter
},
success: function(response){
	$("#showdatarecords").html(response);
}
});
});
});

// filter laptop by category archives
$(document).ready(function(){
$("#filterTypeArchLaptop").on("keyup", function(){
let filter = $(this).val();
$.ajax({
url: "../inc/class.php",
method: "POST",
data:{
	filterTypeArchLaptop: filter
},
success: function(response){
	$("#showPCArchivesTable").html(response);
}
});
});
});


// filter by category
$(document).ready(function(){
$("#filterTypeArchive").on("keyup", function(){
let filter = $(this).val();
$.ajax({
url: "../inc/class.php",
method: "POST",
data:{
	filterTypeArchive: filter
},
success: function(response){
	$("#showPCArchivesTable").html(response);
}
});
});
});


$(document).ready(function(){
	$("#filterLaptopType").on("keyup", function(){
		let filter = $(this).val();
		
		$.ajax({
			url: "../inc/class.php",
			method: "POST",
			data:{
				filterLaptopType: filter
			},
			success: function(response){
				$("#showdatalaptoprecords").html(response);
			}
		});
	});
});

$(document).ready(function(){
$("#filterYrService").change(function(){
let filterYrService = $(this).val();
$.ajax({
url: "../inc/class.php",
method: "POST",
data:{
	filterYrService: filterYrService
},
success: function(response){
	$("#showdatarecords").html(response);
}
});
});
});

$(document).ready(function(){
$("#filterServices").on("change", function(){
let filterServices = $(this).val();
$.ajax({
url: "../inc/class.php",
method: "POST",
data:{
	filterServices: filterServices
},
success: function(response){
	$("#showDataServices").html(response);
}
});
});
});


// laptop data
$(document).on("click", ".editLaptopId", function(e){
e.preventDefault();
let retrieve_dataId = $(this).attr("id");

$.ajax({
url: "retrieve.php",
method: "POST",
data:{
update_laptopId: retrieve_dataId
},
dataType: "json",
success: function(data){
$("#updateId").val(data.id);
$("#updateServices").val(data.services);
$("#updateTagName").val(data.property_tag_name);
$("#updateProperty").val(data.property_tag);
$("#updateDesc").val(data.description);
$("#updateActualUser").val(data.actual_user);
$("#updateDate").val(data.date_acquired);
$("#updateOthers").val(data.remarks);
$("#updateSpecify").val(data.specify);
$("#updateStatus").val(data.service_unserviceable);
}
});
});

$(document).on("click", ".delLaptopId", function(e){
e.preventDefault();
let retrieve_dataId = $(this).attr("id");
$.ajax({
url: "retrieve.php",
method: "POST",
data:{
delLaptopId: retrieve_dataId
},
dataType: "json",
success: function(data){
$("#delete_dataId").val(data.id);
$("#del-data").html(data.property_tag_name);
}
});
});

$(document).on("click", ".restore-laptop", function(e){
e.preventDefault();
let restore_laptopId = $(this).attr("id");
$.ajax({
url: "retrieve.php",
method: "POST",
data:{
restore_laptopId: restore_laptopId
},
dataType: "json",
success: function(data){
$("#restore_laptopId").val(data.id);
$("#restore-data").html(data.property_tag_name);
}
});
});


$(document).on("click", ".restore-apple", function(e){
e.preventDefault();
let restore_AppleId = $(this).attr("id");
$.ajax({
url: "retrieve.php",
method: "POST",
data:{
restore_AppleId: restore_AppleId
},
dataType: "json",
success: function(data){
$("#restore_AppleId").val(data.id);
$("#restore-Appledata").html(data.services);
$("#restore-appleTag").html(data.property_tag_name);
}
});
});

$(document).on("click", ".editAppleId", function(e){
e.preventDefault();
let retrieve_appleDataId = $(this).attr("id");
$.ajax({
url: "retrieve.php",
method: "POST",
data:{
updateApple_id: retrieve_appleDataId
},
dataType: "json",
success: function(data){
$("#updateAppleId").val(data.id);
$("#updateServices").val(data.services);
$("#updateTagName").val(data.property_tag_name);
$("#updateProperty").val(data.property_tag);
$("#updateDesc").val(data.description);
$("#updateActualUser").val(data.actual_user);
$("#updateDate").val(data.date_acquired);
$("#updateOthers").val(data.remarks);
$("#updateSpecify").val(data.specify);
$("#updateStatus").val(data.service_unserviceable);
}
});
});

$(document).on("click", ".delAppleId", function(e){
e.preventDefault();
let restore_delAppleData = $(this).attr("id");

$.ajax({
url: "retrieve.php",
method: "POST",
data:{
deleteAppleId: restore_delAppleData
},
dataType: "json",
success: function(data){
$("#delete_AppledataId").val(data.id);
$("#del-Appledata").html(data.services);
$("#del-Appledatauser").html(data.property_tag_name);
}
});
});

// apple age filter 
$(document).ready(function(){
    $("#appleAgeFilter").on("change", function(){
	let filter = $(this).val();
	$.ajax({
	    url: "../inc/class.php",
	    method: "POST",
	    data: { appleAgingFilter: filter },
	    success: function(response){
	        $("#showAgeAppleData").html(response);
	    }
	});
  });
});

// filter apple by date acquired
$(document).ready(function(){
	$("#filterApple").on("change", function(){
		let filter = $(this).val();
		$.ajax({
			url: "../inc/class.php",
			method: "POST",
			data:{
				filterApple: filter
			},
			success: function(response){
				$("#showdataApple").html(response);
			}
		});
	});
});

// filter apple by years
$(document).ready(function(){
	$("#filterAppleYrService").on("change", function(){
		let filter = $(this).val();
		$.ajax({
			url: "../inc/class.php",
			method: "POST",
			data:{
				filterAppleYrService: filter
			},
			success: function(response){
				$("#showdataApple").html(response);
			}
		});
	});
});

// filter apple by category
$(document).ready(function(){
	$("#filterAppleType").on("keyup", function(){
		let filter = $(this).val();
		$.ajax({
			url: "../inc/class.php",
			method: "POST",
			data:{
				filterAppleType: filter
			},
			success: function(response){
				$("#showdataApple").html(response);
			}
		});
	});
});

// scanner filter by date acquired
$(document).ready(function(){
$("#filterScanner").on("change", function(){
let filter = $(this).val();
$.ajax({
url: "../inc/class.php",
method: "POST",
data:{
	filterScanner: filter
},
success: function(response){
	$("#showdataScanner").html(response);
}
 });
 });
});

// filter scanner by years of service
$(document).ready(function(){
	$("#filterScannerYrService").on("change", function(){
		let filter = $(this).val();
		$.ajax({
			url: "../inc/class.php",
			method: "POST",
			data:{
				filterScannerYrService: filter
			},
			success: function(response){
				$("#showdataScanner").html(response);
			}
		});
	});
});

// filter scanner by category
$(document).ready(function(){
	$("#filterScannerType").on("keyup", function(){
		let filter = $(this).val();
		$.ajax({
			url: "../inc/class.php",
			method: "POST",
			data:{
				filterScannerType: filter
			},
			success: function(response){
				$("#showdataScanner").html(response);
			}
		});
	});
});

// modal retrieve scanner update
// 
$(document).on("click", ".editScannereId", function(e){
e.preventDefault();
let retrieve = $(this).attr("id");
$.ajax({
url: "retrieve.php",
method: "POST",
data:{
editScannereId: retrieve
},
dataType: "json",
success: function(data){
$("#updateScannerId").val(data.id);
$("#updateServices").val(data.services);
$("#updateTagName").val(data.property_tag_name);
$("#updateProperty").val(data.property_tag);
$("#updateDesc").val(data.description);
$("#updateActualUser").val(data.actual_user);
$("#updateDate").val(data.date_acquired);
$("#updateOthers").val(data.remarks);
$("#updateSpecify").val(data.specify);
$("#updateStatus").val(data.service_unserviceable);
}
});
});

// Scanner retrieve data delete
$(document).on("click", ".delScannereId", function(e){
	e.preventDefault();
	let retrieve = $(this).attr("id");
	$.ajax({
		url: "retrieve.php",
		method: "POST",
		data:{
			deletScannerId: retrieve
		},
		dataType: "json",
		success: function(data){
			$("#del_scannerId").val(data.id);
			$("#del-Scanneredata").html(data.services);
			$("#del-Scanneredatauser").html(data.property_tag_name);
		}
	});
});

// scanner age filter
$(document).ready(function(){
	$("#scannerAgeFilter").on("change", function(){
		let filter = $(this).val();
		$.ajax({
			url: "../inc/class.php",
			method: "POST",
			data:{
				scannerAgeFilter: filter
			},
			success: function(response){
				$("#showAgescannerData").html(response);
			}
		});
	});
});

// apple archives filter 
$(document).ready(function(){
	$("#filterAppleArchiveType").on("keyup", function(){
		let filter = $(this).val();
		$.ajax({
			url: "../inc/class.php",
			method: "POST",
			data:{
				filterAppleArchiveType: filter
			},
			success: function(response){
				$("#showPCArchivesTable").html(response);
			}
		});
	});
});


// fitler scanner archive
$(document).ready(function(){
$("#filterScannerTypeArchive").on("keyup", function(){
let filter = $(this).val();
$.ajax({
url: "../inc/class.php",
method: "POST",
data:{
	filterArchScannerType: filter
},
success: function(response){
	$("#showScannerArchivesTable").html(response);
}
});
});
});

// MS 365 side
$(document).on("click", ".editM365Id", function(e){
	e.preventDefault();
	let retrieve = $(this).attr("id");
	$.ajax({
		url: "retrieve.php",
		method: "POST",
		data:{
			editMs365: retrieve
		},
		dataType: "json",
		success: function(data){
			$("#updateMsId").val(data.id);
			$("#updateMsUsername").val(data.username);
			$("#updateMsName").val(data.account_name);
			$("#updateMsDisplayName").val(data.display_name);
			$("#updateMsActualUser").val(data.actual_user);
			$("#updateMsTempPass").val(data.temporary_pass);
			$("#updateMsPermPass").val(data.permanent_pass);
			$("#updateMsRemarks").val(data.remarks);
			$("#updateMsStatus").val(data.status);
		}
	});
});

$(document).on("click", ".delM365Id", function(e){
	e.preventDefault();
	let retrieve = $(this).attr("id");
	$.ajax({
		url: "retrieve.php",
		method: "POST",
		data:{
			delMs365: retrieve
		},
		dataType: "json",
		success: function(data){
			let dataSelect = $("#delMs-dataActualUser");
			$("#del_MsId").val(data.id);
			if (!data.actual_user) {
				dataSelect.html("No Input");
			}else{
				dataSelect.html(data.actual_user);
			}
			$("#delMs-accountName").html(data.display_name);
		}
	});
});

$(document).ready(function(){
	$("#filterM365").on("keyup", function(){
		let filter = $(this).val();
		$.ajax({
			url: "../inc/class.php",
			method: "POST",
			data:{
				m365Filter: filter
			},
			success: function(response){
				$("#showM365Data").html(response);
				$("#printFilterM365").show();
				$("#printM365").hide();
			}
		});
	});
});