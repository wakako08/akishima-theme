<?php
/**
 * Template Name: お問い合わせページ
 * CONTACT
 * 本文: template-parts/pages/contact.php
 */

// ===== フォーム送信処理 =====
if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['_contact_nonce'] ) ) {
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_contact_nonce'] ) ), 'akishima_contact_form' ) ) {
        wp_die( 'セキュリティエラーが発生しました。', '', array( 'response' => 403 ) );
    }

    $name    = isset( $_POST['contact_name'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_name'] ) ) : '';
    $email   = isset( $_POST['contact_email'] ) ? sanitize_email( wp_unslash( $_POST['contact_email'] ) ) : '';
    $message = isset( $_POST['contact_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ) ) : '';
    $privacy = ! empty( $_POST['contact_privacy'] );

    $errors = akishima_validate_contact_form( $name, $email, $message, $privacy );

    if ( empty( $errors ) ) {
        $to      = get_option( 'admin_email' );
        $subject = '【お問い合わせ】' . $name . ' 様より';
        $body    = "お名前: {$name}\nメールアドレス: {$email}\n\nお問い合わせ内容:\n{$message}";
        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'Reply-To: ' . $name . ' <' . $email . '>',
        );

        $sent         = wp_mail( $to, $subject, $body, $headers );
        $redirect_url = add_query_arg( 'contact_status', $sent ? 'success' : 'mail_error', get_permalink() );
    } else {
        $redirect_url = add_query_arg(
            array(
                'contact_status' => 'invalid',
                '_errors'        => implode( ',', $errors ),
                '_name'          => $name,
                '_email'         => $email,
                '_message'       => $message,
            ),
            get_permalink()
        );
    }

    wp_safe_redirect( $redirect_url );
    exit;
}

$form_status = isset( $_GET['contact_status'] ) ? sanitize_key( wp_unslash( $_GET['contact_status'] ) ) : '';
$form_errors = 'invalid' === $form_status ? akishima_contact_form_errors_from_request() : array();

set_query_var( 'contact_status', $form_status );
set_query_var( 'contact_errors', $form_errors );

get_header(); ?>

<main id="main" class="site-main contact-top-page">

    <?php get_template_part( 'template-parts/pages/contact' ); ?>

</main>

<?php get_footer(); ?>
