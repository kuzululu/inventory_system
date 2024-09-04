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
