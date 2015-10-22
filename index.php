<?php
require __DIR__ . '/HaravanAPI/HaravanClient.php'; 
//pro  https://phongnd.myharavan.com/admin/api/auth/?api_key=538492d90e9be03a46b57919a16c9840
//pro  https://phongnd.myharavan.com/admin/app#/embed/538492d90e9be03a46b57919a16c9840 
// https://app.haravan.com/services/partners/apiclient#/detail/1000023239 
// array(4) { ["shop"]=> string(21) "phongnd.myharavan.com" ["timestamp"]=> string(10) "1432195146" ["signature"]=> string(64) "2470dba6ff96ef19b0113131c0364e7c7489860f8ac4da46e4bca399a1929108" ["code"]=> string(64) "764ed580f6cd4677ae798820454cbc57c1e066a9fee1400b8c6014a0ac0054d4" } 
// 
define("SHOPIFY_API_KEY", "538492d90e9be03a46b57919a16c9840");
define("SHOPIFY_SECRET", "7c23e7d22d15dfb607c7d22cfc82678d");
define("REDIRECT_URI", "http://localhost/haravan_app01");
define("SHOPIFY_SCOPE", "read_products write_products");
session_start();

    if (isset($_GET['code'])) { // if the code param has been sent to this page... we are in Step 2
        // Step 2: do a form POST to get the access token
        $haravanClient = new HaravanClient($_GET['shop'], "", SHOPIFY_API_KEY, SHOPIFY_SECRET);
        session_unset();

        // if(!$haravanClient->validateSignature($_GET)) die('Error: invalid signature.');
// var_dump($_GET);  
// die( "signature: " . $_GET['signature']); 


        // Now, request the token and store it in your session.
        $token =  $haravanClient->getAccessToken($_GET['code'], REDIRECT_URI);
        $_SESSION['token'] = $token;
        if ($_SESSION['token'] != '')
            $_SESSION['shop'] = $_GET['shop'];

        echo $token;

        header("Location: shop.php");
        exit;       
    }
    // if they posted the form with the shop name
    else if (isset($_POST['shop']) || isset($_GET['shop'])) {

        // Step 1: get the shopname from the user and redirect the user to the
        // haravan authorization page where they can choose to authorize this app
        $shop = isset($_POST['shop']) ? $_POST['shop'] : $_GET['shop'];
        $haravanClient = new HaravanClient($shop, "", SHOPIFY_API_KEY, SHOPIFY_SECRET);

        // if(!$haravanClient->validateSignature($_GET)) die('Error: invalid signature.');
        
        // redirect to authorize url
        header("Location: " . $haravanClient->getAuthorizeUrl(SHOPIFY_SCOPE, REDIRECT_URI));
        exit;
    }

    // first time to the page, show the form below
?>
    <p>Install this app in a shop to get access to its private admin data.</p> 

    <p style="padding-bottom: 1em;">
        <span class="hint">Don&rsquo;t have a shop to install your app in handy? <a href="https://app.haravan.com/services/partners/api_clients/test_shops">Create a test shop.</a></span>
    </p> 

    <form action="" method="post">
      <label for='shop'><strong>The URL of the Shop</strong> 
        <span class="hint">(enter it exactly like this: myshop.myharavan.com)</span> 
      </label> 
      <p> 
        <input id="shop" name="shop" size="45" type="text" value="" /> 
        <input name="commit" type="submit" value="Install" /> 
      </p> 
    </form>