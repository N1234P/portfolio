

// clear email elements to not be typewritten
var check = document.getElementById("check");
var deny = document.getElementById("deny");
var emailPromptElement = document.getElementsByClassName("emailPrompt")[0];
var arrow = document.getElementsByClassName("arrow")[0];
var emailInput = document.getElementsByClassName("emailInput")[0];
var other = document.getElementsByClassName("other")[0];


check.style.display = "none";
deny.style.display = "none";
emailPromptElement.style.display = "none";
arrow.style.display = "none";
emailInput.style.display = "none";
other.style.display = "none";

// clear password elements to not be typewritten
var check2 = document.getElementById("check2");
var deny2 = document.getElementById("deny2");
var passwordPromptElement = document.getElementsByClassName("passwordPrompt")[0];
var arrow2 = document.getElementsByClassName("arrow2")[0];
var passwordInput = document.getElementsByClassName("passwordInput")[0];
var other2 = document.getElementsByClassName("other2")[0];

check2.style.display = "none";
deny2.style.display = "none";
passwordPromptElement.style.display = "none";
arrow2.style.display = "none";
passwordInput.style.display = "none";
other2.style.display = "none";

// clear exit 
var exit = document.getElementById("exit");

exit.style.display = "none";







var elements = [];
var texts = [];

function startIntro() {
    let welcomeText = "Welcome to the Settings Page!";
    let foundText = "Here's what's found";
    var passwordText = "Password: " + $("#pass").val();
    
    let promptText = "Would you like to change your email?";
    
    texts = [welcomeText, foundText, userText, emailText, passwordText, promptText];
    
    
    let welcomeElement = document.getElementsByClassName("welcome")[0];
    
    let foundElement = document.getElementsByClassName("found")[0];
    
    let userElement = document.getElementsByClassName("username")[0];
    
    let emailElement = document.getElementsByClassName("email")[0];
    
    let promptElement = document.getElementsByClassName("prompt1")[0];
    
    let passwordElement = document.getElementsByClassName("password")[0];
    
    
    
    elements = [welcomeElement, foundElement, userElement, emailElement, passwordElement, promptElement];
    
    
    
}



var email = true;
var i = 0;
var j = 0;



function typeWriter() {
    
    if(i >= texts.length) {
        if(email) {
            check.style.display = "inline-block";
            deny.style.display = "inline-block";
        }
        else {
            check2.style.display = "inline-block";
            deny2.style.display = "inline-block";
        }
        
        return;
    }
    if(j < texts[i].length) {
        elements[i].innerHTML += texts[i][j];
        j++;
        setTimeout(typeWriter, 5);
        
    }
    else {
        i++;
        j = 0;
        setTimeout(typeWriter, 5);
    }
}

function validatePassword() {
    
    const passwordValidation = document.getElementById("passwordValidate");

    
    if(passwordValidation.value === $("#pass").val()) {
        
        document.getElementsByClassName('validate-container')[0].style.display = "none";
     
      
        startIntro();
        typeWriter();
    }

    else {
        $('#incorrect').text('Password is Incorrect!');
    }
  
}



function approveEmail() {
    
    const container = document.getElementsByClassName("settings")[0];
    container.style.height = "48vh";
    check.style.display = "none";
    deny.style.display = "none";
    emailPromptElement.style.display = "block";
    arrow.style.display = "inline-block";
    emailInput.style.display = "inline-block";
    other.style.display = "inline-block";
    other.style.marginBottom = "11px";
}

