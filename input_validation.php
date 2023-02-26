<?php
function validateCryptos($arg1, $arg2, $arg3) {
        
    global $error_crypto;
    
    if($arg1 === "") {
        $error_crypto="Choose a Coin!";
        return false;
    }
    
    else if($arg2 <= 0 || $arg2 >= 1000000000 || !is_numeric($arg2)) {
        $error_crypto="Quantity is invalid";
        return false;
    }
    
    
    else if($arg3 === "none") {
        $error_crypto="Add/Remove unselected!";
        return false;
    }
  
    return true;

}

function validateStocks($arg1, $arg2, $arg3) {
    global $error_stock;
    if($arg1 === "") {
        $error_stock = "Choose a Stock!";
        return false;
    }
    
    else if($arg2 <= 0 || $arg2 >= 1000000 || !is_numeric($arg2)) {
        $error_stock = "Quantity is invalid";
        return false;
    }
    
    else if($arg3 === "none") {
        $error_stock = "Add/Remove unselected!";
        return false;
    }
    return true;
}

?>