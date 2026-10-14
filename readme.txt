=== CDF Donation Form with PayPal and Stripe ===
Contributors: razuahammad
Donate link: https://github.com/razuahammad55/cdf-donation-form-with-paypal-and-stripe
Tags: donation, paypal, stripe, fundraising, non-profit
Requires at least: 5.8
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A secure, modular WordPress donation plugin supporting Stripe Checkout and PayPal REST API for One-Time and Monthly Recurring donations.

== Description ==

CDF Donation Form with PayPal and Stripe is a lightweight, high-converting, and developer-friendly fundraising plugin designed specifically for non-profits, charities, religious organizations, and digital creators.

Accept secure payments directly on your website using **Stripe Checkout** (Credit/Debit Cards) and **PayPal REST API** without complex setup routines.

### Key Features
- **Dual Payment Gateways:** Seamlessly supports both Stripe Checkout and PayPal REST API.
- **Flexible Frequencies:** Handle single **One-Time** donations or automated **Monthly Recurring Subscriptions**.
- **Elementor-Style Custom Styling:** Modern admin UI allowing you to customize button colors, hover/active states, and global border radius using live color pickers with hex code support.
- **Built-In Analytics Dashboard:** Track total revenue, gateway comparisons (Stripe vs. PayPal), and subscription breakdowns right inside your WordPress dashboard.
- **Automated Receipts & Emails:** Instantly dispatch automated HTML thank-you emails to donors and notification summaries to site administrators.
- **Custom Post Type Tracking:** Every successful transaction is securely logged as a custom post type (`donation`) complete with metadata like serial numbers, gateway mode, and transaction IDs.

== External Services ==

This plugin connects to external third-party payment gateways to process secure financial donations:

1. **Stripe Checkout & API:** 
   - **What it is used for:** Processes secure one-time and recurring credit/debit card donations.
   - **Data sent:** Transmits donation amounts, currency, customer email, names, and checkout metadata via secure server-to-server HTTPS API calls when a user initiates checkout.
   - **Terms & Privacy:** [Stripe Terms of Service](https://stripe.com/legal/end-users) | [Stripe Privacy Policy](https://stripe.com/privacy).

2. **PayPal REST API & Subscriptions:**
   - **What it is used for:** Processes secure one-time payments and automated monthly recurring billing agreements.
   - **Data sent:** Transmits payer names, email addresses, order amounts, and return URLs via secure HTTPS requests.
   - **Terms & Privacy:** [PayPal Legal Agreements & Privacy](https://www.paypal.com/us/legalhub/home).

== Installation ==

1. Upload the plugin folder `cdf-donation-form-with-paypal-and-stripe` to the `/wp-content/plugins/` directory, or upload the ZIP package through the **Plugins > Add New** screen in WordPress.
2. Activate the plugin through the **Plugins** screen in WordPress.
3. Upon first activation, the plugin automatically redirects to the Forms setup page and creates required `/donation-success/` and `/donation-canceled/` pages.
4. Navigate to **Donations > Settings** to configure your environment mode and API keys.
5. Use the shortcode `[donation_form]` on any page, post, or Elementor widget to display your donation form.

== Frequently Asked Questions ==

= Do I need SSL / HTTPS installed on my website? =
Yes. Secure Sockets Layer (SSL) is strictly required by both Stripe and PayPal to process live financial transactions safely and maintain PCI compliance.

= Can I accept recurring monthly donations? =
Yes! Use the shortcode `[donation_form type="monthly"]` and ensure your PayPal Monthly Plan ID and Stripe Secret Key are properly configured under **Donations > Settings**.

= What shortcodes are available for use? =
- `[donation_form]` or `[donation_form type="one_time"]` – Renders the one-time donation form.
- `[donation_form type="monthly"]` – Renders the monthly recurring subscription form.
- `[donation_receipt]` – Renders the transaction summary receipt on your success page.

= How do I customize the form colors and styles? =
Go to **Donations > Style** in your WordPress admin dashboard. You can customize colors for Normal, Hover, and Active states across Amount Buttons, Gateway Cards, and Submit Buttons using live color pickers or hex codes, alongside global border radius controls.

= Where can I find my Stripe and PayPal API keys? =
- **Stripe:** Log into your Stripe Dashboard, go to *Developers > API Keys*, and copy your Publishable Key (`pk_...`) and Secret Key (`sk_...`).
- **PayPal:** Log into the PayPal Developer Portal, create a REST API app under Apps & Credentials, and copy your Client ID and Secret Key.

= Can I test transactions before going live? =
Yes! Under **Donations > Settings**, change your Environment Mode using the Test / Live toggle switch. This allows you to test checkout flows using sandbox API keys without making actual charges.

= Who do I contact for developer support or bug reports? =
For developer inquiries, custom extensions, or bug reports, you can reach out directly to the author at razuahammad55@gmail.com.

== Screenshots ==

1. screenshot-1.jpg – One-Time donation form rendered on the frontend with custom amount presets and PayPal/Stripe toggle.
2. screenshot-2.jpg – Monthly recurring donation form with dynamic subscription handling.
3. screenshot-3.jpg – Donor transaction receipt page displaying transaction details.
4. screenshot-4.jpg – Automated HTML email receipt dispatched directly to the donor.
5. screenshot-5.jpg – Admin email notification sent upon receiving a new donation.
6. screenshot-6.jpg – Admin Custom Post Type transaction list (`donation`) showing serial numbers, status modes, and amounts.
7. screenshot-7.jpg – Detailed view of individual donation payment metadata and gateway transaction IDs.
8. screenshot-8.jpg – Forms & shortcodes overview page with step-by-step setup guide.
9. screenshot-9.jpg – Built-in revenue analytics dashboard with gateway and frequency breakdowns.
10. screenshot-10.jpg – Elementor-style tabbed style configuration page with hex code and border radius controls.
11. screenshot-11.jpg – Email configuration page for admin notification and donor notification.
12. screenshot-12.jpg – Plugin settings page to configure PayPal and Stripe API keys for the donation form.

== Changelog ==

= 2.0.0 =
- Released Version 2.0.0 with 100% Plugin Check compliance and zero static analysis warnings.
- Implemented robust, independent dual-credential storage for Sandbox and Live environment API keys.
- Enhanced frontend CSS with strict scope isolation (`body .styled-donation-container`) ensuring complete independence from third-party theme interference.
- Fully refined first-time activation redirect logic and streamlined admin UI panels.

= 1.7.0 =
- Resolved all static analysis input sanitization warnings across options and custom settings.
- Restructured admin CSS into dedicated assets/css/admin-style.css and separated frontend CSS assets completely.

= 1.6.0 =
- Added strict one-time activation redirect check.
- Added caching environment nonce bypass toggle under Donations > Forms.

= 1.5.0 =
- Added Requires PHP: 7.4 header for compatibility.
- Fully documented 3rd-party external API services (Stripe & PayPal) in readme.txt for Guideline 6 compliance.

= 1.4.0 =
- Fix Text Domain issues.

= 1.3.0 =
- Updated plugin title to CDF Donation Form with PayPal and Stripe for Guideline 17 compliance.
- Added Elementor-style tabbed color controls with hex code support.

= 1.2.0 =
- Initial public release supporting Stripe Checkout and PayPal REST API.
