var popup = document.getElementById("popup");
       
    

var arrow = document.getElementById("arrow");

var arrow2 = document.getElementById("arrow2");

arrow2.style.display = "none";
popup.style.display = "none";

arrow.addEventListener("click", function() {
    if(popup.style.display === "none") {
       
        arrow.style.display = "none";
        arrow2.style.display = "inline";
        popup.style.display = "block";
    }
 
});

arrow2.addEventListener("click", function() {
    if(popup.style.display != "none") {
       
        arrow.style.display = "inline";
        arrow2.style.display = "none";
        popup.style.display = "none";
    }
});


