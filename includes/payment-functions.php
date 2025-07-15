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
       hm_handle_payment_success($paymentIntent,$name, $email, $phone, $services);
        wp_send_json_success([
            'clientSecret' => $paymentIntent->client_secret
        ]);
        
    } catch (Exception $e) {
        wp_send_json_error([
            'message' => $e->getMessage()
        ]);
    }
}

function hm_handle_payment_success($paymentIntent,$name, $email, $phone, $services) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'wb_payments';
    
    $wpdb->insert($table_name, [
        'payment_id' => '',
        'amount' => '', // Convert from pence
        'currency' => 'GBP',
        'customer_name' => $name,
        'customer_email' => $email,
        'customer_phone' => $phone,
        'services' => $services ? json_encode($services) : '',
        'status' => 'approved',
        'created_at' => current_time('mysql')
    ]);
    
    // Send confirmation email
    hm_send_confirmation_email(
        $paymentIntent->metadata->customer_email,
        $paymentIntent
    );
}

function hm_send_confirmation_email($email, $paymentIntent) {
    $subject = 'Your Payment Receipt';
    $headers = ['Content-Type: text/html; charset=UTF-8'];
    
    $message = '<h2>Payment Confirmation</h2>';
    $message .= '<p>Thank you for your payment of ' . 
                htmlspecialchars($paymentIntent->currency) . ' ' . 
                number_format($paymentIntent->amount/100, 2) . '</p>';
    
    // Add services list if available
    if (!empty($paymentIntent->metadata->services)) {
        $services = json_decode($paymentIntent->metadata->services);
        $message .= '<h3>Services:</h3><ul>';
        foreach ($services as $service) {
            $message .= '<li>' . htmlspecialchars($service->name) . ' - ' . 
                       htmlspecialchars($service->price) . '</li>';
        }
        $message .= '</ul>';
    }
    
    wp_mail($email, $subject, $message, $headers);
}
// Handle failed payment
function hm_handle_payment_failure($paymentIntent) {
    $metadata = $paymentIntent->metadata;
    
    // Save payment to database
    global $wpdb;
    $table_name = $wpdb->prefix . 'wb_payments';
    
    $wpdb->insert($table_name, [
        'payment_id' => $paymentIntent->id,
        'amount' => $paymentIntent->amount / 100,
        'currency' => $paymentIntent->currency,
        'customer_name' => $metadata->customer_name,
        'customer_email' => $metadata->customer_email,
        'customer_phone' => $metadata->customer_phone,
        'services' => $metadata->services,
        'status' => 'failed',
        'created_at' => current_time('mysql')
    ]);
    
    // Send failure notification
    hm_send_payment_failure_notification($paymentIntent);
}

