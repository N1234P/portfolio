var login = document.getElementById("login");
var loginform = document.getElementById("logForm");
var background = document.getElementById("background");



loginform.style.display = "none";


login.addEventListener('click', function() {
    
    if(loginform.style.display != "none") {
        loginform.style.display = "none";
        background.classList.remove("blur");

    }
    else {
        loginform.style.display = "block";
        background.classList.add("blur");
        signform.style.display = "none";
    }
    
});

var exit = document.getElementById("exit");

exit.addEventListener('click', function() {
    loginform.style.display = "none";
    background.classList.remove("blur");
});




var signButton = document.getElementById("signup");
var signform = document.getElementById("signForm");
signForm.style.display = "none";

signButton.addEventListener("click", function() {
    
        signform.style.display = "block";
        loginform.style.display = "none";
    
});

var exit2 = document.getElementById("exit2");
exit2.addEventListener('click', function() {
    signform.style.display = "none";
    background.classList.remove("blur");
});

$('#webpage-description').css('opacity', 0);
$('.navigation').css('opacity', 0);

var titleElement = document.getElementById("webpage-title");
var title = titleElement.innerHTML;
var i = 0;
titleElement.innerHTML = "";

function typeWriter() {
    if(i < title.length) {
        console.log(titleElement.innerHTML);
        titleElement.innerHTML += title[i];
        i++;
        setTimeout(typeWriter, 100);
    }
    else {
        $('.navigation').css('opacity', 1)
        $('#webpage-description').css('opacity', 1);
    }
}
typeWriter();



//   $("#loginID").submit(function(event) {
//     loadAjax();
 //   event.returnValue = false;
// });