"use strict";
document.querySelector(".toggleIcon").addEventListener("click", ()=>{
  let x = document.querySelector(".togglePassword");
  let show_eye = document.querySelector(".show_eyes");
  let hide_eye = document.querySelector(".hide_eyes");
  hide_eye.classList.remove("d-none");
  if (x.type === "password") {
    x.type = "text";
    show_eye.style.display = "none";
    hide_eye.style.display = "block";
  } else {
    x.type = "password";
    show_eye.style.display = "block";
    hide_eye.style.display = "none";
  }
}, false);