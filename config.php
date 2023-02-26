<?php
    require_once("connection.php");
   
    include("input_validation.php");

    $username = $_SESSION['username'];
    
    
    
    $error_crypto = "";
    $success_crypto = "";
    
  
    
    $query = "SELECT Current FROM funds WHERE '".$username."' = Username";
    
    $result = mysqli_query($mysqli,$query);
    
    
    // CRYPTO ------------------------------------------------
    if(isset($_POST['submitCrypto'])) {
        $coin_id = $_POST["crypto"];
        $quantity = $_POST["quantity"];
        
        $modify = $_COOKIE["crypto_id"];
        
        
        $success_crypto = "";
       
        
        if(validateCryptos($coin_id, $quantity, $modify)) {
                if(mysqli_num_rows($result) > 0) { // checks if funds have been added
                    $row = mysqli_fetch_row($result); 
                    $current = $row[0];
                    
                    
                    ini_set('max_execution_time', 10000);
                    $url ="https://api.coingecko.com/api/v3/simple/price?ids=$coin_id&vs_currencies=usd";// path to your JSON file
                    $data = file_get_contents($url, true);
                    $priceInfo = json_decode($data);
                    if(empty($priceInfo) || !property_exists($priceInfo, $coin_id)) {
                        $error_crypto = "Calls have been exceeded! (Wait 1m)";
                    }


                    else {
                        $price = $priceInfo->$coin_id->usd; // pulls crypto price


                        

                        if($modify === "add") { // user has chosen to add the crypto to their portfolio
                            if(($price * $quantity) < $current) { // check if they own enough funds
                                $current -= $price * $quantity; // subtract the user's funds with the price * quantity of what they bought
                                // update the funds table with the new current funds
                                $query = "UPDATE funds SET Current = '".$current."' WHERE Username = '".$username."'"; 
                                mysqli_query($mysqli, $query);
                                

                                // this checks if they already hold the asset, if so then just update it with the added quantity
                                // otherwise just insert into the table
                                $query = "SELECT Quantity FROM holdings WHERE Username='".$username."' AND Asset='".$coin_id."'";
                                $result = mysqli_query($mysqli, $query);
                                if(mysqli_num_rows($result) > 0) {
                                    $row = mysqli_fetch_row($result);
                                    $quantity += $row[0];
                                    
                                    $query = "UPDATE holdings SET Quantity='".$quantity."' WHERE Username='".$username."' AND Asset='".$coin_id."'";
                                    mysqli_query($mysqli, $query);
                                }
                                else {
                                
                                    $query = "INSERT INTO holdings VALUES(DEFAULT, '".$username."', '".$coin_id."','crypto','".$quantity."')";
                                    mysqli_query($mysqli, $query);
                                    
                                
                                }
                                $success_crypto = "Updated Crypto Holdings!";
                            }

                            else {
                                $error_crypto = "You can not afford the selected asset - $quantity $coin_id";
                            }
                        }

                        else {
                            $query = "SELECT Quantity FROM holdings WHERE Username= '".$username."' AND Asset='".$coin_id."'";
                            $result = mysqli_query($mysqli, $query);
                            if(mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_row($result);
                                if($quantity <= $row[0]) {
                                    $current += $quantity * $price;
                                    $quantity = $row[0] - $quantity;
                                    $query = "UPDATE holdings SET Quantity='".$quantity."'WHERE Asset='".$coin_id."'";
                                    mysqli_query($mysqli, $query);

                                    $query = "UPDATE funds SET Current='".$current."' WHERE Username='".$username."'";
                                    mysqli_query($mysqli, $query);
                                    $success_crypto = "Updated Crypto Holdings!";
                                }
                                else {
                                    $error_crypto = "You do not own enough - $quantity > $row[0]";
                                }
                            }

                            else {
                                $error_crypto = "Can't sell what you don't have";
                            }

                        }
                    }
                    
                    
                    
                }
                else {
                    $error_crypto = "Please Add Funds";
                }
                
            
            
        }
    }

   


    // STOCKS ----------------------------------------------------------------------------------
    
    $error_stock = "";
    $success_stock = "";
    if(isset($_POST["submitStock"])) {
        $stock_id = $_POST["stock"];
        $quantity = $_POST["quantity"];
        
        $modify = $_COOKIE["stock_id"];
        
       
        
        if(validateStocks($stock_id, $quantity, $modify)) {
            
            
            ini_set('max_execution_time', 300); 
            $url ="https://api.twelvedata.com/price?symbol=$stock_id&apikey=1e1c18c3d14043c0bc51562f4cf11f34";// path to your JSON file
            $data = file_get_contents($url, true);
            $priceInfo = json_decode($data);
            if(!property_exists($priceInfo, "price") || empty($priceInfo)) {
                $error_stock="Calls have been exceeded! (Wait 1m)";
            }

            
            else {
                

                if(mysqli_num_rows($result) > 0) { // checks if funds have been added

                    $price = "price";
                    $price = $priceInfo->$price;
                    $row = mysqli_fetch_row($result);
                    $current = $row[0];

                    if($modify === "add") { // user has chosen to add the crypto to their portfolio
                        if(($price * $quantity) < $current) { // check if they own enough funds
                            $current -= $price * $quantity; // subtract the user's funds with the price * quantity of what they bought
                            // update the funds table with the new current funds
                            $query = "UPDATE funds SET Current = '".$current."' WHERE Username = '".$username."'"; 
                            mysqli_query($mysqli, $query);
                            
    
                            // this checks if they already hold the asset, if so then just update it with the added quantity
                            // otherwise just insert into the table
                            $query = "SELECT Quantity FROM holdings WHERE Username='".$username."' AND Asset='".$stock_id."'";
                            $result = mysqli_query($mysqli, $query);
                            if(mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_row($result);
                                $quantity += $row[0];
                                
                                $query = "UPDATE holdings SET Quantity='".$quantity."' WHERE Username='".$username."' AND Asset='".$stock_id."'";
                                mysqli_query($mysqli, $query);
                            }
                            else {
                             
                                $query = "INSERT INTO holdings VALUES(DEFAULT, '".$username."', '".$stock_id."','stock','".$quantity."')";
                                mysqli_query($mysqli, $query);
                                
                            
                            }
                            $success_stock = "Updated Stock Holdings!";
                        }
    
                        else {
                            $error_stock = "You can not afford the selected asset - $quantity $stock_id";
                        }
                    }
    
                    else {
                        $query = "SELECT Quantity FROM holdings WHERE Username= '".$username."' AND Asset='".$stock_id."'";
                        $result = mysqli_query($mysqli, $query);
                        if(mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_row($result);
                            if($quantity <= $row[0]) {
                                $current += $quantity * $price;
                                
                                $quantity = $row[0] - $quantity;
                                $query = "UPDATE holdings SET Quantity='".$quantity."'WHERE Asset='".$stock_id."'";
                                mysqli_query($mysqli, $query);
    
                                $query = "UPDATE funds SET Current='".$current."' WHERE Username='".$username."'";
                                mysqli_query($mysqli, $query);
                                $success_stock = "Updated Stock Holdings!";
                            }
                            else {
                                $error_stock = "You do not own enough - $quantity > $row[0]";
                            }
                        }
    
                        else {
                            $error_stock = "Can't sell what you don't have";
                        }
    
                    }
                    
                    
                    
                }
                else {
                    $error_stock = "Please Add Funds";
                }

                
            }
            
        }

        
        
    }
    
    ?>
    
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <script>
            document.cookie="stock_id=none";
            document.cookie="crypto_id=none";
        </script>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Configure Holdings</title>
        <link rel="stylesheet" type="text/css" href="stars.css"/>
        <link rel="stylesheet" type="text/css" href="config.css"/>
       
    </head>
        <body>
            <div>
                <div id="stars"></div>
                <div id="stars2"></div>
                <div id="stars3"></div>
            </div>
            <div class="container">
            
            
            <nav>
                <ul>
                    <li><a href="tracker.php">Tracker</a></li>
                    <li><a href="funds.php">Add Funds</a></li>
                </ul>
            </nav>
            <div class="options">
                <div class="box">
                <span>
                    <form method="POST">
                    <h1>Stock</h1>
                    <p>Choose a Stock</p>
                    <div class="dropdown" method="POST">
                        <input name="stock" type="text" class="textBox" placeholder="Select Stock" required readonly>
                        <div class="option">
                           <div onclick="setText('AAPL', 0)"><img class="stockImage" src="images/apple.png"></ion-icon>Apple</div>
                            <div onclick="setText('GOOG', 0)"><img class="stockImage" src="images/google.png">Google</div>
                            <div onclick="setText('MSFT', 0)"><img class="stockImage" src="images/microsoft.png">Microsoft</div>
                            <div onclick="setText('AMZN', 0)"><img class="stockImage" src="images/amazon.png">Amazon</div>
    
                        </div>
                    </div>
                    <p>Enter Quantity</p>
                    <input name="quantity" class="quantity" type="text" placeholder="Share Quantity (1-10^9)" required>
    
                    <p>Add or Remove Share?</p>
                    <img class="add"  type="image" src="images/add.png" onclick="addFocus(0)" > 
                    <img  class="remove" type= "image" src="images/remove.png"onclick="removeFocus(0)">
    
                    <div class="result_error"><?php echo $error_stock?><div class="result_success"><?php echo $success_stock ?></div></div>
                    <input class="submit" type="submit" name="submitStock">
                    </form>
                </span>
            </div>
    
    
                <div class="box2">
                    <span>
    
                    <form method="POST">
                    <h1>Crypto</h1>
                    <p>Choose a Crypto</p>
                    <div class="dropdown">
    
                        <input name="crypto" type="text" class="textBox" placeholder="Select Crypto" required readonly>
                        <div class="option">
                            <div onclick="setText('bitcoin', 1)"><img class="cryptoImage" src="images/bitcoin.png"></ion-icon>Bitcoin</div>
                            <div onclick="setText('ethereum', 1)"><img class="cryptoImage" src="images/eth.png">Ethereum</div>
                            <div onclick="setText('binancecoin', 1)"><img class="cryptoImage" src="images/bnb.png">Binance Coin</div>
                            <div onclick="setText('ripple', 1)"><img class="cryptoImage" src="images/xrp.png">XRP</div>
                            <div onclick="setText('cardano', 1)"><img class="cryptoImage" src="images/ada.png">ADA</div>
                            <div onclick="setText('matic-network', 1)"><img class="cryptoImage" src="images/matic.png">Matic</div>
                        </div>
                    </div>
                    <p>Enter Quantity</p>
                    <input name="quantity" class="quantity" type="text" placeholder="Crypto Quantity (1-10^9)" required>
    
                    <p>Add or Remove Coin?</p>
                    <img class="add"  type="image" src="images/add.png" onclick="addFocus(1)" >
                    <img  class="remove" type= "image" src="images/remove.png"onclick="removeFocus(1)">
                    <div class="result_error"><?php echo $error_crypto?><div class="result_success"><?php echo $success_crypto ?></div></div>
    
                    <input class="submit" type="submit" name="submitCrypto">
                    </form>
                    </span>
                </div>
            </div>
</div>
    
    <script type="text/javascript" src="scripts/config.js"></script>
    <script type="text/javascript">
                if ( window.history.replaceState ) {
                    window.history.replaceState( null, null, window.location.href );
                }
    </script>
    </body>
    </html>