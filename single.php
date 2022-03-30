<?php

add_action( 'woocommerce_before_add_to_cart_button', 'neo_wup_before_add_to_cart_field' );

function neo_wup_before_add_to_cart_field() {
	global $product;
	$neo_custom_enable = $product->get_meta('neo_custom_fee_enable');
	$max = $product->get_meta('neo_custom_fee_max');
	$min = $product->get_meta('neo_custom_fee_min');
	if($neo_custom_enable){
		echo '<div class="misha-before-add-to-cart-field">
			<label for="neo_custom_fee">'.apply_filters('neo_woo_user_price_product_input_label',WDT_FRONT_INPUT_LABEL).': </label>
			<input type="number" id="neo_custom_fee" name="neo_custom_fee" placeholder="'.$min.'-'.$max.'" max="'.esc_attr($max).'" min="'.esc_attr($min).'" autocomplete="off" required>
		</div>';		
	}

}

add_filter( 'woocommerce_add_cart_item_data', 'neo_wup_save_field_value_to_cart_data', 10, 3 );

function neo_wup_save_field_value_to_cart_data( $cart_item_data, $product_id, $variation_id ) {

	if ( !empty( $_POST['neo_custom_fee'] ) ) {
		$cart_item_data['neo_custom_fee'] = sanitize_text_field( $_POST['neo_custom_fee']);
	}

	return $cart_item_data;

}

add_filter( 'woocommerce_get_item_data', 'neo_wup_display_field', 10, 2 );

function neo_wup_display_field( $item_data, $cart_item ) {

	if ( !empty( $cart_item['neo_custom_fee'] ) ) {
		$item_data[] = array(
			'key'     => apply_filters('neo_woo_user_price_cart_item_meta_label',WDT_FRONT_AFTER_LABEL),
			'value'   => $cart_item['neo_custom_fee'],
			'display' => '',
		);
	}

	return $item_data;

}

add_action( 'woocommerce_checkout_create_order_line_item', 'neo_wup_add_order_item_meta', 10, 4 );

function neo_wup_add_order_item_meta( $item, $cart_item_key, $values, $order ) {

	if ( !empty( $values['neo_custom_fee'] ) ) {
		$item->add_meta_data( 'neo_custom_fee', $values['neo_custom_fee'] );
	}
}

/* Change label on admin edit order */
add_filter('woocommerce_order_item_display_meta_key', 'neo_wup_order_item_display_meta_key', 20, 2 );
function neo_wup_order_item_display_meta_key( $display_key, $meta ) {

    // Set user meta custom field as order item meta
    if( $meta->key === 'neo_custom_fee' && is_admin() )
        $display_key = __(apply_filters('neo_woo_user_price_cart_item_meta_label',WDT_FRONT_AFTER_LABEL), "woocommerce" );

    return $display_key;    
}

/* Change label on frontend and email */
add_filter('woocommerce_display_item_meta', 'neo_wup_woocommerce_display_item_meta', 20, 3 );
function neo_wup_woocommerce_display_item_meta( $html, $item, $args ) {

	$strings = array();
	$html    = '';
	$args    = wp_parse_args(
		$args,
		array(
			'before'       => '<ul class="wc-item-meta"><li>',
			'after'        => '</li></ul>',
			'separator'    => '</li><li>',
			'echo'         => true,
			'autop'        => false,
			'label_before' => '<strong class="wc-item-meta-label">',
			'label_after'  => ':</strong> ',
		)
	);

	foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
		$value = $args['autop'] ? wp_kses_post( $meta->display_value ) : wp_kses_post( make_clickable( trim( $meta->display_value ) ) );

		$strings[] = $args['label_before'] . wp_kses_post( ($meta->display_key != 'neo_custom_fee') ? $meta->display_key : apply_filters('neo_woo_user_price_cart_item_meta_label',WDT_FRONT_AFTER_LABEL) ) . $args['label_after'] . $value;
	}

	if ( $strings ) {
		$html = $args['before'] . implode( $args['separator'], $strings ) . $args['after'];
	}

    return $html;    
}


add_action( 'woocommerce_before_calculate_totals', 'neo_wup_recalculate_price' );

function neo_wup_recalculate_price( $cart_object ) {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
     
    foreach ( $cart_object->get_cart() as $hash => $value ) {
    	$product_id = ($value['variation_id'] > 0) ? $value['variation_id'] : $value['product_id'];
    	$product = wc_get_product($product_id);
    	$enable = $product->get_meta('neo_custom_fee_enable');
    	if( $enable == 'yes' ) {
        	// $value['quantity']
            $newprice = intval($value['neo_custom_fee']) + $value['data']->get_regular_price();
            if(isset($_REQUEST['cart'])){}
        	if( isset($_REQUEST['cart'][$hash]['neo_custom_fee']) ){
        		$custom_fee = $_REQUEST['cart'][$hash]['neo_custom_fee'];
        		
        		$cart_content = WC()->cart->cart_contents;
		        $cart_content[$hash]['neo_custom_fee'] = $custom_fee;
		        // $cart_item['neo_custom_fee'] = $custom_fee; 
		        WC()->cart->set_cart_contents($cart_content);

        		$newprice = $custom_fee + $value['data']->get_regular_price();
        	}

            $value['data']->set_price( $newprice );

        }

    }
}


add_filter('woocommerce_cart_item_price', 'neo_wup_woocommerce_cart_item_price', 10,3);
function neo_wup_woocommerce_cart_item_price($price, $cart_item, $cart_item_key){
	if( isset($cart_item['neo_custom_fee']) && intval($cart_item['neo_custom_fee']) > 0){
		$product_id = ($cart_item['variation_id'] > 0) ? $cart_item['variation_id'] : $cart_item['product_id'];
    	$_product = wc_get_product($product_id);
		return wc_price($_product->get_price());
	}
	return $price;
}





// add_filter('neo_woo_user_price_cart_column_text', function($text){
// 	return 'hello';
// }, 10,1);

// neo_woo_user_price_product_input_label