    <?php 
     require_once('connection.php');
    $username = $_SESSION['username'];
   



    $query = "SELECT Current, Total FROM funds WHERE Username='".$username."'";

    $result = mysqli_query($mysqli, $query);

    $total = 0;
    $current = 0;

    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_row($result);
        $total = $row[1];
        $current = $row[0];
    }




    $previous_prices = "";



    $query = "SELECT * FROM holdings WHERE Username='".$username."' ORDER BY(Asset)";
    $result = mysqli_query($mysqli, $query);

    $holdings = array();
    $prices = array();

    $valid = true;

    if(mysqli_num_rows($result) > 0) {
        foreach($result as $row) {
            $a = "Asset";
            $t = "Type";

            $id = $row[$a];
            $type = $row[$t];
            
            error_reporting(E_ERROR | E_PARSE);


            if($type === "stock") {
                ini_set('max_execution_time', 300);
                $url ="https://api.twelvedata.com/price?symbol=$id&apikey=1e1c18c3d14043c0bc51562f4cf11f34";// path to your JSON file
                $data = file_get_contents($url, true);
                $priceInfo = json_decode($data);

                if(!property_exists($priceInfo, "price") || empty($priceInfo)) {
                    $price = "?";
                    $valid = false;
                }

                else {
                    $price = "price";
                    $price = $priceInfo->$price;
                }


            }

            else {
                    ini_set('max_execution_time', 10000);
                    $url ="https://api.coingecko.com/api/v3/simple/price?ids=$id&vs_currencies=usd";// path to your JSON file
                    $data = file_get_contents($url, true);
                    $priceInfo = json_decode($data);

                    if(empty($priceInfo) || !property_exists($priceInfo, $id)) {
                        $price = "?";
                        $valid = false;
                    }

                    else {
                        $price = $priceInfo->$id->usd;
                    }
            }

            array_push($prices, $price);
            
            array_push($holdings, $row);
            
        }

        if($valid) {
            
            $_SESSION['prices'] = $prices;
        }
        
    
        $previous_prices = $_SESSION['prices'];
        
    }


    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tracker</title>
        <link rel="stylesheet" type="text/css" href="tracker.css">
        <link rel="stylesheet" type="text/css" href="stars.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:100,400" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </head>
    <body>

    <div class="mainContainer">
       <div class="reset">
        <div>
            <div id="stars"></div>
            <div id="stars1"></div>
            <div id="stars2"></div>
        </div>
        <div class = "header">
            <h1>Hello <span><?php echo $username;?></span>,</h1>
        </div>
        <nav>
            <ul>
                <li><a href="config.php">Configure</a></li>
                <li><a href="funds.php">Add Funds</a></li>
                
                <li class="arrowRight" id="arrow">
                    <i class="arrow right">
                    </i>
                    <i class="arrow right">
                    </i>
                </li>

                <li class="arrowLeft" id="arrow2">
                    <i class="arrow left">
                    </i>
                    <i class="arrow left">
                    </i>    
                </li>

                </span>
                <li><div class="popup" id="popup">
                    <div class="items">
                        <a class="settings" href="settings.php"><img src="images/settings.png"></a>
                        <a class="question" href="faq.html"><img src="images/question_wo-modified.png"></a>
                        <a class="logout" href="home.php"><img src="images/logout.png"></a>
                    </div>
                </div></li>  
            </ul>
                
        </nav>

        <div class="total" id="total">
            <h1 id="total_header">$0</h1>
            <p id="percent"></p>
        </div>
    </div>

    <!--asset, type, price, quantity, chart, value -->
        <div class="table-container">
            <table class="table-asset">
                <tr>
                    <th>Asset</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Chart</th>
                    <th>Value</th>
                </tr>
            
                
                
            </table>
        </div>
        </div>

        <div class="usd-container">
            <h4>No Holdings Were Found...</h4>
            <h3><img src="images/wallet.png"><span class="current">Current USD = $0</span></h3>
        </div>
    </div>        
        <div class="tradingview-widget-container" id="containerID">
        <link rel="stylesheet" href="chart.css">

        <div id="widgetID" class="tradingview-widget-copyright"><a href="https://www.tradingview.com/symbols/AAPL/" rel="noopener" target="_blank"></a>
            
        </div>
        <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        
        
        <script type="text/javascript">

                function showChart(asset) {

                    
                var id = asset;
                
                switch(id) {
                    case "MSFT":
                        name = "Microsoft";
                        break;

                    case "GOOG":
                        name = "Google";
                        break;

                    case "amzn":
                        name = "Amazon";
                        break; 

                    case "bitcoin":
                        name = "Bitcoin";
                        id = "BTCUSD"
                        break;

                    case "ethereum": 
                        name = "Ethereum";
                        id = "ETHUSD";
                        break;

                    case "binancecoin":
                        name = "Binance Coin";
                        id = "BNBUSDT";
                        break;

                    case "ripple":
                        name = "XRP";
                        id = "XRPUSDT";
                        break;

                    case "cardano":
                        name = "ADA";
                        id = "ADAUSDT";
                        break;

                    case "matic-network":
                        name = "MATICUSDT";
                        id = "MATICUSDT";
                        break;

                
                    default:
                        name="Apple";
                        break;
                }
            
            
              

            let width = 700;
            let height = 400;
            let fontSize = "10";
            if(screen.width < 1200) {
                console.log("HERE");
                width = 280;
                height = 500;
                
            }
            new TradingView.MediumWidget(
            {
            "symbols": [
            [
                name,
                id
            ]
            ],
            "chartOnly": false,
            "width": width,
            "height": height,
            "locale": "en",
            "colorTheme": "dark",
            "autosize": false,
            "showVolume": false,
            "hideDateRanges": false,
            "scalePosition": "middle",
            "scaleMode": "Normal",
            "fontFamily": "-apple-system, BlinkMacSystemFont, Trebuchet MS, Roboto, Ubuntu, sans-serif",
            "fontSize": fontSize,
            "noTimeScale": false,
            "valuesTracking": "1",
            "chartType": "line",
            "container_id": "widgetID"
        }
            );

            $(".mainContainer").css("filter", "blur(2px)");
            $("#widgetID").css("display", "block");

            
        }

        
        
        
        </script>
    </div>
        

        <script type="text/javascript" src="scripts/tracker.js"></script>
        <script type="text/javascript">
            
            
        
            var holdings = <?php echo json_encode($holdings); ?>;
            var prices = <?php echo json_encode($prices); ?>;

            var totalFunds = <?php echo $total; ?>;
            var currentFunds = <?php echo $current; ?>;

            console.log(prices);
            if(prices.includes("?")) {
                var prices = <?php echo json_encode($previous_prices); ?>;
            }

            console.log(holdings);
            console.log(prices);
            let i = 0;
            var total = 0;

            for(let h of holdings) {

            
                let asset = h['Asset'];
                let png = "";
                switch(asset.toLowerCase()) {
                    case "aapl":
                        png = "apple.png";
                        break;

                    case "amzn":
                        png = "amazon.png";
                        break;

                    case "msft":
                        png = "microsoft.png";
                        break;

                    case "binancecoin":
                        png = "bnb.png";
                        break;

                    case "bitcoin":
                        png = "bitcoin.png";
                        break;

                    case "ethereum":
                        png = "eth.png";
                        break;

                    case "ripple":
                        png = "xrp.png";
                        break;

                    case "cardano":
                        png = "ada.png";
                        break;

                    case "matic-network":
                        png = "matic.png";
                        break;

                    default: 
                        png = "google.png";
                        break;
                }
                let type = h['Type'];
                let price = prices[i];

                let quantity = h['Quantity'];
                if(quantity == 0) {
                    i++;
                    continue;
                }

                let chart = "chart.png";
                let value = (price * quantity).toFixed(2); 

                total += Number(value);
                
                
                
                price = format(price.toString());
                value = format(value.toString());
        
                
                $(".table-asset").append("<tr><td class='asset'><img src=images/" + png + ">" + asset.toUpperCase() + "</td> <td>" + type + "</td><td>" + price + "</td><td>" + quantity + "</td><td><img id=chartReq onclick=showChart(" + "'" + asset + "'" + "); src=images/" + chart + "></td><td>" + value + "</td></tr>");
                $("h4").text("");
            
                $(".reset").click(() => {
                    $("#widgetID").css("display", "none");
                    $(".mainContainer").css("filter", "none");
                })

                i++;
            }


        

            if(currentFunds > 0) {
                $('.current').text("Current USD = $" + currentFunds);
            }
            total += currentFunds;
            total = total.toFixed(2);
            var counter = setInterval(update);
            var count = 0;

            displayPercentage(total, totalFunds)

            function displayPercentage(total, totalFunds) {
                if(total === 0 || totalFunds === 0) {
                    return 
                }

                if(total < totalFunds) {
                    let percent = (1-(total / totalFunds)) * 100;
                    percent = percent.toFixed(3);
                    $('#percent').text("-" + percent + "%");
                    $('#percent').css('color', 'red');

                }

                else {
                    let percent = ((total / totalFunds) - 1) * 100;
                    percent = percent.toFixed(3);
                    $('#percent').text('+' + percent + '%');
                    $('#percent').css('color', 'green');
                }

            



            }


            function update() {
                $('#total_header').text(format(count.toString()));
                

                if(count >= total) {
                    $('#total_header').text(format(total.toString()));
                    clearInterval(counter);
                    return;
                }

                if(total < 1000) {
                    count += 10;
                }

                else if(total >= 1000 && total < 10000) {
                    count += 25;
                    console.log("here");
                }

                else if(total >= 10000 && total < 100000) {
                    count += 100;
                    
                }

                else if(total >= 100000 && total < 1000000) {
                    count += 1000;
                    console.log("here");
                }

                else if(total >= 1000000 && total < 10000000) {
                    count += 10000;
                }

                else if(total >= 10000000 && total < 100000000) {
                    count += 100000;
                }

                else {
                    count += 500000;
                }
            }
            


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
        
        </script>
    </body>

    </html>