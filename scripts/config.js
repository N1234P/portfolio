function addFocus(index) {
    var add = document.getElementsByClassName("add")[index];
    var remove = document.getElementsByClassName("remove")[index];
    add.classList.add("highlight");
    remove.classList.remove("highlight");
    

    if(index == 1) {
        document.cookie="crypto_id=add";
    }
    else {
        document.cookie="stock_id=add";
        console.log(document.cookie);
    }
 
    return false;
       
}

function removeFocus(index) {
    var add = document.getElementsByClassName("add")[index];
    var remove = document.getElementsByClassName("remove")[index];
    remove.classList.add("highlight");
    add.classList.remove("highlight");

    if(index == 1) {
        document.cookie="crypto_id=remove";
    }
    else {
        document.cookie="stock_id=remove";
    }
    return false;
}


function setText(asset, index) {
    var text = document.getElementsByClassName("textBox")[index];
    text.value = asset;


};

var dropdownStock = document.getElementsByClassName("dropdown")[0];
var dropdownCrypto = document.getElementsByClassName("dropdown")[1];
var optionStock = document.getElementsByClassName("option")[0];
var optionCrypto = document.getElementsByClassName("option")[1];


    dropdownStock.addEventListener('click', function() {
        if(optionStock.style.display == 'none' || optionStock.style.display == "") {
            dropdownStock.classList.toggle('active');
            optionStock.style.display = 'block';
        }
        else {
            dropdownStock.classList.toggle('active');
            optionStock.style.display = 'none';
       }
    });

    
    dropdownCrypto.addEventListener('click', function() {
        if(optionCrypto.style.display == 'none' || optionCrypto.style.display == "") {
            dropdownCrypto.classList.toggle('active');
            optionCrypto.style.display = 'block';
        }
        else {
            dropdownCrypto.classList.toggle('active');
            optionCrypto.style.display = 'none';
       }
    });



    
