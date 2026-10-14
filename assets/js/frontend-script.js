/**
 * Plugin Name: CDF Donation Form with PayPal and Stripe
 * Description: Frontend JavaScript for Version 2.0.0
 * Author: Razu Ahammad
 */

document.addEventListener('DOMContentLoaded', function () {
    const donationContainers = document.querySelectorAll('.styled-donation-container');

    donationContainers.forEach(container => {
        const amtButtons = container.querySelectorAll('.amt-btn');
        const customAmtInput = container.querySelector('.donation-amount-input');
        const gatewayRows = container.querySelectorAll('.gateway-row');

        // Preset Amount Button Clicks
        amtButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                
                amtButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                const val = this.getAttribute('data-val');
                if (customAmtInput && val) {
                    customAmtInput.value = val;
                }
            });
        });

        // Custom Amount Input Changes
        if (customAmtInput) {
            customAmtInput.addEventListener('input', function () {
                const currentVal = parseFloat(this.value);
                amtButtons.forEach(btn => {
                    const btnVal = parseFloat(btn.getAttribute('data-val'));
                    if (btnVal === currentVal) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
            });
        }

        // Gateway Selection Toggle
        gatewayRows.forEach(row => {
            const radioInput = row.querySelector('input[type="radio"]');

            row.addEventListener('click', function () {
                gatewayRows.forEach(r => r.classList.remove('active'));
                this.classList.add('active');

                if (radioInput) {
                    radioInput.checked = true;
                }
            });

            if (radioInput) {
                radioInput.addEventListener('change', function () {
                    gatewayRows.forEach(r => r.classList.remove('active'));
                    if (this.checked) {
                        row.classList.add('active');
                    }
                });
            }
        });
    });
});
