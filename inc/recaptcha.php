<?php
/**
 * お問い合わせフォーム用 Google reCAPTCHA v3
 *
 * キーは wp-config.php の定数で上書きできる。
 *   define( 'AKISHIMA_RECAPTCHA_SITE_KEY', '...' );
 *   define( 'AKISHIMA_RECAPTCHA_SECRET_KEY', '...' );
 */

function akishima_recaptcha_site_key() {
    if ( defined( 'AKISHIMA_RECAPTCHA_SITE_KEY' ) && AKISHIMA_RECAPTCHA_SITE_KEY ) {
        return AKISHIMA_RECAPTCHA_SITE_KEY;
    }

    return '6LcHThsuAAAAAEgKZC2BTSsaSFc8wnn-SzWWDESr';
}

function akishima_recaptcha_secret_key() {
    if ( defined( 'AKISHIMA_RECAPTCHA_SECRET_KEY' ) && AKISHIMA_RECAPTCHA_SECRET_KEY ) {
        return AKISHIMA_RECAPTCHA_SECRET_KEY;
    }

    return '6LcHThsuAAAAADgK4_9zwwtMa799OcdRH8BwZKvu';
}

function akishima_recaptcha_verify_token( $token ) {
    $secret = akishima_recaptcha_secret_key();
    $token  = is_string( $token ) ? trim( $token ) : '';

    if ( '' === $secret || '' === $token ) {
        return false;
    }

    $response = wp_remote_post(
        'https://www.google.com/recaptcha/api/siteverify',
        array(
            'timeout' => 8,
            'body'    => array(
                'secret'   => $secret,
                'response' => $token,
                'remoteip' => isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '',
            ),
        )
    );

    if ( is_wp_error( $response ) ) {
        return false;
    }

    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( ! is_array( $body ) || empty( $body['success'] ) ) {
        return false;
    }

    $score  = isset( $body['score'] ) ? (float) $body['score'] : 0;
    $action = isset( $body['action'] ) ? $body['action'] : '';

    return $score >= 0.5 && 'contact' === $action;
}

function akishima_enqueue_recaptcha() {
    if ( is_admin() || ! is_page_template( 'page-contact.php' ) ) {
        return;
    }

    if ( 'success' === get_query_var( 'contact_status', '' ) ) {
        return;
    }

    $site_key = akishima_recaptcha_site_key();
    if ( '' === $site_key ) {
        return;
    }

    wp_enqueue_script(
        'google-recaptcha',
        'https://www.google.com/recaptcha/api.js?render=' . rawurlencode( $site_key ),
        array(),
        null,
        true
    );

    wp_enqueue_script(
        'akishima-contact-recaptcha',
        get_template_directory_uri() . '/assets/js/contact-recaptcha.js',
        array( 'google-recaptcha' ),
        filemtime( get_template_directory() . '/assets/js/contact-recaptcha.js' ),
        true
    );

    wp_localize_script(
        'akishima-contact-recaptcha',
        'akishimaRecaptcha',
        array(
            'siteKey' => $site_key,
            'action'  => 'contact',
        )
    );
}
add_action( 'wp_enqueue_scripts', 'akishima_enqueue_recaptcha' );
