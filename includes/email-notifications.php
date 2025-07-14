<?php
// Send payment notifications
function hm_send_payment_notifications($paymentIntent) {
    $metadata = $paymentIntent->metadata;
    $amount = $paymentIntent->amount / 100;
    $services = json_decode($metadata->services, true);
    
    // Customer email
    $to = $metadata->customer_email;
    $subject = 'Your Payment Receipt - Hello Movers';
    
    $message = '<html><body>';
    $message .= '<h2>Thank you for your payment!</h2>';
    $message .= '<p>Hello ' . esc_html($metadata->customer_name) . ',</p>';
    $message .= '<p>We have received your payment of £' . number_format($amount, 2) . '.</p>';
    
    if (!empty($services)) {
        $message .= '<h3>Services Paid For:</h3>';
        $message .= '<ul>';
        foreach ($services as $service) {
            $message .= '<li>' . esc_html($service['name']) . ' - £' . number_format($service['price'], 2);
            if (isset($service['hours'])) {
                $message .= ' (' . $service['hours'] . ' hours)';
            }
            $message .= '</li>';
        }
        $message .= '</ul>';
    }
    
    $message .= '<p>Payment ID: ' . $paymentIntent->id . '</p>';
    $message .= '<p>If you have any questions, please contact us.</p>';
    $message .= '</body></html>';
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($to, $subject, $message, $headers);
    
    // Admin email
    $admin_email = get_option('admin_email');
    $admin_subject = 'New Payment Received - Hello Movers';
    
    $admin_message = '<html><body>';
    $admin_message .= '<h2>New Payment Received</h2>';
    $admin_message .= '<p>Customer: ' . esc_html($metadata->customer_name) . '</p>';
    $admin_message .= '<p>Email: ' . esc_html($metadata->customer_email) . '</p>';
    $admin_message .= '<p>Phone: ' . esc_html($metadata->customer_phone) . '</p>';
    $admin_message .= '<p>Amount: £' . number_format($amount, 2) . '</p>';
    
    if (!empty($services)) {
        $admin_message .= '<h3>Services:</h3>';
        $admin_message .= '<ul>';
        foreach ($services as $service) {
            $admin_message .= '<li>' . esc_html($service['name']) . ' - £' . number_format($service['price'], 2);
            if (isset($service['hours'])) {
                $admin_message .= ' (' . $service['hours'] . ' hours)';
            }
            $admin_message .= '</li>';
        }
        $admin_message .= '</ul>';
    }
    
    $admin_message .= '<p>Payment ID: ' . $paymentIntent->id . '</p>';
    $admin_message .= '<p>View in Stripe Dashboard: https://dashboard.stripe.com/payments/' . $paymentIntent->id . '</p>';
    $admin_message .= '</body></html>';
    
    wp_mail($admin_email, $admin_subject, $admin_message, $headers);
}

// Send payment failure notification
function hm_send_payment_failure_notification($paymentIntent) {
    $metadata = $paymentIntent->metadata;
    
    // Admin email
    $admin_email = get_option('admin_email');
    $subject = 'Payment Failed - Hello Movers';
    
    $message = '<html><body>';
    $message .= '<h2>Payment Failed</h2>';
    $message .= '<p>Customer: ' . esc_html($metadata->customer_name) . '</p>';
    $message .= '<p>Email: ' . esc_html($metadata->customer_email) . '</p>';
    $message .= '<p>Phone: ' . esc_html($metadata->customer_phone) . '</p>';
    $message .= '<p>Amount: £' . number_format($paymentIntent->amount / 100, 2) . '</p>';
    $message .= '<p>Payment ID: ' . $paymentIntent->id . '</p>';
    $message .= '<p>View in Stripe Dashboard: https://dashboard.stripe.com/payments/' . $paymentIntent->id . '</p>';
    $message .= '</body></html>';
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($admin_email, $subject, $message, $headers);
}