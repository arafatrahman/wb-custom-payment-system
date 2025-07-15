<div class="hm-payment-widget">
    <h2>Select services or enter a custom amount to pay</h2>
    
    <div class="hm-tabs-header">
        <button class="hm-tab-btn active" data-tab="hm-custom-tab">Custom Amount</button>
        <button class="hm-tab-btn " data-tab="hm-house-moving-tab">House Moving</button>
        <button class="hm-tab-btn " data-tab="hm-man-van-tab">Man & Van</button>
         <button class="hm-tab-btn " data-tab="hm-handyman-tab">Handyman</button>
          <button class="hm-tab-btn " data-tab="hm-cr-tab">Clearance & Rubbish Removal</button>
          <button class="hm-tab-btn " data-tab="hm-extra-item-tab">Extra Items</button>
            <button class="hm-tab-btn " data-tab="hm-cleaning-tab">Cleaning Services</button>
    </div>
    
    <form id="hm-payment-form">

        <!-- Custom Amount Tab -->
        <div id="hm-custom-tab" class="hm-tab-content active">
            <div class="hm-custom-amount">
                <label for="hm-custom-amount-input">Enter Amount (£):</label>
                <input type="number" id="hm-custom-amount-input" name="custom_amount" min="1" step="1" placeholder="Enter amount in GBP">
            </div>
        </div>
        <!-- Services Tabs -->
<div id="hm-services-tabs">
    
        <div id="hm-house-moving-tab" class="hm-tab-content">
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
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="2_bed_house" data-price="330">
                    <div class="hm-service-info">
                        <span class="hm-service-name">2 Bed Flat/House</span>
                        <span class="hm-service-price">£330</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="3_bed_house" data-price="490">
                    <div class="hm-service-info">
                        <span class="hm-service-name">3 Bed House</span>
                        <span class="hm-service-price">£490</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="4_bed_house" data-price="620">
                    <div class="hm-service-info">
                        <span class="hm-service-name">4 Bed House</span>
                        <span class="hm-service-price">£620</span>
                    </div>
                </label>
                
        </div>

        <div id="hm-man-van-tab" class="hm-tab-content">

                        <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="one_man_van" data-price="42" data-hourly="true">
                    <div class="hm-service-info">
                        <span class="hm-service-name">One Man & Van (per hour)</span>
                        <span class="hm-service-price">£42/hour</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="two_men_van" data-price="60" data-hourly="true">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Two Men & Van (per hour)</span>
                        <span class="hm-service-price">£60/hour</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="three_men_van" data-price="77" data-hourly="true">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Three Men & Van (per hour)</span>
                        <span class="hm-service-price">£77/hour</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="daily_one_man" data-price="340">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Daily rate (one man)</span>
                        <span class="hm-service-price">£340</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="daily_two_men" data-price="480">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Daily rate (two men)</span>
                        <span class="hm-service-price">£480</span>
                    </div>
                </label>
            
        </div>

        <div id="hm-handyman-tab" class="hm-tab-content">

                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="handyman_hourly" data-price="48" data-hourly="true" data-min-hours="2">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Handyman (per hour, min 2 hours)</span>
                        <span class="hm-service-price">£48/hour</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="handyman_half_day" data-price="160">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Half Day (4 hrs)</span>
                        <span class="hm-service-price">£160</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="handyman_full_day" data-price="320">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Full Day (8 hrs)</span>
                        <span class="hm-service-price">£320</span>
                    </div>
                </label>            
            
        </div>

        <div id="hm-cr-tab" class="hm-tab-content">
            
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="1_cubic_yard" data-price="130">
                    <div class="hm-service-info">
                        <span class="hm-service-name">1 Cubic Yard</span>
                        <span class="hm-service-price">£130</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="2_cubic_yards" data-price="150">
                    <div class="hm-service-info">
                        <span class="hm-service-name">2 Cubic Yards</span>
                        <span class="hm-service-price">£150</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="5_cubic_yards" data-price="180">
                    <div class="hm-service-info">
                        <span class="hm-service-name">5 Cubic Yards</span>
                        <span class="hm-service-price">£180</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="10_cubic_yards" data-price="330">
                    <div class="hm-service-info">
                        <span class="hm-service-name">10 Cubic Yards</span>
                        <span class="hm-service-price">£330</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="15_cubic_yards" data-price="450">
                    <div class="hm-service-info">
                        <span class="hm-service-name">15 Cubic Yards</span>
                        <span class="hm-service-price">£450</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="20_cubic_yards" data-price="590">
                    <div class="hm-service-info">
                        <span class="hm-service-name">20 Cubic Yards</span>
                        <span class="hm-service-price">£590</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="rubble" data-price="425">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Rubble (per 750 kg)</span>
                        <span class="hm-service-price">£425</span>
                    </div>
                </label>


        </div>

        <div id="hm-extra-item-tab" class="hm-tab-content">
            <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="mattress" data-price="35">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Mattress</span>
                        <span class="hm-service-price">£35</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="tv_monitor" data-price="15">
                    <div class="hm-service-info">
                        <span class="hm-service-name">TVs & Monitors</span>
                        <span class="hm-service-price">£15</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="car_battery" data-price="19">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Car Battery</span>
                        <span class="hm-service-price">£19</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="fluorescent_tube" data-price="8">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Fluorescent Tube</span>
                        <span class="hm-service-price">£8</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="paint_can" data-price="8">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Paint Cans (each)</span>
                        <span class="hm-service-price">£8</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="commercial_fridge" data-price="230">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Commercial Fridge/Freezer</span>
                        <span class="hm-service-price">£230</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="tall_fridge" data-price="70">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Tall Fridge</span>
                        <span class="hm-service-price">£70</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="undercounter_fridge" data-price="70">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Under-counter Fridge</span>
                        <span class="hm-service-price">£70</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="gas_canister" data-price="9">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Gas Canisters</span>
                        <span class="hm-service-price">£9</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="fire_extinguisher" data-price="55">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Fire Extinguishers</span>
                        <span class="hm-service-price">£55</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="piano" data-price="265">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Pianos</span>
                        <span class="hm-service-price">£265</span>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="tyre" data-price="9">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Tyres (each)</span>
                        <span class="hm-service-price">£9</span>
                    </div>
                </label>
                
        </div>
        
              
        <div id="hm-cleaning-tab" class="hm-tab-content ">

                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="studio_flat_cleaning" data-price="170">
                    <div class="hm-service-info">
                        <span class="hm-service-name">Studio Flat Cleaning</span>
                        <span class="hm-service-price">£170</span>
                        <div class="hm-service-desc">Includes 3 appliances and 2 deep carpeted areas</div>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="1_bed_flat_cleaning" data-price="220">
                    <div class="hm-service-info">
                        <span class="hm-service-name">1 Bed Flat Cleaning</span>
                        <span class="hm-service-price">£220</span>
                        <div class="hm-service-desc">Includes 3 appliances and 2 deep carpeted areas</div>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="2_bed_house_cleaning" data-price="240">
                    <div class="hm-service-info">
                        <span class="hm-service-name">2 Bed Flat/House Cleaning</span>
                        <span class="hm-service-price">£240</span>
                        <div class="hm-service-desc">Includes 3 appliances and 3 deep carpeted areas</div>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="3_bed_house_cleaning" data-price="300">
                    <div class="hm-service-info">
                        <span class="hm-service-name">3 Bed House Cleaning</span>
                        <span class="hm-service-price">£300</span>
                        <div class="hm-service-desc">Includes 3 appliances and 3 deep carpeted areas</div>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="4_bed_house_cleaning" data-price="400">
                    <div class="hm-service-info">
                        <span class="hm-service-name">4 Bed House Cleaning</span>
                        <span class="hm-service-price">£400</span>
                        <div class="hm-service-desc">Includes 4 appliances and 4 deep carpeted areas</div>
                    </div>
                </label>
                <label class="hm-service-checkbox">
                    <input type="checkbox" name="services[]" value="5_bed_house_cleaning" data-price="520">
                    <div class="hm-service-info">
                        <span class="hm-service-name">5 Bed House Cleaning</span>
                        <span class="hm-service-price">£520</span>
                        <div class="hm-service-desc">Includes 5 appliances and 5 deep carpeted areas</div>
                    </div>
                </label>
          
            
            <!-- Hourly service controls -->
            <div id="hm-hourly-controls" style="display: none; margin-top: 20px;">
                <h4>Hourly Services</h4>
                <div id="hm-hourly-services-list"></div>
            </div>
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


