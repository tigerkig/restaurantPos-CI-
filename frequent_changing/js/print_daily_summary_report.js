"use strict";
function printDiv(divName) {
    let printContents = document.getElementById(divName).innerHTML;
    let originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}