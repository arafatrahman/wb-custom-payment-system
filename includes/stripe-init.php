<?php
// Use correct path to the Stripe library
$stripe_path = plugin_dir_path(__FILE__) . '../stripe/init.php';

if (file_exists($stripe_path)) {
    require_once $stripe_path;
    \Stripe\Stripe::setApiKey(get_option('hm_stripe_secret_key'));
}
// Register webhook endpoint
add_action('rest_api_init', function() {
    register_rest_route('stripe/v1', '/webhook', [
        'methods' => 'POST',
        'callback' => 'hm_stripe_webhook_handler',
        'permission_callback' => '__return_true'
    ]);
});

// Webhook handler
function hm_stripe_webhook_handler(WP_REST_Request $request) {
    $payload = $request->get_body();
    $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
    $endpoint_secret = 'https://alldaymovingltd.co.uk/wp-json/stripe/v1/webhook'; // From Stripe dashboard
    
    try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
    } catch(Exception $e) {
        error_log('Stripe webhook error: ' . $e->getMessage());
        return new WP_REST_Response(['error' => 'Invalid signature'], 400);
    }
    
    switch ($event->type) {
        case 'payment_intent.succeeded':
            $paymentIntent = $event->data->object;
            hm_handle_payment_success(
                $paymentIntent,
                $paymentIntent->metadata->customer_name,
                $paymentIntent->metadata->customer_email,
                $paymentIntent->metadata->customer_phone,
                json_decode($paymentIntent->metadata->services)
            );
            break;
            
        case 'payment_intent.payment_failed':
            $paymentIntent = $event->data->object;
            hm_handle_payment_failure($paymentIntent);
            break;
    }
    
    return new WP_REST_Response(['success' => true], 200);
}

// Handle successful payment
function hm_handle_payment_success($paymentIntent, $name, $email, $phone, $services) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'wb_payments';
    
    $wpdb->insert($table_name, [
        'payment_id' => $paymentIntent->id,
        'amount' => $paymentIntent->amount / 100, // Convert from pence to pounds
        'currency' => strtoupper($paymentIntent->currency),
        'customer_name' => $name,
        'customer_email' => $email,
        'customer_phone' => $phone,
        'services' => $services ? json_encode($services) : '',
        'status' => 'approved',
        'created_at' => current_time('mysql')
    ]);
    
    // Send confirmation email
    hm_send_confirmation_email($email, $paymentIntent);
}

// Send payment confirmation email
function hm_send_confirmation_email($email, $paymentIntent) {
    $subject = 'Your Payment Receipt';
    $headers = ['Content-Type: text/html; charset=UTF-8'];
    
    $message = '<h2>Payment Confirmation</h2>';
    $message .= '<p>Thank you for your payment of ' . 
                htmlspecialchars(strtoupper($paymentIntent->currency)) . ' ' . 
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
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'wb_payments';
    
    $wpdb->insert($table_name, [
        'payment_id' => $paymentIntent->id,
        'amount' => $paymentIntent->amount / 100,
        'currency' => strtoupper($paymentIntent->currency),
        'customer_name' => $metadata->customer_name,
        'customer_email' => $metadata->customer_email,
        'customer_phone' => $metadata->customer_phone,
        'services' => $metadata->services,
        'status' => 'failed',
        'created_at' => current_time('mysql')
    ]);
    
    // Send failure notification (implementation depends on your needs)
    // hm_send_payment_failure_notification($paymentIntent);
}