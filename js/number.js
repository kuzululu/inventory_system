$(document).ready(function(){
	let input = $(".number");

	input.change(function(){
		$(this).val($(this).val().length > 11 ? $(this).val().slice(0,11) : $(this).val());
	});
});