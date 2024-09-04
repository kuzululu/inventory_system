"use strict";

let btnPrint = document.querySelector("#print");

btnPrint.addEventListener("click", ()=>{
	let restorePage = document.body.innerHTML;
	let printArea = document.querySelector("#showdatarecords").innerHTML;

	document.body.innerHTML = printArea;
	window.print();

	document.body.innerHTML = restorePage;
	window.location.href = window.location.href;
},false);

let btnLaptopPrint = document.querySelector("#print");

btnLaptopPrint.addEventListener("click", ()=>{
	let restorePage = document.body.innerHTML;
	let printArea = document.querySelector("#showdatalaptoprecords").innerHTML;

	document.body.innerHTML = printArea;
	window.print();

	document.body.innerHTML = restorePage;
	window.location.href = window.location.href;
},false);

let btnApplePrint = document.querySelector("#print");

btnApplePrint.addEventListener("click", ()=>{
	let restorePage = document.body.innerHTML;
	let printArea = document.querySelector("#showdataApple").innerHTML;

	document.body.innerHTML = printArea;
	window.print();

	document.body.innerHTML = restorePage;
	window.location.href = window.location.href;
}, false);

let btnScannerPrint = document.querySelector("#print");

btnScannerPrint.addEventListener("click", ()=>{
	let restorePage = document.body.innerHTML;
	let printArea = document.querySelector("#showdataScanner").innerHTML;

	document.body.innerHTML = printArea;
	window.print();

	document.body.innerHTML = restorePage;
	window.location.href = window.location.href;
}, false);