var validated = false;
var ajaxObj = {
    do_the_ajax : (data) => {
        $.ajax({
            type:'post',
            url:'settings.php',
            async: false,
            data: {newEmail : data[0]},
            success: function(data) {
                error = data.substring(0, data.indexOf("~"));
                error = error.trim();
                
                if(error.length > 0) {
                    $('.emailError').text(error);
                    $('.emailError').css({
                        'color' : 'red'
                    });
                }
                
                else {
                    $('.emailError').text("Changed Email!");
                    $('.emailError').css({
                        'color' : 'green'
                    })
                    console.log("HERE???");
                    validated = true;
                }
                console.log(error);
            }
        });},
        
    };
    
    var fromEmail = false;
    var visited = false;
    $('.other').click(() => {
        var newEmail = $('input[name="emailInput"]').val();
        console.log("FS");
        
        ajaxObj.do_the_ajax([newEmail]); 
       
        fromEmail = true;
        if(validated && !visited) {
            visited = true;
            startPasswordPrompt();
            
        }
    });
    
    
    function startPasswordPrompt() {
        
        check.style.display = "none";
        deny.style.display = "none";
        
        
        const container = document.getElementsByClassName("settings")[0];
        
        let promptText2 = "Would you like to change your password?";
        
        
        let promptElement2 = document.getElementsByClassName("prompt2")[0];
        promptElement2.innerHTML = "";
        
        if(fromEmail) {
            container.style.height = "56vh";
            $('.prompt2').css({
                'position' : 'relative',
                'bottom' : '25px'
            });
            
            $('#check2').css({
                'position' : 'relative',
                'bottom' : '25px',
            
            });
            
            $('#deny2').css({
                'position' : 'relative',
                'bottom' : '25px'
                
            });
        }
        
        else {
            container.style.height = "48vh";
        }
        
        
        texts = [promptText2];
        elements = [promptElement2];
        
        
        email = false;
        i = 0;
        j = 0;
        
        typeWriter();
        
    }
    
    function approvePassword() {
        const container = document.getElementsByClassName("settings")[0];
        container.style.height = "56vh";
        check2.style.display = "none";
        deny2.style.display = "none";
       
        passwordPromptElement.style.display = "block";
        arrow2.style.display = "inline-block";
        passwordInput.style.display = "inline-block";
        other2.style.display = "inline-block";

        if(fromEmail) {
            container.style.height = "68vh";
            
            $('.passwordPrompt').css({
                
                'position' : 'relative',
                'bottom' : '20px'
            });

            $('.arrow2').css({
                'position' : 'relative',
                'bottom' : '40px'
            })

            $('.passwordInput').css({
                'position' : 'relative',
                'bottom' : '40px'
            })

            $('.other2').css({
                'position' : 'relative',
                'bottom' : '40px'
            })

        }

        else {
            $('.passwordInput').css({
                'position' : 'relative',
                'bottom' : '12px'
            })

            $('.other2').css({
                'position' : 'relative',
                'bottom' : '6.5px'
            })
        }
       
    }


    var validatedPass = false;
    var ajaxObjPass = {
        do_the_ajax : (data) => {
            $.ajax({
                type:'post',
                url:'settings.php',
                async: false,
                data: {newPassword : data[0]},
                success: function(data) {
                    errorPass = data.substring(0, data.indexOf("~"));
                    errorPass = errorPass.trim();
                    
                    if(errorPass.length > 0) {
                        $('.passwordError').text(errorPass);
                        $('.passwordError').css({
                            'color' : 'red'
                        });
                    }
                    
                    else {
                        $('.passwordError').text("Changed Password!");
                        $('.passwordError').css({
                            'color' : 'green'
                        })
                        
                        validatedPass = true;
                    }
                    console.log(errorPass);
                }
            });},
            
        };

    
        $('.other2').click(() => {
            var newPassword = $('input[name="passwordInput"]').val();
          
            
            ajaxObjPass.do_the_ajax([newPassword]); 
            
            if(fromEmail) {
                console.log("here");
                $('.passwordError').css({
                    'position' : 'relative',
                    'bottom' : '60px'
                });
            }
         
            if(validatedPass) {
                showExit();
                
            }
        });

        function showExit() {
          
            check2.style.display = "none";
            deny2.style.display = "none";
            const container = document.getElementsByClassName("settings")[0];
            if(fromEmail) {
                container.style.height = "76vh";
                exit.style.display = "block";
                $('#exit').css({
                    'position' : 'relative',
                    'bottom' : '15px',
                    'text-align' : 'center'
                });
            }

            
            else {
                container.style.height = "63vh";
                exit.style.display = "block";
                $('#exit').css({
                    'position' : 'relative',
                    'top' : '8px',
                    'text-align' : 'center'
                });
            }
       
            
        }

    