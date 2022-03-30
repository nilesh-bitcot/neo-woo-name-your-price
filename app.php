<?php

add_action('admin_enqueue_scripts', 'neo_wup_admin_enqueue_scripts');

function neo_wup_admin_enqueue_scripts(){
	$assetsUrl = plugin_dir_url(__FILE__) . 'assets';

	wp_enqueue_style( 'pdo-main', $assetsUrl . '/css/cal.css' );
    
    wp_enqueue_script( 'pdo-script', $assetsUrl . '/js/main.js', array('jquery') );

    wp_localize_script( 'pdo-script', 'wdt_pdo_obj',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'is_admin' => is_admin() ) );
}

add_action('wp_enqueue_scripts', 'neo_wup_enqueue_scripts');
function neo_wup_enqueue_scripts(){
	$assetsUrl = plugin_dir_url(__FILE__) . 'assets';

    wp_enqueue_style( 'pdo-main', $assetsUrl . '/css/cal.css' );

	wp_enqueue_script( 'pdo-script', $assetsUrl . '/js/main.js', array('jquery', 'jquery-ui-datepicker'), time(), true
    );
}