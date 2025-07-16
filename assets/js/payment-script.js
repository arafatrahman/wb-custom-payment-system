jQuery(document).ready(function($) {
    // Initialize Stripe with publishable key
    const stripe = Stripe(WB_PAYMENT_vars.stripe_pk);
    const elements = stripe.elements();
    
    // Create and mount Card Element
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a'
            }
        }
    });
    cardElement.mount('#card-element');
    
    // Handle real-time validation errors
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    
    // Tab functionality
    $('.hm-tab-btn').on('click', function() {
        $('.hm-tab-btn').removeClass('active');
        $(this).addClass('active');
        const tabId = $(this).data('tab');
        $('.hm-tab-content').removeClass('active');
        $('#' + tabId).addClass('active');
        calculateTotal();
    });
    
    // Service selection and total calculation
    let totalAmount = 0;
    const hourlyServices = {};
    
    // Add event listeners for service checkboxes
    $('.hm-service-checkbox input[type="checkbox"]').on('change', function() {
        if ($(this).is(':checked')) {
            if ($(this).data('hourly') === true) {
                // For hourly services
                const serviceName = $(this).closest('.hm-service-checkbox').find('.hm-service-name').text();
                const servicePrice = parseFloat($(this).data('price'));
                const minHours = $(this).data('min-hours') ? parseInt($(this).data('min-hours')) : 1;
                
                const hourlyId = 'hourly-' + Date.now();
                $(this).data('hourly-id', hourlyId);
                
                hourlyServices[hourlyId] = {
                    element: this,
                    name: serviceName,
                    price: servicePrice,
                    hours: minHours,
                    minHours: minHours
                };
                
                updateHourlyServicesDisplay();
            }
        } else {
            // Remove hourly service if unchecked
            const hourlyId = $(this).data('hourly-id');
            if (hourlyId && hourlyServices[hourlyId]) {
                delete hourlyServices[hourlyId];
                updateHourlyServicesDisplay();
            }
        }
        calculateTotal();
    });
    
    // Custom amount input handler
    $('#hm-custom-amount-input').on('input', calculateTotal);
    
    // Update hourly services display
    function updateHourlyServicesDisplay() {
        const hourlyControls = $('#hm-hourly-controls');
        const hourlyServicesList = $('#hm-hourly-services-list');
        
        if (Object.keys(hourlyServices).length > 0) {
            hourlyControls.show();
            hourlyServicesList.empty();
            
            for (const [id, service] of Object.entries(hourlyServices)) {
                const serviceDiv = $(`
                    <div class="hm-hourly-service">
                        <div class="hm-hourly-service-header">
                            <strong>${service.name}</strong> (£${service.price}/hour)
                        </div>
                        <div class="hm-hourly-service-controls">
                            <label>Hours:</label>
                            <input type="number" class="hm-hourly-hours" value="${service.hours}" min="${service.minHours}">
                            <button class="hm-remove-hourly" data-id="${id}">Remove</button>
                        </div>
                    </div>
                `);
                
                hourlyServicesList.append(serviceDiv);
                
                // Hours input handler
                serviceDiv.find('.hm-hourly-hours').on('input', function() {
                    const hours = parseInt($(this).val()) || service.minHours;
                    hourlyServices[id].hours = Math.max(hours, service.minHours);
                    $(this).val(hourlyServices[id].hours);
                    calculateTotal();
                });
                
                // Remove button handler
                serviceDiv.find('.hm-remove-hourly').on('click', function() {
                    $(hourlyServices[id].element).prop('checked', false);
                    delete hourlyServices[id];
                    updateHourlyServicesDisplay();
                    calculateTotal();
                });
            }
        } else {
            hourlyControls.hide();
        }
    }
    
    // Calculate total amount
    function calculateTotal() {
        totalAmount = 0;
        
        // Check which tab is active
        const servicesTabActive = $('#hm-custom-tab').hasClass('active');
        
        if (servicesTabActive) {
             // Calculate from custom amount
            totalAmount = parseFloat($('#hm-custom-amount-input').val()) || 0;

        } else {

                        // Calculate from selected services
            $('.hm-service-checkbox input[type="checkbox"]:checked').each(function() {
                if (!$(this).data('hourly-id')) {
                    // Non-hourly services
                    totalAmount += parseFloat($(this).data('price'));
                }
            });
            
            // Add hourly services
            for (const service of Object.values(hourlyServices)) {
                totalAmount += service.price * service.hours;
            }
           
        }
        
        // Update display
        $('.hm-total-amount').text(`£${totalAmount.toFixed(2)}`);
        $('#hm-total-amount').val(totalAmount.toFixed(2));
        
        // Enable/disable pay button
        $('#hm-pay-button').prop('disabled', totalAmount <= 0);
    }
    
    // Handle form submission
    $('#hm-payment-form').on('submit', async function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $button = $form.find('#hm-pay-button');
        const $error = $('#hm-error-message');
        
        // Disable form during processing
        $button.prop('disabled', true).text('Processing...');
        $error.text('');
        
        // Validate customer information
        const customerName = $('#hm-customer-name').val().trim();
        const customerEmail = $('#hm-customer-email').val().trim();
        const customerPhone = $('#hm-customer-phone').val().trim();
        
        if (!customerName || !customerEmail || !customerPhone) {
            showError('Please fill in all customer information fields');
            $button.prop('disabled', false).text('Pay Now');
            return;
        }
        
        if (totalAmount <= 0) {
            showError('Please select services or enter an amount');
            $button.prop('disabled', false).text('Pay Now');
            return;
        }
        
        try {
            // 1. Create Payment Intent via AJAX
            const response = await $.ajax({
                url: WB_PAYMENT_vars.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'hm_create_payment_intent',
                    security: WB_PAYMENT_vars.nonce,
                    amount: Math.round(totalAmount * 100),
                    customer_name: customerName,
                    customer_email: customerEmail,
                    customer_phone: customerPhone,
                    services: getSelectedServices()
                }
            });
            
            if (!response.success) {
                throw new Error(response.data.message || 'Payment failed');
            }
            
            // 2. Confirm Payment with Stripe
            const { error, paymentIntent } = await stripe.confirmCardPayment(
                response.data.clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: customerName,
                            email: customerEmail,
                            phone: customerPhone
                        }
                    }
                }
            );
            
            if (error) {
                throw error;
            }
            
            if (paymentIntent.status === 'succeeded') {

                await $.ajax({
                    url: WB_PAYMENT_vars.ajax_url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'hm_handle_payment_success',
                        security: WB_PAYMENT_vars.nonce,
                        payment_id: paymentIntent.id,
                        amount: paymentIntent.amount,
                        currency: paymentIntent.currency,
                        customer_name: customerName,
                        customer_email: customerEmail,
                        customer_phone: customerPhone,
                        services: JSON.stringify(getSelectedServices()),
                        status: paymentIntent.status
                    }
                });

    // Format amount with currency symbol
    const amountFormatted = new Intl.NumberFormat('en-GB', {
        style: 'currency',
        currency: paymentIntent.currency
    }).format(paymentIntent.amount / 100);
    
    // Format timestamp
    const paymentDate = new Date(paymentIntent.created * 1000);
    const dateOptions = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit', 
        minute: '2-digit'
    };
    const formattedDate = paymentDate.toLocaleDateString('en-GB', dateOptions);
    
    // Get card details (if available)
    let cardDisplay = 'Card payment';
    if (paymentIntent.payment_method_details?.card) {
        const { brand, last4 } = paymentIntent.payment_method_details.card;
        cardDisplay = `${brand?.toUpperCase() || 'CARD'} •••• ${last4 || '****'}`;
    }
    
    // Remove payment widget completely
    $('.hm-payment-widget').hide();
    
    // Populate and show confirmation
    $('.payment-confirmation')
        .find('#amount-paid').text(amountFormatted).end()
        .find('#payment-method').text(cardDisplay).end()
        .find('#transaction-id').text(paymentIntent.id).end()
        .find('#payment-status').text(paymentIntent.status).end()
        .find('#date-time').text(formattedDate).end()
        .find('#currency').text(paymentIntent.currency.toUpperCase()).end()
        .find('#client-secret').text(paymentIntent.client_secret).end()
        .fadeIn(300);
    
    $('#download-receipt').on('click', function() {
    // Format date for receipt
    const paymentDate = new Date(paymentIntent.created * 1000);
    const formattedDate = paymentDate.toLocaleString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    // Get card details
    const cardBrand = paymentIntent.payment_method_details?.card?.brand || 'Card';
    const cardLast4 = paymentIntent.payment_method_details?.card?.last4 || '****';
    
    // Create receipt content
    const receiptContent = `
        All Day Moving  - Payment Receipt
        =============================
        
        Payment Details:
        - Amount: £${(paymentIntent.amount/100).toFixed(2)}
        - Currency: ${paymentIntent.currency.toUpperCase()}
        - Payment Method: ${cardBrand} •••• ${cardLast4}
        - Transaction ID: ${paymentIntent.id}
        - Date: ${formattedDate}
        - Status: ${paymentIntent.status}
        
        Customer Information:
        - Name: ${$('#hm-customer-name').val()}
        - Email: ${$('#hm-customer-email').val()}
        - Phone: ${$('#hm-customer-phone').val()}
        
        Thank you for your business!
        Contact: +44 7424 934025
        Website: alldaymovingltd.co.uk
    `;
    

    // Create download
    const blob = new Blob([receiptContent], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `HelloMovers-Receipt-${paymentIntent.id}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
});
}
            
        } catch (err) {
            showError(err.message);
            $button.prop('disabled', false).text('Pay Now');
            console.error('Payment error:', err);
        }
    });

    // Add this to your success handler

    
    // Get selected services data
    function getSelectedServices() {
        const services = [];
        
        $('.hm-service-checkbox input[type="checkbox"]:checked').each(function() {
            const service = {
                id: $(this).val(),
                name: $(this).closest('.hm-service-checkbox').find('.hm-service-name').text(),
                price: parseFloat($(this).data('price'))
            };
            
            if ($(this).data('hourly-id')) {
                service.hours = hourlyServices[$(this).data('hourly-id')].hours;
                service.type = 'hourly';
            }
            
            services.push(service);
        });
        
        return services;
    }
    
    // Show error message
    function showError(message) {
        $('#hm-error-message').text(message).show();
        setTimeout(() => {
            $('#hm-error-message').fadeOut();
        }, 5000);
    }
    
    // Initialize calculations
    calculateTotal();
});