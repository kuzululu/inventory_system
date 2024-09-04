"use strict";

let insertTagName = document.querySelector("#insertTagName");
let insertActualUser = document.querySelector("#insertActualUser");

let updateTagName = document.querySelector("#updateTagName");
let updateActualUser = document.querySelector("#updateActualUser");

insertTagName.addEventListener("input", ()=>{
    insertActualUser.value = insertTagName.value;
},false);

updateTagName.addEventListener("input", ()=>{
    updateActualUser.value = updateTagName.value;
},false);