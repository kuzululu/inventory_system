"use strict";

let btnScannerPrint = document.querySelector("#print");

btnScannerPrint.addEventListener("click", ()=>{
	let restorePage = document.body.innerHTML;
	let printArea = document.querySelector("#showdataScanner").innerHTML;

	document.body.innerHTML = printArea;
	window.print();

	document.body.innerHTML = restorePage;
	window.location.href = window.location.href;
}, false);
