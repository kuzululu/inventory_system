$(document).ready(function(){
	$("#insertOthers").on("change", function(){
		let trigger = $(this);
		let change = $(".others");
		let attrib = $("#insertSpecify");

		if (trigger.val() === "Others") {
			change.show("slow");
			attrib.attr("required", "required");
		}else{
			change.hide("slow");
			attrib.removeAttr("required");
			attrib.val("");
		}
	});

	$("#updateOthers").on("change", function(){
		let trigger = $(this);
		let change = $(".others");
		let attrib = $("#updateSpecify");

		if (trigger.val() === "Others") {
			change.show("slow");
			attrib.attr("required", "required");
		}else{
			change.hide("slow");
			attrib.removeAttr("required");
			attrib.val("");
		}
	});


	// in modal event
	$("#modalUpdate").on("shown.bs.modal", function(){
		let trigger = $("#updateOthers");
		let change = $(".others");
		let attrib = $("#updateSpecify");

		if (trigger.val() === "Others") {
			change.show("slow");
			attrib.attr("required", "required");
		}else{
			change.hide("slow");
			attrib.removeAttr("required");
			attrib.val("");
		}
	});

});

