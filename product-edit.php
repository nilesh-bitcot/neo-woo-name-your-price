<?php 
// product-edit.php

/*
Add Custom Input fields on product edit page
*/
add_action( 'woocommerce_product_options_general_product_data', 'neo_wup_option_general_custom_field' );
function neo_wup_option_general_custom_field() {
    global $post;
    echo '<hr>';

    echo '<p>'. __('Set custom fee', 'woocommerce').'</p>';
    $args0 = array(
        'id'          => 'neo_custom_fee_enable',
        'desc'        => __( 'Enable', 'woocommerce' ),
        'desc_tip'    => __( 'Enable', 'woocommerce' ),
        'label'       => __( 'Enable Custom Fee', 'woocommerce' ),
        'value'       => get_post_meta( $post->ID, 'neo_custom_fee_enable', true ),
    );
    woocommerce_wp_checkbox( $args0 );

    $args1 = array(
        'id' => 'neo_custom_fee_max',
        'label' => __( 'Custom Fee Max Value', 'woocommerce' ),
        'class' => 'neo-custom-field',
        'desc_tip' => true,
        'description' => __( 'Enter maximum value in number.', 'woocommerce' ),
        'value'       => get_post_meta( $post->ID, 'neo_custom_fee_max', true ),
    );
    woocommerce_wp_text_input( $args1 );
    $args2 = array(
        'id' => 'neo_custom_fee_min',
        'label' => __( 'Custom Fee Min Value', 'woocommerce' ),
        'class' => 'neo-custom-field',
        'desc_tip' => true,
        'description' => __( 'Enter Minimum value in number.', 'woocommerce' ),
        'value'       => get_post_meta( $post->ID, 'neo_custom_fee_min', true ),
    );
    woocommerce_wp_text_input( $args2 );
}

/*
* Save custom data in product meta
*/

add_action( 'woocommerce_process_product_meta', 'neo_wup_save_custom_meta_data' );
function neo_wup_save_custom_meta_data( $post_id ) {
    // grab the custom SKU from $_POST
    $flag = isset( $_POST[ 'neo_custom_fee_enable' ] ) ? sanitize_text_field( $_POST[ 'neo_custom_fee_enable' ] ) : '';
    $max = isset( $_POST[ 'neo_custom_fee_max' ] ) ? sanitize_text_field( $_POST[ 'neo_custom_fee_max' ] ) : '';
    $min = isset( $_POST[ 'neo_custom_fee_min' ] ) ? sanitize_text_field( $_POST[ 'neo_custom_fee_min' ] ) : '';

    // grab the product
    $product = wc_get_product( $post_id );

    // save the custom SKU using WooCommerce built-in functions
    $product->update_meta_data( 'neo_custom_fee_enable', $flag );
    $product->update_meta_data( 'neo_custom_fee_max', $max );
    $product->update_meta_data( 'neo_custom_fee_min', $min );
    $product->save();
}

