Shopify Developer plugin:

In order to run this plugin, you need any php server to install "getJson.php" on.
In our app, in file "orders.js" you need to swap the address in "shopifyUrl" variable to new one.
In addition if you want to use your own Shopify data, you need to create Shopify Developer Account, generate your own API key and enter it wherever it is used.

Shopify API key appearances:
-Shopify plugin:        getJson.php >> variable "$Shopify_API_key"
-Mobile app:              orders.js >> variable "Shopify_API_key"
-Manual:       eShoppingManual.html >> variable "Shopify_API_key"

Both in our mobile app and installation manual, Shopify API key is used for generation of registration link, as every user needs to grant necessary permissions to the application through Shopify site. API key identifies our app.
