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
            'amount' => $amount / 100,
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



