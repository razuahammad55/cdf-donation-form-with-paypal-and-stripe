# CDF Donation Form with PayPal and Stripe

[![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-blue.svg)](https://wordpress.org)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B%20%7C%208.x-777BB4.svg)](https://www.php.net)
[![License: GPL v2+](https://img.shields.io/badge/License-GPL%20v2%2B-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

A secure, lightweight, and modular WordPress fundraising plugin that integrates **Stripe Checkout** and **PayPal REST API** for seamless **One-Time** and **Monthly Recurring** donations. Designed with an Elementor-style style editor, dynamic CSS variables, built-in revenue analytics, and automated donor receipt notifications.

---

## 🌟 Key Features

* **Dual Gateway Integration:** Complete support for Stripe Checkout (Credit/Debit Cards) and PayPal REST API.
* **Flexible Donation Frequencies:** Easily handle single **One-Time** donations and **Monthly Recurring Subscriptions**.
* **Elementor-Style Custom Styling:** Modern tabbed control panel (**Normal**, **Hover**, **Active**) for Amount Buttons, Gateway Cards, and Submit Buttons with Hex code inputs and global border-radius geometry.
* **Built-in Revenue Analytics:** Dedicated reporting dashboard tracking total volume, gateway revenue splits (Stripe vs. PayPal), and frequency breakdowns.
* **Automated Receipt & Email Notifications:** Automated HTML thank-you emails sent to donors and notification summaries sent to administrators.
* **Custom Post Type Tracking (`donation`):** Every successful transaction is logged with unique auto-increment serial numbers, mode badges, and transaction IDs.
* **Developer Friendly:** Completely modular codebase utilizing strict `cdf_` prefixes, WordPress nonces, and sanitized metadata handlers.

---

## 📂 File & Directory Architecture

```text
cdf-donation-form-paypal-stripe/
│
├── cdf-donation-form-paypal-stripe.php    # Main Plugin File, Activation Hooks & Dynamic CSS Generator
├── readme.txt                          # Official WordPress.org Repository Metadata
├── README.md                           # GitHub Project Documentation
│
├── includes/
│   ├── class-cpt.php                   # Registers 'donation' Custom Post Type & Admin Columns
│   ├── class-settings.php              # Sub-menus: Forms, Analytics, Style, Email & Settings
│   ├── class-logger.php                # Payment Routing, Webhook Verifications & Database Logging
│   ├── class-emails.php                # Admin Notifications & Donor Receipt Email Logic
│   └── class-shortcodes.php            # [donation_form] & [donation_receipt] Shortcodes
│
└── assets/
    ├── css/
    │   ├── admin-style.css             # Admin Dashboard & Control Panel Styles
    │   └── frontend-style.css          # Frontend Form CSS & Dynamic Variable Injection
    └── js/
        └── frontend-script.js          # Interactive Amount & Gateway Selector Script
```

---

## 🛠️ Shortcode Reference

### 1. One-Time Donation Form
Renders the compact one-time donation form with preset amount buttons and gateway selector:

`[donation_form]` *(Or explicitly using `[donation_form type="one_time"]`)*

### 2. Monthly Recurring Subscription Form
Renders the recurring donation form linked to Stripe Subscriptions and PayPal Billing Plans:

`[donation_form type="monthly"]`

### 3. Donation Receipt
Displays the transaction receipt summary on your success redirect page (`/donation-success/`):
`[donation_receipt]`

---

## ⚙️ Installation & Setup

1. Clone or download this repository into your WordPress plugins directory:
   cd wp-content/plugins/
   git clone https://github.com/razuahammad55/cdf-donation-form-with-paypal-and-stripe.git cdf-donation-form-with-paypal-and-stripe

2. Activate **CDF Donation Form with PayPal and Stripe** via **Plugins > Installed Plugins** in WordPress.
3. Upon activation, `/donation-success/` and `/donation-canceled/` pages are created automatically.
4. Go to **Donations > Settings** in your WordPress admin menu:
   * Select **Test / Sandbox** or **Live / Production** mode.
   * Enter your **Stripe Publishable Key** (`pk_...`) and **Stripe Secret Key** (`sk_...`).
   * Enter your **PayPal Client ID**, **Secret Key**, and **Monthly Plan ID**.
5. Embed `[donation_form]` on any page, post, or Elementor widget!

---

## 👨‍💻 Author & Developer Info

* **Developer:** Razu Ahammad
* **Specialization:** WordPress Speed Optimization, Malware Security & Elementor Web Design
* **Upwork Profile:** https://www.upwork.com/freelancers/razuahammad55
* **Contact Email:** razuahammad55@gmail.com

---

## 📄 License

Distributed under the **GNU General Public License v2.0 or later**. See `LICENSE` for details.
