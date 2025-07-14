<?php
/*
Plugin Name: Movers Payment System
Description: Payment system for Movers services with Stripe integration
Version: 1.0
Author: ARAFAT RAHMAN
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('WB_PAYMENT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WB_PAYMENT_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once WB_PAYMENT_PLUGIN_DIR . 'includes/stripe-init.php';
require_once WB_PAYMENT_PLUGIN_DIR . 'includes/payment-functions.php';
require_once WB_PAYMENT_PLUGIN_DIR . 'includes/admin-settings.php';
require_once WB_PAYMENT_PLUGIN_DIR . 'includes/email-notifications.php';

// Enqueue scripts and styles
add_action('wp_enqueue_scripts', 'WB_PAYMENT_enqueue_scripts');
function WB_PAYMENT_enqueue_scripts() {
    if (is_page('make-a-payment')) {
        wp_enqueue_style('hm-payment-style', WB_PAYMENT_PLUGIN_URL . 'assets/css/payment-style.css');
        wp_enqueue_script('stripe-js', 'https://js.stripe.com/v3/', [], null, true);
        wp_enqueue_script('hm-payment-script', WB_PAYMENT_PLUGIN_URL . 'assets/js/payment-script.js', ['jquery', 'stripe-js'], '1.0', true);
        

        wp_localize_script('hm-payment-script', 'WB_PAYMENT_vars', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'stripe_pk' => get_option('hm_stripe_publishable_key'),
            'nonce' => wp_create_nonce('WB_PAYMENT_nonce'),
            'success_redirect' => get_permalink(get_page_by_path('payment-success'))
      ]);

    }
}

// Create shortcode for payment form
add_shortcode('hello_movers_payment', 'WB_PAYMENT_form_shortcode');
function WB_PAYMENT_form_shortcode() {
    ob_start();
    include WB_PAYMENT_PLUGIN_DIR . 'templates/payment-form.php';
    return ob_get_clean();
}

// Register activation hook
register_activation_hook(__FILE__, 'WB_PAYMENT_plugin_activate');
function WB_PAYMENT_plugin_activate() {
    // Create necessary pages if they don't exist
    if (!get_page_by_path('make-a-payment')) {
        wp_insert_post([
            'post_title' => 'Make a Payment',
            'post_name' => 'make-a-payment',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_content' => '[hello_movers_payment]'
        ]);
    }
    
    if (!get_page_by_path('payment-success')) {
        wp_insert_post([
            'post_title' => 'Payment Success',
            'post_name' => 'payment-success',
            'post_status' => 'publish',
            'post_type' => 'page'
        ]);
    }
}

// Add admin menu
add_action('admin_menu', 'WB_PAYMENT_admin_menu');
function WB_PAYMENT_admin_menu() {
    add_menu_page(
        'Hello Movers Payments',
        'Hello Movers Payments',
        'manage_options',
        'hello-movers-payments',
        'WB_PAYMENT_admin_page',
        'dashicons-money',
        30
    );
    
    add_submenu_page(
        'hello-movers-payments',
        'Settings',
        'Settings',
        'manage_options',
        'hello-movers-payments-settings',
        'WB_PAYMENT_settings_page'
    );
}



register_activation_hook(__FILE__, 'hm_create_payment_table');

function hm_create_payment_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'wb_payments';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        payment_id varchar(100) NOT NULL,
        amount decimal(10,2) NOT NULL,
        currency varchar(3) NOT NULL,
        customer_name varchar(100) NOT NULL,
        customer_email varchar(100) NOT NULL,
        customer_phone varchar(20) NOT NULL,
        services text NOT NULL,
        status varchar(20) NOT NULL,
        created_at datetime NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY payment_id (payment_id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
    // Add this for debugging
    if ($wpdb->last_error) {
        error_log('Database error: ' . $wpdb->last_error);
    }
}