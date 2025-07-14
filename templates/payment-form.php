<div class="hm-payment-widget">
    <h2>Make a Payment</h2>
    <p>Select services or enter a custom amount to pay</p>
    
    <div class="hm-tabs-header">
        <button class="hm-tab-btn active" data-tab="hm-services-tab">Services</button>
        <button class="hm-tab-btn" data-tab="hm-custom-tab">Custom Amount</button>
    </div>
    
    <form id="hm-payment-form">
        <!-- Services Tab -->
        <div id="hm-services-tab" class="hm-tab-content active">
            <div class="hm-service-selection">
                <h3>House Moving</h3>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="studio_flat" data-price="144">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Studio Flat</span>
                        <span class="hm-service-price">£144</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="1_bed_flat" data-price="210">
                    <div class="hm-service-info">
                        <span class="hm-service-name">1 Bed Flat</span>
                        <span class="hm-service-price">£210</span>
                    </div>
                </label>
                <!-- Add all other services similarly -->
                
                <h3>Man With A Van</h3>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="one_man_van" data-price="42" data-hourly="true">
                    <div class="hm-service-info">
                        <span class="hm-service-name">One Man & Van (per hour)</span>
                        <span class="hm-service-price">£42/hour</span>
                    </div>
                </label>
                <!-- Add all other services similarly -->
            </div>
            
            <!-- Hourly service controls -->
            <div id="hm-hourly-controls" style="display: none; margin-top: 20px;">
                <h4>Hourly Services</h4>
                <div id="hm-hourly-services-list"></div>
            </div>
        </div>
        
        <!-- Custom Amount Tab -->
        <div id="hm-custom-tab" class="hm-tab-content">
            <div class="hm-custom-amount">
                <label for="hm-custom-amount-input">Enter Amount (£):</label>
                <input type="number" id="hm-custom-amount-input" name="custom_amount" min="1" step="1" placeholder="Enter amount in GBP">
            </div>
        </div>
        
        <div class="hm-total-section">
            <div>Total Amount:</div>
            <div class="hm-total-amount">£0.00</div>
            <input type="hidden" id="hm-total-amount" name="total_amount" value="0">
        </div>
        
        <!-- Customer Information -->
        <div class="hm-customer-info">
            <h3>Customer Information</h3>
            <div class="hm-form-group">
                <label for="hm-customer-name">Full Name</label>
                <input type="text" id="hm-customer-name" name="customer_name" required>
            </div>
            <div class="hm-form-group">
                <label for="hm-customer-email">Email</label>
                <input type="email" id="hm-customer-email" name="customer_email" required>
            </div>
            <div class="hm-form-group">
                <label for="hm-customer-phone">Phone</label>
                <input type="tel" id="hm-customer-phone" name="customer_phone" required>
            </div>
        </div>
        
        <!-- Stripe Card Element -->
        <div class="hm-form-group">
            <label>Card Details</label>
            <div id="card-element"></div>
            <div id="card-errors" role="alert"></div>
        </div>
        
        <button type="submit" id="hm-pay-button" class="hm-pay-button" disabled>Pay Now</button>
        <div id="hm-error-message" class="hm-error"></div>
    </form>
</div>