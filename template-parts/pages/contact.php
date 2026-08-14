<?php
/**
 * お問い合わせページ本文（ヒーロー + フォーム / サンクス）
 * 送信処理は page-contact.php。status は query_var contact_status。
 * 入口: page-contact.php
 */
/**
 * お問い合わせページ ヒーローセクション
 */
get_template_part(
    'template-parts/shared/page-hero',
    null,
    array(
        'title_en'       => 'CONTACT',
        'title_ja'       => 'お問い合わせ',
        'image'          => 'assets/images/contact/contact-hero.png',
        'image_position' => 'center 40%',
    )
);

 $form_status = get_query_var( 'contact_status', '' ); if ( 'success' === $form_status ) : ?>
<?php
/**
 * お問い合わせ送信完了（サンクス）セクション
 */
?>
<section class="contact-thanks">
    <div class="contact-thanks__inner">
        <div class="contact-thanks__content">
            <p class="contact-thanks__title">送信が完了しました。</p>
            <div class="contact-thanks__message">
                <p class="contact-thanks__lead">お問い合わせいただき、誠にありがとうございます。</p>
                <div class="contact-thanks__body">
                    <p>担当者が確認の上、ご連絡差し上げます。</p>
                    <p>返信まで今しばらくお待ちください。</p>
                </div>
            </div>
        </div>
        <div class="contact-thanks__action">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="contact-thanks__btn">
                <?php get_template_part( 'template-parts/shared/action-btn-arrow' ); ?>
                <span>TOPに戻る</span>
            </a>
        </div>
    </div>
</section>

<?php else : ?>
<?php
/**
 * お問い合わせフォームセクション
 * ネイティブ PHP (wp_mail) で動作。本番では CF7 shortcode に差替可。
 */

$form_status   = get_query_var( 'contact_status', '' );
$form_errors   = get_query_var( 'contact_errors', array() );
$field_errors  = akishima_contact_form_field_errors( is_array( $form_errors ) ? $form_errors : array() );
$has_name_err  = '' !== $field_errors['name'];
$has_email_err = '' !== $field_errors['email'];
$has_msg_err   = '' !== $field_errors['message'];
$has_priv_err  = '' !== $field_errors['privacy'];

// 入力値の保持（エラー時に再表示）
$old_name    = isset( $_GET['_name'] ) ? sanitize_text_field( wp_unslash( $_GET['_name'] ) ) : '';
$old_email   = isset( $_GET['_email'] ) ? sanitize_email( wp_unslash( $_GET['_email'] ) ) : '';
$old_message = isset( $_GET['_message'] ) ? sanitize_textarea_field( wp_unslash( $_GET['_message'] ) ) : '';
?>
<section class="contact-form-section">
    <div class="contact-form-section__inner">

        <?php if ( 'mail_error' === $form_status ) : ?>
        <div class="contact-form__notice contact-form__notice--error" role="alert">
            <p>送信に失敗しました。時間をおいて再度お試しください。</p>
        </div>
        <?php endif; ?>

        <form class="contact-form" action="<?php echo esc_url( get_permalink() ); ?>" method="post" novalidate>
            <?php wp_nonce_field( 'akishima_contact_form', '_contact_nonce' ); ?>

            <!-- お名前 -->
            <div class="contact-form__field">
                <label class="contact-form__label" for="contact-name">
                    お名前<span class="contact-form__required">必須</span>
                </label>
                <input
                    type="text"
                    id="contact-name"
                    name="contact_name"
                    class="contact-form__input<?php echo $has_name_err ? ' is-error' : ''; ?>"
                    placeholder="山田 太郎"
                    value="<?php echo esc_attr( $old_name ); ?>"
                    required
                    autocomplete="name"
                    aria-invalid="<?php echo $has_name_err ? 'true' : 'false'; ?>"
                    aria-describedby="contact-name-error"
                >
                <p class="contact-form__error" id="contact-name-error" role="alert"<?php echo $has_name_err ? '' : ' hidden'; ?>><?php echo esc_html( $field_errors['name'] ); ?></p>
            </div>

            <!-- メールアドレス -->
            <div class="contact-form__field">
                <label class="contact-form__label" for="contact-email">
                    メールアドレス<span class="contact-form__required">必須</span>
                </label>
                <input
                    type="email"
                    id="contact-email"
                    name="contact_email"
                    class="contact-form__input<?php echo $has_email_err ? ' is-error' : ''; ?>"
                    placeholder="xxxx@example.co.jp"
                    value="<?php echo esc_attr( $old_email ); ?>"
                    required
                    autocomplete="email"
                    aria-invalid="<?php echo $has_email_err ? 'true' : 'false'; ?>"
                    aria-describedby="contact-email-error"
                >
                <p class="contact-form__error" id="contact-email-error" role="alert"<?php echo $has_email_err ? '' : ' hidden'; ?>><?php echo esc_html( $field_errors['email'] ); ?></p>
            </div>

            <!-- お問い合わせ内容 -->
            <div class="contact-form__field">
                <label class="contact-form__label" for="contact-message">
                    お問い合わせ内容<span class="contact-form__required">必須</span>
                </label>
                <textarea
                    id="contact-message"
                    name="contact_message"
                    class="contact-form__textarea<?php echo $has_msg_err ? ' is-error' : ''; ?>"
                    placeholder="詳細をご記載ください。"
                    rows="10"
                    required
                    aria-invalid="<?php echo $has_msg_err ? 'true' : 'false'; ?>"
                    aria-describedby="contact-message-error"
                ><?php echo esc_textarea( $old_message ); ?></textarea>
                <p class="contact-form__error" id="contact-message-error" role="alert"<?php echo $has_msg_err ? '' : ' hidden'; ?>><?php echo esc_html( $field_errors['message'] ); ?></p>
            </div>

            <!-- 個人情報同意チェックボックス -->
            <div class="contact-form__privacy">
                <label class="contact-form__privacy-label">
                    <input
                        type="checkbox"
                        name="contact_privacy"
                        id="contact-privacy"
                        class="contact-form__checkbox<?php echo $has_priv_err ? ' is-error' : ''; ?>"
                        value="1"
                        required
                        aria-invalid="<?php echo $has_priv_err ? 'true' : 'false'; ?>"
                        aria-describedby="contact-privacy-error"
                    >
                    <span><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" target="_blank" rel="noopener noreferrer">個人情報の取扱規程</a>に同意する。</span>
                </label>
                <p class="contact-form__error contact-form__error--privacy" id="contact-privacy-error" role="alert"<?php echo $has_priv_err ? '' : ' hidden'; ?>><?php echo esc_html( $field_errors['privacy'] ); ?></p>
            </div>

            <!-- 送信ボタン -->
            <div class="contact-form__submit-wrap">
                <button type="submit" class="contact-form__submit">
                    <span>送信する</span>
                    <?php get_template_part( 'template-parts/shared/action-btn-arrow' ); ?>
                </button>
            </div>

        </form>

    </div>
</section>

<?php endif; ?>
