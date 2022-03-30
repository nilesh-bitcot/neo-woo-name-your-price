# neo-woo-name-your-price
Woocommerce adon : to give ability to a user to add custom fee for product
Means that custom fee amount will be added to the actual cost of the product.

# How to use
Go to product edit page and enable feature (in product general data tab).
Add maximum and minimum price
Thats it.

# Available filters
you can update some of the labels on front end using the following filters.

```php
add_filter('neo_woo_user_price_cart_column_text', function($text){
	return 'Cart column label';
}, 10,1);

add_filter('neo_woo_user_price_product_input_label', function($text){
	return 'Enter your custom fee';
}, 10,1);
```
