<?php

        echo " <div>
        <div id=stars></div>
        <div id=stars></div>
        <div id=stars3></div></div>";

        $url = 'https://cointelegraph.com/rss/tag/altcoin';


        $headers = array(
        'http' => array(
            'method' => 'GET',
            'header' => "Content-Type: application/rss+xml\r\n" .
                        "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36\r\n"
        )
        );


        $context = stream_context_create($headers);


        $data = file_get_contents($url, false, $context);
        if(!$data) {
            echo "<h1>News API is Down</h1>";
        }

        else {
            echo "<h1>Latest News</h1>";
            header("Access-Control-Allow-Origin: *");

            $xml = simplexml_load_string($data);
            echo $xml;
           
            $count = 0;
            echo "<div class=article-box>";
            foreach ($xml->channel->item as $item) {
                if($count > 7) {
                    break;
                }
    
                if (isset($item->enclosure)) {
                    // The item has an enclosure element, which may contain an image URL
                    $imageUrl = (string) $item->enclosure['url'];
                
                   
                  }
              
                $title = $item->title;
                $link = $item->link;
                
                
                echo "<div class=individual-box>
                      <p>$title</p>
                      <a href=$link><img src='$imageUrl'></a>
                </div>";
                $count++;
            }
            echo "</div>";
        
        }
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stars.css">
    <title>Crypto News</title>
    <link rel="stylesheet" href="news.css">
    
</head>
<body>
    <div class="arrowLeft"><p class="arrow"><</p></div>
    <div class="arrowRight"><p class="arrow">></p></div>
    <script src="scripts/news.js"></script>

</body>
</html>