<div class="payment-confirmation" style="display: none;">
    <div class="confirmation-header">
        <svg class="confirmation-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#4CAF50">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
        </svg>
        <h2>Payment Successful</h2>
        <p class="confirmation-subtext">Your transaction has been completed</p>
    </div>
    
    <div class="confirmation-details">
        <div class="detail-row">
            <span class="detail-label">Amount Paid:</span>
            <span id="amount-paid" class="detail-value">£0.00</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Payment Method:</span>
            <span id="payment-card" class="detail-value">CARD •••• ****</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Transaction ID:</span>
            <span id="transaction-id" class="detail-value">Loading...</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Date & Time:</span>
            <span id="date-time" class="detail-value">Loading...</span>
        </div>
    </div>
    
    <div class="confirmation-actions">
        <button id="download-receipt" class="confirmation-btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 9H15V3H9V9H5L12 16L19 9Z" fill="currentColor"/>
                <path d="M19 20H5V18H19V20Z" fill="currentColor"/>
            </svg>
            Download Receipt
        </button>
        <button class="confirmation-btn btn-secondary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 20V14H14V20H19V12H22L12 3L2 12H5V20H10Z" fill="currentColor"/>
            </svg>
            <a href="https://alldaymovingltd.co.uk/">
                Go To Home
            </a>
        </button>
    </div>
    
    <div class="confirmation-footer">
        <p>Need help? <a href="tel:+447424934025">Contact our support team</a></p>
    </div>
</div>