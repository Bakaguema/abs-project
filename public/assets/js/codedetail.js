
function RandomColor(){
    let random = Math.floor(Math.random()*16777215).toString(16);
    let color;
    color = "#" + random;
    return color;
}

function openTab(tabName) {
    let i;
    let tabContent;

    tabContent = document.getElementsByClassName("tableau_display");

    for (i = 0; i < tabContent.length; i++) {
        tabContent[i].style.display = "none";
    }

    document.getElementById(tabName).style.display = "flex";
}

let tableau = document.getElementById("tableaudiv");
let total = document.getElementById("totaldiv");

tableau.addEventListener("click", function(){openTab("tableau")}, false);
total.addEventListener("click", function(){openTab("total")}, false);
