$(document).ready(function(){
	$("#insertUsername").change(function(){
		let selectedValue = $(this).val();
		$.ajax({
			url: "retrieve.php",
			method: "POST",
			data:{
				selected: selectedValue
			},
			dataType: "json",
			success: function(data){
				$("#insertMsName").val(data.account_name);
				$("#insertMsTempPass").val(data.temporary_pass);
				$("#insertMsRemarks").val(data.remarks);
                $("#insertMsDisplayName").val(data.display_name);
                $("#insertPerPass").val(data.permanent_pass);
			}
		});
	});
});



// $(document).ready(function(){
// $("#insertUsername").change(function(){
// switch($(this).val()){

// case "ca.odsiar@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Depsec Arturo Paras");
// $("#insertMsDisplayName").val("CA-ODSIAR");
// break;	

// case "susan.tabil@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Dir Susan Tabil");
// $("#insertMsDisplayName").val("CA-ACCOUNTING");
// break;

// case "william.aniag@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("William Aniag");
// $("#insertMsDisplayName").val("CA-ACCOUNTING");
// break;

// case "james.mangaran@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("James Mangaran");
// $("#insertMsDisplayName").val("CA-BUDGET");
// break;

// case "ghin.genteroy@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Ghin Genteroy");
// $("#insertMsDisplayName").val("CA-BUDGET");
// break;

// case "shervit.chan@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Shervit chan");
// $("#insertMsDisplayName").val("CA-CAB");
// break;

// case "trixie.valisno@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Trixie Valisno");
// $("#insertMsDisplayName").val("CA-GS");
// break;

// case "jeric@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Jeric Mabacquiao");
// $("#insertMsDisplayName").val("CA-GS");
// break;

// case "juliet@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Juliet Bernal");
// $("#insertMsDisplayName").val("CA-CASH");
// break;

// case "ryan@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Ryan Balajadia");
// $("#insertMsDisplayName").val("CA-ARIS");
// break;

// case "neil.pastrana@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Neil Pastrana");
// $("#insertMsDisplayName").val("CA-ARIS");
// break;

// case "joyce@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Joyce Castillo");
// $("#insertMsDisplayName").val("CA-HRMS");
// break;

// case "ken@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Kerner Lucban");
// $("#insertMsDisplayName").val("CA-HRMS");
// break;

// case "ernest@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Ernest Vera Cruz");
// $("#insertMsDisplayName").val("CA-IRS");
// break;

// case "vicky@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Marivic Guzman");
// $("#insertMsDisplayName").val("CA-LEGAL");
// break;

// case "luisa@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Luisa Pagayucan");
// $("#insertMsDisplayName").val("CA-SAA");
// break;

// case "joey.flaminiano@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Depsec Joey Flaminiano");
// $("#insertMsDisplayName").val("CA-ODSA");
// break;

// case "roland@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Roland Filoteo");
// $("#insertMsDisplayName").val("CA-TSS");
// break;

// case "henry@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Henry Kison");
// $("#insertMsDisplayName").val("CA-TSS");
// break;

// case "josam.samarista@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Dir Josam Samarista");
// $("#insertMsDisplayName").val("Zeigfredo Jose Samarista");
// break;

// case "km.bellen@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("karlo Michael Bellen");
// $("#insertMsDisplayName").val("karlo Michael Bellen");
// break;

// case "myra.villarica@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Sec Myra Villarica");
// $("#insertMsDisplayName").val("Myra Marie Villarica");
// break;

// case "jr.bones@comappgovph.onmicrosoft.com":
// $("#insertMsName").val("Jose Bones");
// $("#insertMsDisplayName").val("CA-DBLS");
// break;

// default:
// $("#insertMsName").val("");
// $("#insertMsDisplayName").val("");
// break;
// }
// });
// });

