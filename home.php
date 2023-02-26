<?php 
$error = "";
require_once('connection.php');



// LOGGING IN ----- 
if(isset($_POST['email']) && isset($_POST['password'])) {

    $email=$_POST['email'];
    $password=$_POST['password'];

    $query="SELECT * FROM users WHERE Email = '".$email."' AND Password = '".$password."' limit 1";

    $result=mysqli_query($mysqli, $query);



    if(mysqli_num_rows($result) == 1) {
        $error = "";
        $row = mysqli_fetch_row($result);
        $username = $row[1];
        header("location:tracker.php");
        
        session_start();
        $_SESSION['username'] = $username;
  
        
    }

    else {
        $error = "Incorrect Email or Password";
    }

    echo "$error~";


}
// SIGNING UP ---- WILL NEED TO VERIFY IF USERNAME DOES NOT EXIST
$error_signup = "";

if(isset($_POST["username"]) && isset($_POST["email_sign"]) && isset($_POST["password_sign"]) && isset($_POST["password2_sign"])) {
    
    $username = $_POST["username"];
    $email = $_POST["email_sign"];
    $password = $_POST["password_sign"];
    $password2 = $_POST["password2_sign"];
    $query="SELECT * FROM users WHERE Email = '".$email."'";

    $emails = mysqli_query($mysqli, $query);

    $query = "SELECT * FROM users WHERE Username = '".$username."'";

    $usernames = mysqli_query($mysqli, $query);
    

    if(mysqli_num_rows($emails) >= 1 || mysqli_num_rows($usernames) >= 1) {
        $error_signup = "Email/Username already exists";
    }


    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_signup = "Invalid Email";
    }

    else if($password2 != $password) {
        $error_signup = "Passwords do not match";
        
    }

    else if(strlen($password) < 8) {
        $error_signup = "Password is too short";
    }

    else {
       

        
        $query = "INSERT INTO users VALUES(DEFAULT, '".$username."', '".$email."', '".$password."')";
        mysqli_query($mysqli, $query);

        header("location:tracker.php");
        $_SESSION["username"] = $username;

       
    }
    echo "$error_signup~";
}



?>



<!DOCTYPE html>
<html lang="en">
<head>
   
   
    <meta name="viewport" content="width=device-width, initial-scale=.75">
    <title>Portfolio Tracker</title>
    <link rel="stylesheet" type="text/css" href="home.css">  
    <link rel="stylesheet" type="text/css" href="stars.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    
    <div class="container" id="background">
        <div>
            <div id="stars"></div>
            <div id="stars2"></div>
            <div id="stars3"></div>
        </div>    
        <nav class="navigation">
            <ul>
                <li><a href="news.php">News</a></li>
                <li><a href="faq.html">FAQ</a></li>
                <li><a id="login">Login/Sign up</a></li>
            </ul>
            <img class="github" src="images/github-modified.png">
        </nav>
        
        <div class="title">
            <h1 id="webpage-title">Portfolio Tracker</h1>
            <h3 id="webpage-description">User-Defined & Real Time<sup>™<sup></h3>
            
        </div>
    </div>

    <div class="form_body">
    <div class="loginform" id="logForm">
        <img src="images/x-modified.png" align="right" id="exit">
        <form method="POST" id="loginID" onsubmit="validate(event);">
        
            <div class="bar">
                <p>Login</p>
            </div>
            <div class="inputs">
                <label for="email">Email</label>
                <input class="email" type="email" name="email" required>
                <label for="password">Password</label>
                <input class="password" type="password" name="password" required>
                <p id="signup"><a>Don't have an account? Sign up here</a></p>
                <p class="error"> <?php echo $error; ?> </p>
                <button class="submit" type="button" name="submit" id="submitLogin">Submit</button> 
                
               
            </div>
           
        </form>
        
    </div>
    </div>

    <div class="form_body">
    <div class="signform" id="signForm">
        <img src="images/x-modified.png" align="right" id="exit2">
        <form method="POST">
            <div class="barSign">
                <p>Sign Up</p>
            </div>
            <div class="inputs">
                <label for="username">Username</label>
                <input type="text" class="username" id="username" name="username" required>
                <label for="email">Email</label>
                <input class="email" type="email" name="email_sign" required>
                <label for="password">Password</label>
                <input class="password" type="password" name="password_sign" required>

                <label for="password2">Confirm Password</label>
                <input class="password2" type="password" name="password2_sign" required>
                <p class="error_sign"><?php echo $error_signup?></p>
                <button class="submit" type="button" name="submit_sign" id="submitSign">Submit</button> 
            </div>
        </form>
    </div>

    </div>
    <script type="text/javascript" src="scripts/home.js"></script>
    

    <script type="text/javascript">
        var error;
        
        $(document).ready(function(){ 
            $('#submitSign').on('click', function(){ 
                
  	//first get the value of input fields.. 
  	            var email = $('input[name="email_sign"]').val(),
                user = $('input[name="username"]').val(), 
  	            pass = $('input[name="password_sign"]').val(),
                pass2 = $('input[name="password2_sign"]').val(); 

                if(email.length == 0 || user.length == 0 || pass.length == 0 || pass2.length == 0) {
                    error = "Missing Fields!";
                    $('.error_sign').text(error);
                    return;
                }
             
 
    //now use ajax to send the data from client system to server... 
                $.ajax({ 
    	            type: 'post', //specify the type of request GET Or POST  
    	            url: 'home.php', // specify the url where u gonna write php code to handle this ajax request or leave empty if same page... 
    	            data: {email_sign : email, username : user, password_sign : pass, password2_sign : pass2}, 
    	            success: function(data){ 
                       
                        
                        
                        error = data.substring(0, data.indexOf("~"));
                        error = error.trim();
                        
                        if(error.length !== 0) {
                            console.log(error.length);
                            $('.error_sign').text(error);
                        }
                        else {
                            console.log("HERE???");
                            window.location = 'tracker.php';
                        }
    		        
    	            }, 
                }); 
 
            }); 
        });
        
        $(document).ready(function() {
            $('#submitLogin').click(() => {
                var email = $('input[name="email"]').val(),
                password = $('input[name="password"]').val();

                if(email.length == 0 || password.length == 0) {
                    error = "Missing Fields";
                    $('.error').text(error);
                }

                $.ajax({
                    type:'post',
                    url: 'home.php',
                    data: {email: email, password : password},
                    success: function(data) {
                        error = data.substring(0, data.indexOf("~"));
                        error = error.trim();
                      
                        if(error.length !== 0) {
                            $('.error').text(error);
                        }
                        else {
                            
                            window.location = 'tracker.php';
                        }
                    }
                });
            })
        })
    </script>
   

        



    
</body>
</html>