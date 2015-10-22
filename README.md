# haravan_api_php
phong.nguyen 20151020 PhuNuVietNam 2015 
Haravan API for php 

# usage inside CodeIgniter app: 
- Put HaravanAPI inside folder **".../third_party/"**  
- Use classes by this LOC 
```php
require_once APPPATH."/third_party/HaravanAPI/autoload.php";    
```
- Get token  
. Please read docs: https://docs.shopify.com/api/authentication/oauth   
. View a demo app by Haravan:  https://github.com/Haravan/haravan_php_api   

- Get 1 product by id 

```ruby
$proHRV = new ProductHRV( 'your_haravan_store.myharavan.com', 'your_haravan_token', 'your_haravan_api_key', 'your_haravan_api_secret'); 
$product = $proHRV->get_one('1000459014');  
``` 
