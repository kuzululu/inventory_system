// for disable ctr+p on printing
$(document).on("keydown", function(e){
	if ((e.ctrlKey || e.metaKey) && (e.charCode == 16 || e.charCode == 112 || e.keyCode == 80)) {
		// alert("Please use the Print button below for a better rendering on the document");
  
  Swal.fire({
  	position:"top-end",
    icon: "error",
  	title: "oops",
  	text: "Bawal gumamit ng ctr+p shortcut sa pag print",
    allowOutsideClick: false,
    allowEscapeKey: false,
    allowConfirmeButton: false,
  });

  setTimeout(()=>{
    window.location.href = window.location.href;
  },1000);


  // is used to stop the propagation of an event in older versions of Internet Explorer (IE) and some other browsers that support the legacy cancelBubble property. This property is specific to IE and is not part of the standard Event interface.
  // e.cancelBubble = true; //old browser

  e.stopPropagation(); // modern browser

  e.preventDefault();

   // method is used to prevent further propagation of an event within the event flow. It is typically called inside an event handler function to stop the event from being propagated to other elements or event listeners that are registered for the same event.
  e.stopImmediatePropagation();
	}
});