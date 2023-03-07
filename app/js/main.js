feather.replace();

const dateControl = document.querySelector('input[type="datetime-local"]');
var now = new Date();
now.setMonth(now.getMonth() + 1);
dateControl.value = now.toISOString().slice(0, -8);

var coll = document.getElementById("collapser");
coll.addEventListener("click", function() {
    var content = document.getElementById("coll-content");
    var chvRight = document.getElementById("chevron-right");
    var chvDown = document.getElementById("chevron-down");
    if (content.style.display == "block") {
        content.style.display = "none";
        chvDown.style.display = "none";
        chvRight.style.display = "inline-block";  
    } else {
        content.style.display = "block";
        chvDown.style.display = "inline-block";
        chvRight.style.display = "none";
    }
});