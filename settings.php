<?php 
    
    require('connection.php');
    $username = $_SESSION['username'];

    $query = "SELECT Email FROM users WHERE Username = '".$username."'";

    $result = mysqli_query($mysqli, $query);

    $email = mysqli_fetch_row($result)[0];

    $query = "SELECT Password FROM users WHERE Username = '".$username."'";

    $result = mysqli_query($mysqli, $query);

    $password = mysqli_fetch_row($result)[0];

    if(isset($_POST['newEmail'])) {
        $newEmail = $_POST['newEmail'];
        $query = "SELECT Email FROM users WHERE email='$newEmail'";
        
        $result = mysqli_query($mysqli, $query);
        $error = "";
        if(mysqli_num_rows($result) > 0) {
            $error = "Email already exists";
           
        }

        else if(!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email";
        }

        else {
            $query = "UPDATE users SET Email='$newEmail' WHERE Username='$username'";
            mysqli_query($mysqli, $query);
            
        }

        echo $error."~";
        
    }

    if(isset($_POST['newPassword'])) {
        $newPassword = $_POST['newPassword'];
        $error = "";
        if(strlen($newPassword) < 8) {
            $error = "Password too short";
        }
        else if($password == $newPassword) {
            $error = "Password in use";
        }
        else {
            $query = "UPDATE users SET Password='$newPassword' WHERE Username = '$username'";
            mysqli_query($mysqli, $query);
        }
        echo $error."~";
    }
  

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   
    <title>Settings</title>
    <link rel="stylesheet" href="settings.css">
    
</head>
<body>
    
    <div class="container">
        
        <div class="settings">
            <div class="validate-container">
                <p class="validatePrompt">ENTER PASSWORD:</p>
                <input class="validateInput" type="password" id="passwordValidate">
                <p id="incorrect"></p>
                <img onclick="validatePassword();" class="png" id="check0" src="images/check.png">
            </div>
            <p class="welcome"></p>
            <p class="found"></p>
            <p class="username"></p>
            
            <p class="email"></p>
            <p class="password"></p>
            <input type="hidden" id="pass" value="<?php echo $password ?>" />
            
            <form method="POST">
                <p class="prompt1"></p>
                <img onclick="approveEmail();" class="png" id="check" src="images/check.png">
                <img onclick="startPasswordPrompt();" class="png" id="deny" src="images/deny.png">
                
                <p class="emailPrompt">Enter new Email</p>
                <p class="arrow">-></p>
                <input class="emailInput" name="emailInput" type="text">
                <img class="other" src="images/check.png">
                <p class="emailError"></p>


                <p class="prompt2"></p>
                <img onclick="approvePassword();" class="png" id="check2" src="images/check.png">
                <img onclick="showExit();" class="png" id="deny2" src="images/deny.png">

                <p class="passwordPrompt">Enter new Password</p>
                <p class="arrow2">-></p>
                <input class="passwordInput" name="passwordInput" type="text">
                <img class="other2" src="images/check.png">
                <p class="passwordError"></p>
                <a href="tracker.php"><p id="exit">EXIT</p></a>
            </form>
        </div>
    </div>



    <script>
   



        let userText = "Username: " + <?php echo "'" .$username. "'"; ?>;
        let emailText = "Email: " + <?php echo "'" .$email. "'"; ?>;
       
    </script>

    <script src="scripts/settings.js"></script>
    
</body>


</html>