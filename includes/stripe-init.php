<?php
// Use correct path to the Stripe library
$stripe_path = plugin_dir_path(__FILE__) . '../stripe/init.php';

if (file_exists($stripe_path)) {
    require_once $stripe_path;
    \Stripe\Stripe::setApiKey(get_option('hm_stripe_secret_key'));
}
// Handle Stripe webhook
add_action('init', 'hm_stripe_webhook');
function hm_stripe_webhook() {
    if (isset($_GET['stripe_webhook']) && $_GET['stripe_webhook'] == '1') {
        $payload = @file_get_contents('php://input');
        $event = null;
        
        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch(\UnexpectedValueException $e) {
            http_response_code(400);
            exit();
        }

        print_r($event); // For debugging, remove in production
        exit;
        
        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                hm_handle_payment_success($paymentIntent);
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                hm_handle_payment_failure($paymentIntent);
                break;
            default:
                error_log('Received unknown event type ' . $event->type);
        }
        
        http_response_code(200);
        exit();
    }
}