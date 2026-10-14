<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=donation',
        __( 'Donation Forms & Instructions', 'cdf-donation-form-with-paypal-and-stripe' ),
        __( 'Forms', 'cdf-donation-form-with-paypal-and-stripe' ),
        'manage_options',
        'cdf-forms',
        'cdf_render_forms_page'
    );

    add_submenu_page(
        'edit.php?post_type=donation',
        __( 'Donation Analytics & Revenue', 'cdf-donation-form-with-paypal-and-stripe' ),
        __( 'Analytics', 'cdf-donation-form-with-paypal-and-stripe' ),
        'manage_options',
        'cdf-analytics',
        'cdf_render_analytics_page'
    );

    add_submenu_page(
        'edit.php?post_type=donation',
        __( 'Donation Form Styles', 'cdf-donation-form-with-paypal-and-stripe' ),
        __( 'Style', 'cdf-donation-form-with-paypal-and-stripe' ),
        'manage_options',
        'cdf-style',
        'cdf_render_style_page'
    );

    add_submenu_page(
        'edit.php?post_type=donation',
        __( 'Email Notifications', 'cdf-donation-form-with-paypal-and-stripe' ),
        __( 'Email', 'cdf-donation-form-with-paypal-and-stripe' ),
        'manage_options',
        'cdf-email',
        'cdf_render_email_page'
    );

    add_submenu_page(
        'edit.php?post_type=donation',
        __( 'Gateway API Settings', 'cdf-donation-form-with-paypal-and-stripe' ),
        __( 'Settings', 'cdf-donation-form-with-paypal-and-stripe' ),
        'manage_options',
        'cdf-settings',
        'cdf_render_settings_page'
    );
});

add_action( 'admin_init', function() {
    register_setting( 'cdf_api_settings_group', 'cdf_api_settings', function( $input ) {
        if ( ! is_array( $input ) ) {
            return array();
        }
        $sanitized = array();
        foreach ( $input as $key => $val ) {
            $sanitized[ sanitize_key( $key ) ] = sanitize_text_field( $val );
        }
        return $sanitized;
    });

    register_setting( 'cdf_email_settings_group', 'cdf_admin_email_enabled', 'sanitize_text_field' );
    register_setting( 'cdf_email_settings_group', 'cdf_donor_email_enabled', 'sanitize_text_field' );
    register_setting( 'cdf_email_settings_group', 'cdf_admin_email_recipients', 'sanitize_text_field' );
    register_setting( 'cdf_settings_group', 'cdf_environment', 'sanitize_text_field' );
    
    register_setting( 'cdf_style_options_group', 'cdf_style_options', function( $input ) {
        if ( ! is_array( $input ) ) {
            return array();
        }
        $sanitized = array();
        foreach ( $input as $key => $val ) {
            $safe_key = sanitize_key( $key );
            if ( strpos( $safe_key, 'color' ) !== false || strpos( $safe_key, 'bg' ) !== false || strpos( $safe_key, 'text' ) !== false ) {
                $sanitized[$safe_key] = sanitize_hex_color( $val );
            } else {
                $sanitized[$safe_key] = sanitize_text_field( $val );
            }
        }
        return $sanitized;
    });
});

add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( strpos( $hook, 'donation' ) !== false || strpos( $hook, 'cdf-' ) !== false ) {
        wp_enqueue_style( 'cdf-admin-style', CDF_PLUGIN_URL . 'assets/css/admin-style.css', array(), '2.0.0' );
    }
});

