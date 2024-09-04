"use strict";

let btnLaptopPrint = document.querySelector("#print");

btnLaptopPrint.addEventListener("click", ()=>{
	let restorePage = document.body.innerHTML;
	let printArea = document.querySelector("#showdatalaptoprecords").innerHTML;

	document.body.innerHTML = printArea;
	window.print();

	document.body.innerHTML = restorePage;
	window.location.href = window.location.href;
},false);