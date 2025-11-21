<!-- Checkout Form Section -->
<section class="checkout-form" style="display:none;">
    <?php
    // Get user details from session
    $user_name = $_SESSION['user_name'] ?? 'Guest User';
    $user_email = $_SESSION['user_email'] ?? 'guest@guest.com';
    
    // Stripe publishable key
    $stripe_publishable_key = 'pk_test_51STfLcAksjEcZwsYPOGI0xqUKScqT1AS4GFHnubNNqd3e0YVWomPXk9cABvxKyuOc4yokyT8VtlvzXd6LkWHQiTG0003O6qTzj';
    ?>
    
    <section class="payment-section">
        <h2 class="section-title">Checkout</h2>
        
        <!-- Booking Summary -->
        <div class="checkout-summary mb-4">
            <h5 class="mb-3 fw-bold">Booking Details</h5>
            
            <div class="summary-row">
                <span class="summary-label">Name:</span>
                <span class="summary-value"><?php echo htmlspecialchars($user_name); ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Email:</span>
                <span class="summary-value"><?php echo htmlspecialchars($user_email); ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Room:</span>
                <span class="summary-value" id="checkout-room">--</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Date:</span>
                <span class="summary-value" id="checkout-date">--</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Time:</span>
                <span class="summary-value" id="checkout-time">--</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Players:</span>
                <span class="summary-value" id="checkout-players">--</span>
            </div>
            <div class="summary-row total-row">
                <span class="summary-label">Total:</span>
                <span class="summary-value">$<span id="checkout-total">0</span></span>
            </div>
        </div>
        
        <form id="payment-form">
            <!-- Billing Address -->
            <h5 class="mb-3 fw-bold">Billing Address</h5>
            
            <div class="mb-3">
                <label for="billing-address" class="form-label">Street Address</label>
                <input type="text" class="form-control" id="billing-address" 
                       placeholder="123 Main St" required>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="billing-city" class="form-label">City</label>
                    <input type="text" class="form-control" id="billing-city" 
                           placeholder="Singapore" required>
                </div>
                <div class="col-md-6">
                    <label for="billing-postal" class="form-label">Postal Code</label>
                    <input type="text" class="form-control" id="billing-postal" 
                           placeholder="123456" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="billing-country" class="form-label">Country</label>
                <select class="form-control" id="billing-country" required>
                    <option value="SG" selected>Singapore</option>
                    <option value="MY">Malaysia</option>
                </select>
            </div>
            
            <!-- Payment Method -->
            <h5 class="mb-3 fw-bold">Payment Method</h5>
            
            <!-- Apple Pay / Google Pay Button -->
            <div id="payment-request-button" class="payment-request-button">
                <!-- Stripe will inject Apple Pay/Google Pay button here if available -->
            </div>
            
            <div class="payment-divider" id="payment-divider" style="display: none;">
                <span>OR PAY WITH CARD</span>
            </div>
            
            <!-- Stripe Payment Element (Credit Card) -->
            <div id="payment-element" class="mb-3">
                <!-- Stripe.js injects the Payment Element here -->
            </div>
            
            <div id="payment-error-message" class="error-message" style="display: none;"></div>
            
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary flex-fill" id="back-to-booking-btn">
                    ← Back
                </button>
                <button type="submit" class="btn btn-primary flex-fill" id="payment-submit-button">
                    <span id="payment-button-text">Pay Now</span>
                    <span id="payment-spinner" class="payment-spinner" style="display: none;"></span>
                </button>
            </div>
            
            <div class="secure-badge mt-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Secured by Stripe
            </div>
        </form>
    </section>
    <script>
        // FIX: alert does not go away after booking has expired
        // Stripe configuration
        const stripeKey = '<?php echo $stripe_publishable_key; ?>';
        const stripe = Stripe(stripeKey);

        let elements;
        let paymentRequest;
        let clientSecret;

        // Initialize payment when checkout form is shown
        function initializePayment() {
            const amount = parseFloat($("#checkout-total").text()) * 100; // Stripe takes amt in cents

            if (amount <= 0) {
                showPaymentError('Invalid amount');
                return;
            }

            // Create Payment Intent
            fetch('api/create_payment_intent.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    amount: amount,
                    currency: 'sgd'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                clientSecret = data.clientSecret;
                setupStripeElements(clientSecret, amount);
            })
            .catch(error => {
                console.error('Error:', error);
                showPaymentError('Failed to initialize payment. Please try again.');
            });
        }

        function setupStripeElements(clientSecret, amount) {
            // Create Payment Element
            const appearance = {
                theme: 'stripe',
                variables: {
                    colorPrimary: '#A855F7',
                    colorBackground: '#ffffff',
                    colorText: '#1a1a1a',
                    borderRadius: '8px',
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif'
                }
            };

            elements = stripe.elements({ clientSecret, appearance });
            const paymentElement = elements.create('payment');
            paymentElement.mount('#payment-element');

            // Setup Apple Pay / Google Pay
            setupPaymentRequest(amount);
        }

        function setupPaymentRequest(amount) {
            paymentRequest = stripe.paymentRequest({
                country: 'SG',
                currency: 'sgd',
                total: {
                    label: 'Escape Room Booking',
                    amount: amount,
                },
                requestPayerName: true,
                requestPayerEmail: true,
            });

            const prButton = elements.create('paymentRequestButton', {
                paymentRequest: paymentRequest,
            });

            // Check if Apple Pay / Google Pay is available
            paymentRequest.canMakePayment().then(function(result) {
                if (result) {
                    prButton.mount('#payment-request-button');
                    document.getElementById('payment-divider').style.display = 'block';
                }
            });

            paymentRequest.on('paymentmethod', async (ev) => {
                const billingDetails = getBillingDetails();

                const {error: confirmError} = await stripe.confirmCardPayment(
                    clientSecret,
                    {
                        payment_method: ev.paymentMethod.id,
                        payment_method_options: {
                            card: {
                                billing_details: billingDetails
                            }
                        }
                    },
                    {handleActions: false}
                );

                if (confirmError) {
                    ev.complete('fail');
                    showPaymentError(confirmError.message);
                } else {
                    ev.complete('success');
                    handlePaymentSuccess();
                }
            });
        }

        // Handle form submission
        document.getElementById('payment-form').addEventListener('submit', async (event) => {
            event.preventDefault();

            // Validate billing address
            if (!validateBillingAddress()) {
                return;
            }

            setPaymentLoading(true);

            const billingDetails = getBillingDetails();

            const {error} = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    payment_method_data: {
                        billing_details: billingDetails
                    },
                    receipt_email: '<?php echo $user_email; ?>',
                },
                redirect: 'if_required'
            });

            if (error) {
                showPaymentError(error.message);
                setPaymentLoading(false);
            } else {
                handlePaymentSuccess();
            }
        });

        function getBillingDetails() {
            return {
                name: '<?php echo addslashes($user_name); ?>',
                email: '<?php echo addslashes($user_email); ?>',
                address: {
                    line1: document.getElementById('billing-address').value,
                    city: document.getElementById('billing-city').value,
                    postal_code: document.getElementById('billing-postal').value,
                    country: document.getElementById('billing-country').value
                }
            };
        }

        function validateBillingAddress() {
            const address = document.getElementById('billing-address').value.trim();
            const city = document.getElementById('billing-city').value.trim();
            const postal = document.getElementById('billing-postal').value.trim();

            if (!address || !city || !postal) {
                showPaymentError('Please fill in all billing address fields');
                return false;
            }
            return true;
        }

        function handlePaymentSuccess() {
            // Save booking to database
            const bookingData = {
                date: $("#checkout-date").text(),
                time: $("#checkout-time").text(),
                room: $("#checkout-room").text(),
                players: $("#checkout-players").text(),
                total: $("#checkout-total").text(),
//                 billing_address: document.getElementById('billing-address').value,
//                 billing_city: document.getElementById('billing-city').value,
//                 billing_postal: document.getElementById('billing-postal').value,
                // billing_country: document.getElementById('billing-country').value
            };

            $.ajax({
                type: 'POST',
                url: 'api/save_booking.php',
                data: bookingData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Redirect to success page or show success message
                        window.location.href = 'inc/booking_success.php';
                    } else {
                        showPaymentError('Payment processed but booking save failed. Please contact support.');
                    }
                },
                error: function() {
                    showPaymentError('Payment processed but booking save failed. Please contact support.');
                }
            });
        }

        function setPaymentLoading(isLoading) {
            const button = document.getElementById('payment-submit-button');
            const buttonText = document.getElementById('payment-button-text');
            const spinner = document.getElementById('payment-spinner');

            if (isLoading) {
                button.disabled = true;
                buttonText.style.display = 'none';
                spinner.style.display = 'inline-block';
            } else {
                button.disabled = false;
                buttonText.style.display = 'inline';
                spinner.style.display = 'none';
            }
        }

        function showPaymentError(message) {
            const errorDiv = document.getElementById('payment-error-message');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';

            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        }

        // Back button
        document.getElementById("back-to-booking-btn").addEventListener("click", function() {
            $(".checkout-form").hide(250);
            $(".booking-form").show(250);
            $(".timeslots-container").show(250);
        });
              
    </script>
</section>