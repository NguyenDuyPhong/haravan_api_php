<?php

// echo "signature: " . $_GET['signature'];

require __DIR__ . '/HaravanAPI/HaravanClient.php'; 
define("SHOPIFY_API_KEY", "");
define("SHOPIFY_SECRET", "");
session_start();
    $sc = new HaravanClient($_SESSION['shop'], $_SESSION['token'], SHOPIFY_API_KEY, SHOPIFY_SECRET);

    try
    {
        // Get all products
        $products = $sc->call('GET', '/admin/shop.json?page=1', array());
        
        echo "shop: " . $_SESSION['shop'];
        echo "<br/>token: " . $_SESSION['token'];
        echo "<br/>";
        
        echo "<pre>";
        var_dump($products);
        echo "</pre>";
        

    }
    catch (HaravanApiException $e)
    {
        /* 
         $e->getMethod() -> http method (GET, POST, PUT, DELETE)
         $e->getPath() -> path of failing request
         $e->getResponseHeaders() -> actually response headers from failing request
         $e->getResponse() -> curl response object
         $e->getParams() -> optional data that may have been passed that caused the failure

        */
    }
    
?>