function cdf_render_forms_page() {
    if ( isset( $_POST['cdf_save_forms_config'] ) && check_admin_referer( 'cdf_forms_config_nonce' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $bypass = isset( $_POST['cdf_nonce_bypass'] ) ? '1' : '0';
        update_option( 'cdf_nonce_bypass', $bypass );
        echo '<div class="notice notice-success is-dismissible"><p>Forms configuration saved successfully!</p></div>';
    }

    $nonce_bypass = get_option( 'cdf_nonce_bypass', '0' );
    ?>
    <div class="wrap cdf-admin-wrap">
        <h1 style="font-size: 26px; font-weight: 700; color: #1e293b; margin-bottom: 6px;"><?php esc_html_e( 'Donation Forms & Instructions', 'cdf-donation-form-with-paypal-and-stripe' ); ?></h1>
        <p style="font-size: 14px; color: #64748b; margin-bottom: 25px;">Copy shortcodes and configure advanced form settings.</p>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="cdf-card">
                <h3>One-Time Donation Form</h3>
                <p style="color: #64748b; font-size: 14px;">Renders a compact, high-converting one-time donation form supporting Stripe and PayPal.</p>
                <div class="cdf-code-box">[donation_form type="one_time"]</div>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 0;">Note: You can also use <code>[donation_form]</code> by default.</p>
            </div>

            <div class="cdf-card">
                <h3>Monthly Recurring Form</h3>
                <p style="color: #64748b; font-size: 14px;">Renders the monthly subscription donation form supporting Stripe Subscriptions & PayPal Plans.</p>
                <div class="cdf-code-box">[donation_form type="monthly"]</div>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 0;">Requires PayPal Monthly Plan ID & Stripe configured.</p>
            </div>
        </div>

        <div class="cdf-card">
            <h3 style="border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 15px;">Advanced Caching & Security Configuration</h3>
            <form method="POST" action="">
                <?php wp_nonce_field( 'cdf_forms_config_nonce' ); ?>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <label class="cdf-switch">
                        <input type="checkbox" name="cdf_nonce_bypass" value="1" <?php checked( $nonce_bypass, '1' ); ?>>
                        <span class="cdf-slider"></span>
                    </label>
                    <div>
                        <strong style="font-size: 15px; color: #1e293b; display: block; margin-bottom: 4px;">Bypass Nonce Verification for Cached Environments</strong>
                        <p style="color: #64748b; font-size: 13px; margin: 0; line-height: 1.5;">Enable this switch only if your site uses aggressive full-page caching (like Cloudflare, WP Rocket, or SiteGround Optimizer) that causes cached nonces to expire. By default, nonce security is active and enabled as requested by WordPress.</p>
                    </div>
                </div>
                <div style="margin-top: 20px;">
                    <?php submit_button( 'Save Configuration', 'primary', 'cdf_save_forms_config', false ); ?>
                </div>
            </form>
        </div>
    </div>
    <?php
}

function cdf_render_analytics_page() {
    $donations = get_posts( array(
        'post_type'      => 'donation',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ) );

    $total_revenue  = 0;
    $stripe_rev     = 0;
    $stripe_onetime = 0;
    $stripe_monthly = 0;
    $paypal_rev     = 0;
    $paypal_onetime = 0;
    $paypal_monthly = 0;
    $total_count    = count( $donations );

    foreach ( $donations as $post ) {
        $amount    = floatval( get_post_meta( $post->ID, '_donation_amount', true ) );
        $gateway   = get_post_meta( $post->ID, '_donation_gateway', true );
        $frequency = get_post_meta( $post->ID, '_donation_frequency', true );

        $total_revenue += $amount;

        if ( 'Stripe' === $gateway ) {
            $stripe_rev += $amount;
            if ( 'Monthly' === $frequency ) {
                $stripe_monthly += $amount;
            } else {
                $stripe_onetime += $amount;
            }
        } else if ( 'PayPal' === $gateway ) {
            $paypal_rev += $amount;
            if ( 'Monthly' === $frequency ) {
                $paypal_monthly += $amount;
            } else {
                $paypal_onetime += $amount;
            }
        }
    }
    ?>
    <div class="wrap cdf-admin-wrap">
        <h1 style="font-size: 26px; font-weight: 700; color: #1e293b; margin-bottom: 6px;"><?php esc_html_e( 'Donation Analytics & Revenue Reports', 'cdf-donation-form-with-paypal-and-stripe' ); ?></h1>
        <p style="font-size: 14px; color: #64748b; margin-bottom: 25px;">Comprehensive financial overview and transaction metrics.</p>

        <div class="cdf-card" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 25px;">
            <span style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase;">Total Completed Donations</span>
            <span style="font-size: 24px; font-weight: 700; color: #10b981;"><?php echo intval( $total_count ); ?></span>
        </div>

        <div class="cdf-card">
            <div style="text-align: center; padding-bottom: 25px; border-bottom: 1px solid #e2e8f0; margin-bottom: 25px;">
                <span style="font-size: 13px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">Total Revenue Generated</span>
                <h2 style="font-size: 38px; font-weight: 800; color: #1e293b; margin: 0;">$<?php echo number_format( $total_revenue, 2 ); ?></h2>
            </div>

            <h3 style="margin-bottom: 15px; text-transform: uppercase; font-size: 14px; color: #64748b; letter-spacing: 0.5px;">Gateway Breakdown</h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
                        <span style="font-weight: 700; color: #1e293b;">Stripe Revenue</span>
                        <strong style="font-size: 18px; color: #2563eb;">$<?php echo number_format( $stripe_rev, 2 ); ?></strong>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; text-align: center;">
                        <div style="background: #fff; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px;">
                            <span style="font-size: 11px; text-transform: uppercase; color: #64748b; display: block;">One-Time</span>
                            <strong>$<?php echo number_format( $stripe_onetime, 2 ); ?></strong>
                        </div>
                        <div style="background: #fff; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px;">
                            <span style="font-size: 11px; text-transform: uppercase; color: #64748b; display: block;">Monthly</span>
                            <strong>$<?php echo number_format( $stripe_monthly, 2 ); ?></strong>
                        </div>
                    </div>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
                        <span style="font-weight: 700; color: #1e293b;">PayPal Revenue</span>
                        <strong style="font-size: 18px; color: #0284c7;">$<?php echo number_format( $paypal_rev, 2 ); ?></strong>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; text-align: center;">
                        <div style="background: #fff; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px;">
                            <span style="font-size: 11px; text-transform: uppercase; color: #64748b; display: block;">One-Time</span>
                            <strong>$<?php echo number_format( $paypal_onetime, 2 ); ?></strong>
                        </div>
                        <div style="background: #fff; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px;">
                            <span style="font-size: 11px; text-transform: uppercase; color: #64748b; display: block;">Monthly</span>
                            <strong>$<?php echo number_format( $paypal_monthly, 2 ); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function cdf_render_style_page() {
    if ( isset( $_POST['cdf_save_styles'] ) && check_admin_referer( 'cdf_style_nonce' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $raw_styles = isset( $_POST['cdf_style_options'] ) ? wp_unslash( $_POST['cdf_style_options'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $styles     = is_array( $raw_styles ) ? array_map( 'sanitize_text_field', $raw_styles ) : array();
        update_option( 'cdf_style_options', $styles );
        echo '<div class="notice notice-success is-dismissible"><p>Style settings saved successfully!</p></div>';
    }

    $styles = get_option( 'cdf_style_options', array() );
    $s = function( $key, $default ) use ( $styles ) {
        return isset( $styles[$key] ) ? $styles[$key] : $default;
    };
    ?>
    <div class="wrap cdf-admin-wrap">
        <h1 style="font-size: 26px; font-weight: 700; color: #1e293b; margin-bottom: 6px;"><?php esc_html_e( 'Donation Form Custom Styling', 'cdf-donation-form-with-paypal-and-stripe' ); ?></h1>
        <p style="font-size: 14px; color: #64748b; margin-bottom: 25px;">Customize button colors, hover/active states, and geometric border radius.</p>
        
        <form method="POST" action="">
            <?php wp_nonce_field( 'cdf_style_nonce' ); ?>
            
            <div class="cdf-card">
                <div style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #e2e8f0;">
                    <label style="display: block; font-weight: 600; color: #334155; margin-bottom: 6px;">Global Border Radius (px)</label>
                    <input type="number" name="cdf_style_options[border_radius]" value="<?php echo esc_attr( $s('border_radius', '8') ); ?>" min="0" max="30" style="width: 100px; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 4px;">
                </div>

                <div style="display: flex; gap: 8px; margin-bottom: 24px; border-bottom: 2px solid #e2e8f0; padding-bottom: 14px;">
                    <button type="button" class="cdf-tab-btn active" data-tab="normal" style="background: #2563eb; color: #fff; border: none; padding: 10px 20px; font-size: 14px; font-weight: 600; border-radius: 6px; cursor: pointer;">Normal State</button>
                    <button type="button" class="cdf-tab-btn" data-tab="hover" style="background: #f1f5f9; color: #475569; border: none; padding: 10px 20px; font-size: 14px; font-weight: 600; border-radius: 6px; cursor: pointer;">Hover State</button>
                    <button type="button" class="cdf-tab-btn" data-tab="active" style="background: #f1f5f9; color: #475569; border: none; padding: 10px 20px; font-size: 14px; font-weight: 600; border-radius: 6px; cursor: pointer;">Active State</button>
                </div>

                <div class="cdf-tab-pane" id="tab-normal">
                    <h3 style="font-size: 15px; color: #1e293b;">1. Amount Button</h3>
                    <table class="form-table">
                        <tr><th scope="row">Background Color</th><td><?php cdf_render_color_picker( 'cdf_style_options[amt_bg_normal]', $s('amt_bg_normal', '#f7fafc') ); ?></td></tr>
                        <tr><th scope="row">Text Color</th><td><?php cdf_render_color_picker( 'cdf_style_options[amt_text_normal]', $s('amt_text_normal', '#2d3748') ); ?></td></tr>
                    </table>
                    <h3 style="font-size: 15px; color: #1e293b; margin-top: 20px;">2. Gateway Card</h3>
                    <table class="form-table">
                        <tr><th scope="row">Background Color</th><td><?php cdf_render_color_picker( 'cdf_style_options[gw_bg_normal]', $s('gw_bg_normal', '#ffffff') ); ?></td></tr>
                        <tr><th scope="row">Text Color</th><td><?php cdf_render_color_picker( 'cdf_style_options[gw_text_normal]', $s('gw_text_normal', '#2d3748') ); ?></td></tr>
                    </table>
                    <h3 style="font-size: 15px; color: #1e293b; margin-top: 20px;">3. Submit Button</h3>
                    <table class="form-table">
                        <tr><th scope="row">Background Color</th><td><?php cdf_render_color_picker( 'cdf_style_options[sub_bg_normal]', $s('sub_bg_normal', '#3182ce') ); ?></td></tr>
                        <tr><th scope="row">Text Color</th><td><?php cdf_render_color_picker( 'cdf_style_options[sub_text_normal]', $s('sub_text_normal', '#ffffff') ); ?></td></tr>
                    </table>
                </div>

                <div class="cdf-tab-pane" id="tab-hover" style="display: none;">
                    <h3 style="font-size: 15px; color: #1e293b;">1. Amount Button</h3>
                    <table class="form-table">
                        <tr><th scope="row">Background Color (Hover)</th><td><?php cdf_render_color_picker( 'cdf_style_options[amt_bg_hover]', $s('amt_bg_hover', '#edf2f7') ); ?></td></tr>
                        <tr><th scope="row">Text Color (Hover)</th><td><?php cdf_render_color_picker( 'cdf_style_options[amt_text_hover]', $s('amt_text_hover', '#2d3748') ); ?></td></tr>
                    </table>
                    <h3 style="font-size: 15px; color: #1e293b; margin-top: 20px;">2. Gateway Card</h3>
                    <table class="form-table">
                        <tr><th scope="row">Background Color (Hover)</th><td><?php cdf_render_color_picker( 'cdf_style_options[gw_bg_hover]', $s('gw_bg_hover', '#f7fafc') ); ?></td></tr>
                        <tr><th scope="row">Text Color (Hover)</th><td><?php cdf_render_color_picker( 'cdf_style_options[gw_text_hover]', $s('gw_text_hover', '#2d3748') ); ?></td></tr>
                    </table>
                    <h3 style="font-size: 15px; color: #1e293b; margin-top: 20px;">3. Submit Button</h3>
                    <table class="form-table">
                        <tr><th scope="row">Background Color (Hover)</th><td><?php cdf_render_color_picker( 'cdf_style_options[sub_bg_hover]', $s('sub_bg_hover', '#2b6cb0') ); ?></td></tr>
                        <tr><th scope="row">Text Color (Hover)</th><td><?php cdf_render_color_picker( 'cdf_style_options[sub_text_hover]', $s('sub_text_hover', '#ffffff') ); ?></td></tr>
                    </table>
                </div>

                <div class="cdf-tab-pane" id="tab-active" style="display: none;">
                    <h3 style="font-size: 15px; color: #1e293b;">1. Amount Button</h3>
                    <table class="form-table">
                        <tr><th scope="row">Background Color (Active)</th><td><?php cdf_render_color_picker( 'cdf_style_options[amt_bg_active]', $s('amt_bg_active', '#ebf8ff') ); ?></td></tr>
                        <tr><th scope="row">Text Color (Active)</th><td><?php cdf_render_color_picker( 'cdf_style_options[amt_text_active]', $s('amt_text_active', '#2b6cb0') ); ?></td></tr>
                    </table>
                    <h3 style="font-size: 15px; color: #1e293b; margin-top: 20px;">2. Gateway Card</h3>
                    <table class="form-table">
                        <tr><th scope="row">Background Color (Active)</th><td><?php cdf_render_color_picker( 'cdf_style_options[gw_bg_active]', $s('gw_bg_active', '#ebf8ff') ); ?></td></tr>
                        <tr><th scope="row">Text Color (Active)</th><td><?php cdf_render_color_picker( 'cdf_style_options[gw_text_active]', $s('gw_text_active', '#2b6cb0') ); ?></td></tr>
                    </table>
                    <h3 style="font-size: 15px; color: #1e293b; margin-top: 20px;">3. Submit Button</h3>
                    <table class="form-table">
                        <tr><th scope="row">Background Color (Active)</th><td><?php cdf_render_color_picker( 'cdf_style_options[sub_bg_active]', $s('sub_bg_active', '#2b6cb0') ); ?></td></tr>
                        <tr><th scope="row">Text Color (Active)</th><td><?php cdf_render_color_picker( 'cdf_style_options[sub_text_active]', $s('sub_text_active', '#ffffff') ); ?></td></tr>
                    </table>
                </div>
            </div>

            <?php submit_button( 'Save Style Settings', 'primary', 'cdf_save_styles' ); ?>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabBtns = document.querySelectorAll('.cdf-tab-btn');
        const tabPanes = document.querySelectorAll('.cdf-tab-pane');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                tabBtns.forEach(b => {
                    b.classList.remove('active');
                    b.style.background = '#f1f5f9';
                    b.style.color = '#475569';
                });
                tabPanes.forEach(p => p.style.display = 'none');

                this.classList.add('active');
                this.style.background = '#2563eb';
                this.style.color = '#ffffff';

                const target = document.getElementById('tab-' + this.getAttribute('data-tab'));
                if (target) {
                    target.style.display = 'block';
                }
            });
        });
    });
    </script>
    <?php
}

