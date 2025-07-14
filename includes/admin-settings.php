<?php
// Admin settings page
function WB_PAYMENT_settings_page() {
    ?>
    <div class="wrap">
        <h1>Hello Movers Payment Settings</h1>
        
        <form method="post" action="options.php">
            <?php settings_fields('WB_PAYMENT_settings_group'); ?>
            <?php do_settings_sections('hello-movers-payments-settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register settings
add_action('admin_init', 'WB_PAYMENT_register_settings');
function WB_PAYMENT_register_settings() {
    register_setting('WB_PAYMENT_settings_group', 'hm_stripe_publishable_key');
    register_setting('WB_PAYMENT_settings_group', 'hm_stripe_secret_key');
    register_setting('WB_PAYMENT_settings_group', 'WB_PAYMENT_success_message');
    
    add_settings_section(
        'WB_PAYMENT_stripe_section',
        'Stripe Settings',
        'WB_PAYMENT_stripe_section_callback',
        'hello-movers-payments-settings'
    );
    
    add_settings_field(
        'hm_stripe_publishable_key',
        'Stripe Publishable Key',
        'hm_stripe_publishable_key_callback',
        'hello-movers-payments-settings',
        'WB_PAYMENT_stripe_section'
    );
    
    add_settings_field(
        'hm_stripe_secret_key',
        'Stripe Secret Key',
        'hm_stripe_secret_key_callback',
        'hello-movers-payments-settings',
        'WB_PAYMENT_stripe_section'
    );
    
    add_settings_section(
        'WB_PAYMENT_messages_section',
        'Messages',
        'WB_PAYMENT_messages_section_callback',
        'hello-movers-payments-settings'
    );
    
    add_settings_field(
        'WB_PAYMENT_success_message',
        'Payment Success Message',
        'WB_PAYMENT_success_message_callback',
        'hello-movers-payments-settings',
        'WB_PAYMENT_messages_section'
    );
}

function WB_PAYMENT_stripe_section_callback() {
    echo '<p>Enter your Stripe API keys below. You can find these in your Stripe Dashboard.</p>';
}

function hm_stripe_publishable_key_callback() {
    $value = get_option('hm_stripe_publishable_key');
    echo '<input type="text" name="hm_stripe_publishable_key" value="' . esc_attr($value) . '" class="regular-text">';
}

function hm_stripe_secret_key_callback() {
    $value = get_option('hm_stripe_secret_key');
    echo '<input type="password" name="hm_stripe_secret_key" value="' . esc_attr($value) . '" class="regular-text">';
}

function WB_PAYMENT_messages_section_callback() {
    echo '<p>Customize the messages shown to users.</p>';
}

function WB_PAYMENT_success_message_callback() {
    $value = get_option('WB_PAYMENT_success_message', 'Thank you for your payment! We will contact you shortly to confirm your booking.');
    echo '<textarea name="WB_PAYMENT_success_message" rows="5" class="large-text">' . esc_textarea($value) . '</textarea>';
}

// Admin page to view payments
function WB_PAYMENT_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'wb_payments';
    $payments = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
    
    ?>
    <div class="wrap">
        <h1>Hello Movers Payments</h1>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?php echo esc_html($payment->payment_id); ?></td>
                    <td>
                        <?php echo esc_html($payment->customer_name); ?><br>
                        <?php echo esc_html($payment->customer_email); ?><br>
                        <?php echo esc_html($payment->customer_phone); ?>
                    </td>
                    <td>£<?php echo number_format($payment->amount, 2); ?></td>
                    <td><?php echo esc_html(ucfirst($payment->status)); ?></td>
                    <td><?php echo date('d M Y H:i', strtotime($payment->created_at)); ?></td>
                    <td>
                        <a href="https://dashboard.stripe.com/payments/<?php echo esc_attr($payment->payment_id); ?>" target="_blank">View in Stripe</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}