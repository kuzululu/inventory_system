$(document).ready(function(){
	$(".datePicker").datepicker({
		dateFormat: "mm/dd/yy",
		// change year and month
		changeMonth: true,
		changeYear: true,
		yearRange: "1900:c" // 1900AD to 2013 + 10 years(1900:c+10)
	});
});

