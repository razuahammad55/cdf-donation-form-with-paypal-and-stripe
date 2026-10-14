<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function cdf_register_donation_cpt() {
    if ( ! post_type_exists( 'donation' ) ) {
        register_post_type( 'donation', array(
            'labels' => array(
                'name'               => __( 'Donations', 'cdf-donation-form-with-paypal-and-stripe' ),
                'singular_name'      => __( 'Donation', 'cdf-donation-form-with-paypal-and-stripe' ),
                'add_new'            => __( 'Add Donation', 'cdf-donation-form-with-paypal-and-stripe' ),
                'add_new_item'       => __( 'Add New Donation', 'cdf-donation-form-with-paypal-and-stripe' ),
                'edit_item'          => __( 'View Donation', 'cdf-donation-form-with-paypal-and-stripe' ),
                'view_item'          => __( 'View Donation', 'cdf-donation-form-with-paypal-and-stripe' ),
                'search_items'       => __( 'Search Donations', 'cdf-donation-form-with-paypal-and-stripe' ),
                'not_found'          => __( 'No donations found', 'cdf-donation-form-with-paypal-and-stripe' ),
                'not_found_in_trash' => __( 'No donations found in Trash', 'cdf-donation-form-with-paypal-and-stripe' ),
            ),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 25,
            'menu_icon'           => 'dashicons-heart',
            'capability_type'     => 'post',
            'capabilities'        => array( 'create_posts' => false ),
            'map_meta_cap'        => true,
            'supports'            => array( 'title' ),
            'has_archive'         => false,
            'exclude_from_search' => true,
        ) );
    }
}
add_action( 'init', 'cdf_register_donation_cpt' );

add_filter( 'manage_donation_posts_columns', function( $columns ) {
    return array(
        'cb'          => '<input type="checkbox" />',
        'first_name'  => __( 'First Name', 'cdf-donation-form-with-paypal-and-stripe' ),
        'email'       => __( 'Email', 'cdf-donation-form-with-paypal-and-stripe' ),
        'amount'      => __( 'Amount', 'cdf-donation-form-with-paypal-and-stripe' ),
        'frequency'   => __( 'Frequency', 'cdf-donation-form-with-paypal-and-stripe' ),
        'gateway'     => __( 'Gateway', 'cdf-donation-form-with-paypal-and-stripe' ),
        'environment' => __( 'Mode', 'cdf-donation-form-with-paypal-and-stripe' ),
        'date'        => __( 'Date', 'cdf-donation-form-with-paypal-and-stripe' ),
    );
});

add_action( 'manage_donation_posts_custom_column', function( $column, $post_id ) {
    switch ( $column ) {
        case 'first_name':
            $first_name = get_post_meta( $post_id, '_donation_first_name', true );
            $edit_link  = get_edit_post_link( $post_id );
            echo '<a class="row-title" href="' . esc_url( $edit_link ) . '">' . esc_html( $first_name ? $first_name : 'N/A' ) . '</a>';
            break;
        case 'email':
            echo esc_html( get_post_meta( $post_id, '_donation_email', true ) ?: '—' );
            break;
        case 'amount':
            $amount = get_post_meta( $post_id, '_donation_amount', true );
            echo esc_html( '$' . number_format( floatval( $amount ), 2 ) );
            break;
        case 'frequency':
            echo esc_html( get_post_meta( $post_id, '_donation_frequency', true ) );
            break;
        case 'gateway':
            echo esc_html( get_post_meta( $post_id, '_donation_gateway', true ) );
            break;
        case 'environment':
            $env = get_post_meta( $post_id, '_donation_environment', true );
            $badge_style = ( 'Test / Sandbox' === $env ) ? 'background:#e74c3c;color:#fff;' : 'background:#2ecc71;color:#fff;';
            echo '<span style="padding: 2px 8px; border-radius: 3px; font-weight: 600; font-size: 11px; ' . esc_attr( $badge_style ) . '">' . esc_html( $env ) . '</span>';
            break;
    }
}, 10, 2 );

add_action( 'add_meta_boxes', function() {
    add_meta_box(
        'donation_details_metabox',
        __( 'Donation & Payment Details', 'cdf-donation-form-with-paypal-and-stripe' ),
        'cdf_render_donation_details_metabox',
        'donation',
        'normal',
        'high'
    );
});

function cdf_render_donation_details_metabox( $post ) {
    $first_name     = get_post_meta( $post->ID, '_donation_first_name', true );
    $last_name      = get_post_meta( $post->ID, '_donation_last_name', true );
    $email          = get_post_meta( $post->ID, '_donation_email', true );
    $amount         = get_post_meta( $post->ID, '_donation_amount', true );
    $gateway        = get_post_meta( $post->ID, '_donation_gateway', true );
    $frequency      = get_post_meta( $post->ID, '_donation_frequency', true );
    $environment    = get_post_meta( $post->ID, '_donation_environment', true );
    $transaction_id = get_post_meta( $post->ID, '_donation_transaction_id', true );
    $serial_number  = get_post_meta( $post->ID, '_donation_serial_number', true );
    ?>
    <div class="cdf-details-grid">
        <div class="cdf-detail-card">
            <strong><?php esc_html_e( 'Serial Number', 'cdf-donation-form-with-paypal-and-stripe' ); ?></strong>
            <span>#<?php echo esc_html( $serial_number ? $serial_number : $post->ID ); ?></span>
        </div>
        <div class="cdf-detail-card">
            <strong><?php esc_html_e( 'Donation Amount', 'cdf-donation-form-with-paypal-and-stripe' ); ?></strong>
            <span>$<?php echo esc_html( number_format( floatval( $amount ), 2 ) ); ?></span>
        </div>
        <div class="cdf-detail-card">
            <strong><?php esc_html_e( 'First Name', 'cdf-donation-form-with-paypal-and-stripe' ); ?></strong>
            <span><?php echo esc_html( $first_name ?: '—' ); ?></span>
        </div>
        <div class="cdf-detail-card">
            <strong><?php esc_html_e( 'Last Name', 'cdf-donation-form-with-paypal-and-stripe' ); ?></strong>
            <span><?php echo esc_html( $last_name ?: '—' ); ?></span>
        </div>
        <div class="cdf-detail-card">
            <strong><?php esc_html_e( 'Email Address', 'cdf-donation-form-with-paypal-and-stripe' ); ?></strong>
            <span><?php echo esc_html( $email ?: '—' ); ?></span>
        </div>
        <div class="cdf-detail-card">
            <strong><?php esc_html_e( 'Payment Gateway', 'cdf-donation-form-with-paypal-and-stripe' ); ?></strong>
            <span><?php echo esc_html( $gateway ?: '—' ); ?></span>
        </div>
        <div class="cdf-detail-card">
            <strong><?php esc_html_e( 'Frequency', 'cdf-donation-form-with-paypal-and-stripe' ); ?></strong>
            <span><?php echo esc_html( $frequency ?: '—' ); ?></span>
        </div>
        <div class="cdf-detail-card">
            <strong><?php esc_html_e( 'Environment', 'cdf-donation-form-with-paypal-and-stripe' ); ?></strong>
            <span><?php echo esc_html( $environment ?: '—' ); ?></span>
        </div>
        <div class="cdf-detail-card" style="grid-column: span 2;">
            <strong><?php esc_html_e( 'Transaction / Order ID', 'cdf-donation-form-with-paypal-and-stripe' ); ?></strong>
            <span style="font-family: monospace; font-size: 13px; word-break: break-all;"><?php echo esc_html( $transaction_id ?: '—' ); ?></span>
        </div>
    </div>
    <?php
}