function cdf_render_color_picker( $name, $value ) {
    ?>
    <div style="display: flex; align-items: center; gap: 10px;">
        <input type="color" value="<?php echo esc_attr( $value ); ?>" oninput="this.nextElementSibling.value = this.value" style="width: 45px; height: 32px; border: 1px solid #cbd5e1; border-radius: 4px; cursor: pointer; background: #fff; padding: 2px;">
        <input type="text" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="regular-text" style="width: 110px; font-family: monospace;" oninput="this.previousElementSibling.value = this.value">
    </div>
    <?php
}

function cdf_render_email_page() {
    if ( isset( $_POST['cdf_save_emails'] ) && check_admin_referer( 'cdf_email_nonce' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
        update_option( 'cdf_admin_email_enabled', isset( $_POST['cdf_admin_email_enabled'] ) ? '1' : '0' );
        update_option( 'cdf_donor_email_enabled', isset( $_POST['cdf_donor_email_enabled'] ) ? '1' : '0' );
        $recipients = isset( $_POST['cdf_admin_email_recipients'] ) ? sanitize_text_field( wp_unslash( $_POST['cdf_admin_email_recipients'] ) ) : '';
        update_option( 'cdf_admin_email_recipients', $recipients );
        echo '<div class="notice notice-success is-dismissible"><p>Email settings saved successfully!</p></div>';
    }

    $admin_enabled = get_option( 'cdf_admin_email_enabled', '1' );
    $donor_enabled = get_option( 'cdf_donor_email_enabled', '1' );
    $recipients    = get_option( 'cdf_admin_email_recipients', get_option( 'admin_email' ) );
    ?>
    <div class="wrap cdf-admin-wrap">
        <h1 style="font-size: 26px; font-weight: 700; color: #1e293b; margin-bottom: 6px;"><?php esc_html_e( 'Email Notification Settings', 'cdf-donation-form-with-paypal-and-stripe' ); ?></h1>
        <form method="POST" action="">
            <?php wp_nonce_field( 'cdf_email_nonce' ); ?>
            <div class="cdf-card">
                <table class="form-table">
                    <tr>
                        <th scope="row">Admin Email Notification</th>
                        <td>
                            <label style="display: flex; align-items: center; margin-bottom: 12px;">
                                <span class="cdf-switch">
                                    <input type="checkbox" id="cdf_admin_toggle" name="cdf_admin_email_enabled" value="1" <?php checked( $admin_enabled, '1' ); ?> onchange="document.getElementById('admin_email_box').style.display = this.checked ? 'block' : 'none';">
                                    <span class="cdf-slider"></span>
                                </span>
                                Enable admin notification email when a donation is received
                            </label>
                            <div id="admin_email_box" style="margin-top: 12px; display: <?php echo ( '1' === $admin_enabled ) ? 'block' : 'none'; ?>;">
                                <label style="display:block; font-weight:600; margin-bottom:4px;">Recipient Email Address(es)</label>
                                <input type="text" name="cdf_admin_email_recipients" value="<?php echo esc_attr( $recipients ); ?>" class="regular-text" style="width: 100%; max-width: 450px;">
                                <p class="description">Default is your WordPress admin email. Separate multiple email addresses with commas.</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Donor Receipt Email</th>
                        <td>
                            <label style="display: flex; align-items: center;">
                                <span class="cdf-switch">
                                    <input type="checkbox" name="cdf_donor_email_enabled" value="1" <?php checked( $donor_enabled, '1' ); ?>>
                                    <span class="cdf-slider"></span>
                                </span>
                                Enable automated thank-you receipt email sent directly to the donor
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
            <?php submit_button( 'Save Email Settings', 'primary', 'cdf_save_emails' ); ?>
        </form>
    </div>
    <?php
}

function cdf_render_settings_page() {
    if ( isset( $_POST['cdf_save_settings'] ) && check_admin_referer( 'cdf_settings_nonce' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $env = isset( $_POST['cdf_environment'] ) && 'live' === $_POST['cdf_environment'] ? 'live' : 'sandbox';
        update_option( 'cdf_environment', $env );
        
        $api_settings = array(
            'stripe_publishable_test' => isset( $_POST['cdf_stripe_publishable_test'] ) ? sanitize_text_field( wp_unslash( $_POST['cdf_stripe_publishable_test'] ) ) : '',
            'stripe_secret_test'      => isset( $_POST['cdf_stripe_secret_test'] ) ? sanitize_text_field( wp_unslash( $_POST['cdf_stripe_secret_test'] ) ) : '',
            'stripe_publishable_live' => isset( $_POST['cdf_stripe_publishable_live'] ) ? sanitize_text_field( wp_unslash( $_POST['cdf_stripe_publishable_live'] ) ) : '',
            'stripe_secret_live'      => isset( $_POST['cdf_stripe_secret_live'] ) ? sanitize_text_field( wp_unslash( $_POST['cdf_stripe_secret_live'] ) ) : '',
            'paypal_client_test'      => isset( $_POST['cdf_paypal_client_test'] ) ? sanitize_text_field( wp_unslash( $_POST['cdf_paypal_client_test'] ) ) : '',
            'paypal_secret_test'      => isset( $_POST['cdf_paypal_secret_test'] ) ? sanitize_text_field( wp_unslash( $_POST['cdf_paypal_secret_test'] ) ) : '',
            'paypal_client_live'      => isset( $_POST['cdf_paypal_client_live'] ) ? sanitize_text_field( wp_unslash( $_POST['cdf_paypal_client_live'] ) ) : '',
            'paypal_secret_live'      => isset( $_POST['cdf_paypal_secret_live'] ) ? sanitize_text_field( wp_unslash( $_POST['cdf_paypal_secret_live'] ) ) : '',
            'paypal_plan_id'          => isset( $_POST['cdf_paypal_plan_id'] ) ? sanitize_text_field( wp_unslash( $_POST['cdf_paypal_plan_id'] ) ) : '',
        );
        update_option( 'cdf_api_settings', $api_settings );
        echo '<div class="notice notice-success is-dismissible"><p>Gateway settings saved successfully!</p></div>';
    }

    $env      = get_option( 'cdf_environment', 'sandbox' );
    $settings = get_option( 'cdf_api_settings', array() );

    $stripe_pub_test    = isset( $settings['stripe_publishable_test'] ) ? $settings['stripe_publishable_test'] : ( isset( $settings['stripe_publishable'] ) ? $settings['stripe_publishable'] : '' );
    $stripe_sec_test    = isset( $settings['stripe_secret_test'] ) ? $settings['stripe_secret_test'] : ( isset( $settings['stripe_secret'] ) ? $settings['stripe_secret'] : '' );
    $stripe_pub_live    = isset( $settings['stripe_publishable_live'] ) ? $settings['stripe_publishable_live'] : '';
    $stripe_sec_live    = isset( $settings['stripe_secret_live'] ) ? $settings['stripe_secret_live'] : '';
    $paypal_client_test = isset( $settings['paypal_client_test'] ) ? $settings['paypal_client_test'] : ( isset( $settings['paypal_client'] ) ? $settings['paypal_client'] : '' );
    $paypal_sec_test    = isset( $settings['paypal_secret_test'] ) ? $settings['paypal_secret_test'] : ( isset( $settings['paypal_secret'] ) ? $settings['paypal_secret'] : '' );
    $paypal_client_live = isset( $settings['paypal_client_live'] ) ? $settings['paypal_client_live'] : '';
    $paypal_sec_live    = isset( $settings['paypal_secret_live'] ) ? $settings['paypal_secret_live'] : '';
    $paypal_plan        = isset( $settings['paypal_plan_id'] ) ? $settings['paypal_plan_id'] : '';
    ?>
    <div class="wrap cdf-admin-wrap">
        <h1 style="font-size: 26px; font-weight: 700; color: #1e293b; margin-bottom: 6px;"><?php esc_html_e( 'Gateway API Settings', 'cdf-donation-form-with-paypal-and-stripe' ); ?></h1>
        <form method="POST" action="">
            <?php wp_nonce_field( 'cdf_settings_nonce' ); ?>
            <div class="cdf-card">
                <table class="form-table">
                    <tr>
                        <th scope="row">Environment Mode</th>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <label class="cdf-switch" style="margin: 0;">
                                    <input type="checkbox" id="cdf_env_switch" name="cdf_environment" value="live" <?php checked( $env, 'live' ); ?>>
                                    <span class="cdf-slider"></span>
                                </label>
                                <div>
                                    <strong id="cdf_env_label" style="font-size: 14px; color: #1e293b; display: block;"><?php echo ( 'live' === $env ) ? 'Live / Production Mode' : 'Test / Sandbox Mode'; ?></strong>
                                    <span style="font-size: 13px; color: #64748b;">Switch between Sandbox test credentials and Live production credentials seamlessly.</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>

                <div id="cdf_test_creds" style="display: <?php echo ( 'live' === $env ) ? 'none' : 'block'; ?>;">
                    <h3 style="margin-top: 25px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; color: #2563eb;">Sandbox / Test API Credentials</h3>
                    <table class="form-table">
                        <tr>
                            <th scope="row">Stripe Publishable Key</th>
                            <td>
                                <input type="text" name="cdf_stripe_publishable_test" value="<?php echo esc_attr( $stripe_pub_test ); ?>" class="regular-text" style="width: 100%; max-width: 450px;">
                                <p class="description">Starts with pk_test_</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Stripe Secret Key</th>
                            <td>
                                <input type="password" name="cdf_stripe_secret_test" value="<?php echo esc_attr( $stripe_sec_test ); ?>" class="regular-text" autocomplete="new-password" style="width: 100%; max-width: 450px;">
                                <p class="description">Starts with sk_test_</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">PayPal Client ID</th>
                            <td>
                                <input type="text" name="cdf_paypal_client_test" value="<?php echo esc_attr( $paypal_client_test ); ?>" class="regular-text" style="width: 100%; max-width: 450px;">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">PayPal Secret Key</th>
                            <td>
                                <input type="password" name="cdf_paypal_secret_test" value="<?php echo esc_attr( $paypal_sec_test ); ?>" class="regular-text" autocomplete="new-password" style="width: 100%; max-width: 450px;">
                            </td>
                        </tr>
                    </table>
                </div>

                <div id="cdf_live_creds" style="display: <?php echo ( 'live' === $env ) ? 'block' : 'none'; ?>;">
                    <h3 style="margin-top: 25px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; color: #16a34a;">Live / Production API Credentials</h3>
                    <table class="form-table">
                        <tr>
                            <th scope="row">Stripe Publishable Key</th>
                            <td>
                                <input type="text" name="cdf_stripe_publishable_live" value="<?php echo esc_attr( $stripe_pub_live ); ?>" class="regular-text" style="width: 100%; max-width: 450px;">
                                <p class="description">Starts with pk_live_</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Stripe Secret Key</th>
                            <td>
                                <input type="password" name="cdf_stripe_secret_live" value="<?php echo esc_attr( $stripe_sec_live ); ?>" class="regular-text" autocomplete="new-password" style="width: 100%; max-width: 450px;">
                                <p class="description">Starts with sk_live_</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">PayPal Client ID</th>
                            <td>
                                <input type="text" name="cdf_paypal_client_live" value="<?php echo esc_attr( $paypal_client_live ); ?>" class="regular-text" style="width: 100%; max-width: 450px;">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">PayPal Secret Key</th>
                            <td>
                                <input type="password" name="cdf_paypal_secret_live" value="<?php echo esc_attr( $paypal_sec_live ); ?>" class="regular-text" autocomplete="new-password" style="width: 100%; max-width: 450px;">
                            </td>
                        </tr>
                    </table>
                </div>

                <h3 style="margin-top: 25px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">General Configuration</h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">PayPal Monthly Plan ID</th>
                        <td>
                            <input type="text" name="cdf_paypal_plan_id" value="<?php echo esc_attr( $paypal_plan ); ?>" class="regular-text" style="width: 100%; max-width: 450px;">
                            <p class="description">Enter your PayPal Billing Agreement Plan ID for monthly recurring subscriptions (starts with P-...).</p>
                        </td>
                    </tr>
                </table>
            </div>
            <?php submit_button( 'Save Gateway Settings', 'primary', 'cdf_save_settings' ); ?>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const envSwitch = document.getElementById('cdf_env_switch');
        const envLabel  = document.getElementById('cdf_env_label');
        const testCreds = document.getElementById('cdf_test_creds');
        const liveCreds = document.getElementById('cdf_live_creds');

        if (envSwitch && envLabel) {
            envSwitch.addEventListener('change', function() {
                if (this.checked) {
                    envLabel.textContent = 'Live / Production Mode';
                    testCreds.style.display = 'none';
                    liveCreds.style.display = 'block';
                } else {
                    envLabel.textContent = 'Test / Sandbox Mode';
                    testCreds.style.display = 'block';
                    liveCreds.style.display = 'none';
                }
            });
        }
    });
    </script>
    <?php
}
