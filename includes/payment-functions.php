<?php
// Handle AJAX payment intent creation
add_action('wp_ajax_hm_create_payment_intent', 'hm_create_payment_intent');
add_action('wp_ajax_nopriv_hm_create_payment_intent', 'hm_create_payment_intent');
function hm_create_payment_intent() {
    check_ajax_referer('WB_PAYMENT_nonce', 'security');
    
    try {
        // Validate input
        $amount = intval($_POST['amount']);
        $email = sanitize_email($_POST['customer_email']);
        $name = sanitize_text_field($_POST['customer_name']);
        $phone = sanitize_text_field($_POST['customer_phone']);
        $services = isset($_POST['services']) ? $_POST['services'] : [];
        
        if ($amount < 50) {
            throw new Exception('Amount must be at least £0.50');
        }
        
        if (!is_email($email)) {
            throw new Exception('Please enter a valid email address');
        }
        
        // Create Payment Intent
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'gbp',
            'payment_method_types' => ['card'],
            'metadata' => [
                'customer_name' => $name,
                'customer_email' => $email,
                'customer_phone' => $phone,
                'services' => json_encode($services),
                'wp_user_id' => get_current_user_id()
            ]
        ]);

      
        wp_send_json_success([
            'clientSecret' => $paymentIntent->client_secret
        ]);
        
    } catch (Exception $e) {
        wp_send_json_error([
            'message' => $e->getMessage()
        ]);
    }
}

// Handle successful payment
add_action('wp_ajax_hm_handle_payment_success', 'hm_handle_payment_success');
add_action('wp_ajax_nopriv_hm_handle_payment_success', 'hm_handle_payment_success');

function hm_handle_payment_success() {
    

    check_ajax_referer('WB_PAYMENT_nonce', 'security');

    // Get POST data
    $payment_id = sanitize_text_field($_POST['payment_id']);
    $amount = intval($_POST['amount']);
    $currency = sanitize_text_field($_POST['currency']);
    $customer_name = sanitize_text_field($_POST['customer_name']);
    $customer_email = sanitize_email($_POST['customer_email']);
    $customer_phone = sanitize_text_field($_POST['customer_phone']);
    $services = isset($_POST['services']) ? wp_json_encode($_POST['services']) : '';
    $status = sanitize_text_field($_POST['status']);

    

    global $wpdb;
    $table_name = $wpdb->prefix . 'wb_payments';

    $wpdb->insert($table_name, [
        'payment_id' => $payment_id,
        'amount' => $amount / 100, // Convert from pence
        'currency' => $currency,
        'customer_name' => $customer_name,
        'customer_email' => $customer_email,
        'customer_phone' => $customer_phone,
        'services' => $services,
        'status' => $status,
        'created_at' => current_time('mysql')
    ]);

    // Send confirmation email
    hm_send_confirmation_email(
        $customer_name,
        $customer_phone,
        $customer_email,
        $payment_id,
        $amount,
        $currency,
        $services
    );
}

function hm_send_confirmation_email($customer_name, $customer_phone, $customer_email, $payment_id, $amount, $currency, $services) {
    $subject = 'Your Payment Receipt';
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    $message = '<h2>Payment Confirmation</h2>';
    $message .= '<p>Thank you for your payment of ' . htmlspecialchars(strtoupper($currency)) . ' ' . number_format($amount / 100, 2) . '</p>';
    $message .= '<p>Payment ID: ' . htmlspecialchars($payment_id) . '</p>';

    // Add services list if available
    if (!empty($services)) {
        $decoded_services = json_decode($services, true);
        if (is_array($decoded_services) && count($decoded_services) > 0) {
            $message .= '<h3>Services:</h3><ul>';
            foreach ($decoded_services as $service) {
                if (is_array($service)) {
                    $service_name = isset($service['name']) ? $service['name'] : '';
                    $service_price = isset($service['price']) ? $service['price'] : '';
                } else {
                    $service_name = $service;
                    $service_price = '';
                }
                $message .= '<li>' . htmlspecialchars($service_name);
                if ($service_price !== '') {
                    $message .= ' - ' . htmlspecialchars($service_price);
                }
                $message .= '</li>';
            }
            $message .= '</ul>';
        }
    }

    $message .= '<h3>Customer Details:</h3><ul>';
    $message .= '<li>Name: ' . htmlspecialchars($customer_name) . '</li>';
    $message .= '<li>Email: ' . htmlspecialchars($customer_email) . '</li>';
    $message .= '<li>Phone: ' . htmlspecialchars($customer_phone) . '</li>';
    $message .= '</ul>';

    $admin_email = get_option('admin_email');
    $to = array(
        'alldaymovingltd.co.uk@gmail.com',
        $customer_email,
    );

    wp_mail($to, $subject, $message, $headers);
}
