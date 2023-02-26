<?php
require_once("connection.php");
$username = $_SESSION['username'];
$error_message = "";



$current = 0;
$total = 0;

$query = "SELECT Current, Total FROM funds WHERE Username='".$username."'";
$result = mysqli_query($mysqli, $query);
if(mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_row($result);
    $current = $row[0];
    $total = $row[1];
}


if(isset($_POST['submit'])) {
  

    $addFund = $_POST['funds'];
  
    $addFund = str_replace("$", "", $addFund);
    $addFund = str_replace(",", "", $addFund);

    

    
    if(!is_numeric($addFund)) {
        $error_message = "INVALID FUNDS ENTERED";
    }

    else if($addFund > 1000000000) {
        $error_message = "FUNDS EXCEED MAX AMOUNT";
    }

    else if($addFund <= 0) {
        $error_message = "FUNDS MUST BE >= $0";
    }

    else if($total >= 2147483647) {
        $error_message = "YOU CANNOT ADD ANY MORE FUNDS, PLEASE SELL/REMOVE ASSETS";
    }



   else {

        $query = "SELECT Current, Total FROM funds WHERE Username='".$username."'";
        $result = mysqli_query($mysqli, $query);
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_row($result);
            $current = $row[0] + $addFund;
            $total = $row[1] + $addFund;

           
          
            $query = "UPDATE funds SET Current='".$current."', Total='".$total."' WHERE Username = '".$username."'";
            mysqli_query($mysqli, $query);
              
            

          
        }

        else {
            $current = $addFund;
            $total = $addFund;
            $query = "INSERT INTO funds VALUES(DEFAULT, '".$username."', '".$addFund."', '".$addFund."')";
            mysqli_query($mysqli, $query);
            
        }
  
    }
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funds</title>
    <link rel="stylesheet" type="text/css" href="funds.css">
    <link rel="stylesheet" type="text/css" href="stars.css">
</head>
<body>
    <div>
        <div id="stars"></div>
        <div id="stars2"></div>
        <div id="stars3"></div>
    </div>
    <nav>
        <ul>
            <li><a href="tracker.php">Tracker</a></li>
            <li><a href="config.php">Configure</a></li>
        </ul>
    </nav>
    <form method="POST">
        <div>
            <input class="funds" name="funds" type="text" placeholder="ADD FUNDS">
            <span class="border"></span>

        </div>
            <p class="current" id="current"></p>
            <p id="total">Total Funds Added: 0</p>
           
        
            <p class="error"><?php echo $error_message ?></p>
        
            <input class="submit" type="submit" name="submit" placeholder="SUBMIT" onsubmit="formatCurrent()">

 
        <script type="text/javascript">

                if ( window.history.replaceState ) {
                    window.history.replaceState( null, null, window.location.href );
                }
        
                var current = format("<?php echo $current; ?>");
                var total = format("<?php echo $total ?>");
           

                function format(value) {
                    let count = 0;
                    let updated = "";
                    let dotIndex = value.length;
                    if(value.includes(".")) {
                        dotIndex = value.indexOf(".");
                    }
                    for(let i = value.length - 1; i>=0; i--) {
                        updated = value[i] + updated;
                        if(i >= dotIndex) {
                            continue;
                        }
                        if(count % 3 == 2 && i != 0) {
                            updated = "," + updated;
                        }
                        count ++;
                    }
                    
                    return "$" + updated;
                }

                
                const currentHtml = document.getElementById("current");
            
                currentHtml.innerHTML = "Current Funds: " + current;
                
                const totalHtml = document.getElementById("total");
                totalHtml.innerHTML = "Total Funds Added: " + total;

            
        
            


        </script>


    </form>
   

    
</body>
</html>