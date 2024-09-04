"use strict";

let btnApplePrint = document.querySelector("#print");

btnApplePrint.addEventListener("click", ()=>{
	let restorePage = document.body.innerHTML;
	let printArea = document.querySelector("#showdataApple").innerHTML;

	document.body.innerHTML = printArea;
	window.print();

	document.body.innerHTML = restorePage;
	window.location.href = window.location.href;
}, false);