<?php
/**
 * 子サイト管理画面: 自治会ページのテキスト・画像編集
 */

/**
 * オプションキー一覧
 */
function akishima_community_content_option_keys() {
    return array(
        'intro'          => 'akishima_community_intro',
        'activities'     => 'akishima_community_activities',
        'fee'            => 'akishima_community_fee',
        'organizations'  => 'akishima_community_organizations',
        'facility_name'  => 'akishima_community_facility_name',
        'facility_address' => 'akishima_community_facility_address',
        'facility_rental'  => 'akishima_community_facility_rental',
    );
}

/**
 * 子サイトに保存済みのコンテンツを取得
 */
function akishima_get_community_content_from_options() {
    $data = array();
    foreach ( akishima_community_content_option_keys() as $field => $option_key ) {
        $data[ $field ] = (string) get_option( $option_key, '' );
    }
    return $data;
}

/**
 * 管理メニュー（子サイトのみ）
 */
function akishima_community_admin_menu() {
    if ( ! is_multisite() || is_main_site() ) {
        return;
    }

    add_menu_page(
        '自治会ページの編集',
        '自治会ページ',
        'edit_posts',
        'akishima-community-page',
        'akishima_community_admin_render_page',
        'dashicons-admin-home',
        3
    );
}
add_action( 'admin_menu', 'akishima_community_admin_menu' );

/**
 * メディアライブラリ
 */
function akishima_community_admin_assets( $hook ) {
    if ( 'toplevel_page_akishima-community-page' !== $hook ) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script(
        'akishima-community-admin',
        get_template_directory_uri() . '/assets/js/community-admin.js',
        array( 'jquery' ),
        filemtime( get_template_directory() . '/assets/js/community-admin.js' ),
        true
    );
}
add_action( 'admin_enqueue_scripts', 'akishima_community_admin_assets' );

/**
 * 保存処理
 */
function akishima_community_admin_save() {
    if ( ! isset( $_POST['akishima_community_admin_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['akishima_community_admin_nonce'] ) ), 'akishima_community_admin_save' ) ) {
        return;
    }

    if ( ! is_multisite() || is_main_site() || ! current_user_can( 'edit_posts' ) ) {
        return;
    }

    foreach ( akishima_community_content_option_keys() as $field => $option_key ) {
        $raw = isset( $_POST[ 'akishima_' . $field ] ) ? wp_unslash( $_POST[ 'akishima_' . $field ] ) : '';
        update_option( $option_key, sanitize_textarea_field( $raw ) );
    }

    foreach ( array( 'gallery', 'facility_gallery' ) as $gallery_key ) {
        $option_key = 'akishima_community_' . $gallery_key . '_ids';
        $raw_ids    = isset( $_POST[ $gallery_key . '_ids' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $gallery_key . '_ids' ] ) ) : '';
        $ids        = array_filter( array_map( 'absint', explode( ',', $raw_ids ) ) );
        update_option( $option_key, $ids );
    }

    add_settings_error( 'akishima_community_admin', 'saved', '自治会ページを保存しました。', 'success' );
}
add_action( 'admin_init', 'akishima_community_admin_save' );

/**
 * 管理画面フォーム
 */
function akishima_community_admin_render_page() {
    if ( ! is_multisite() || is_main_site() || ! current_user_can( 'edit_posts' ) ) {
        wp_die( 'このページにはアクセスできません。' );
    }

    $slug      = function_exists( 'akishima_get_subsite_community_slug' ) ? akishima_get_subsite_community_slug() : '';
    $community = $slug ? akishima_get_community_by_slug( $slug ) : null;
    $is_block  = $community && ! empty( $community['is_block'] );
    $name      = $community && ! empty( $community['name'] ) ? $community['name'] : ( $is_block ? 'ブロック' : '自治会' );
    $page_type = $is_block ? 'ブロックページ' : '自治会ページ';
    $content   = akishima_get_community_content_from_options();
    $gallery_ids          = (array) get_option( 'akishima_community_gallery_ids', array() );
    $facility_gallery_ids = (array) get_option( 'akishima_community_facility_gallery_ids', array() );

    settings_errors( 'akishima_community_admin' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( $page_type ); ?>の編集</h1>
        <p style="max-width:720px;line-height:1.7">
            <strong><?php echo esc_html( $name ); ?></strong> のトップページに表示される内容を編集します。<br>
            ページ上部の名称・ブロック名はマスターデータのため、ここでは変更できません。
        </p>

        <form method="post" action="">
            <?php wp_nonce_field( 'akishima_community_admin_save', 'akishima_community_admin_nonce' ); ?>

            <h2 class="title">基本情報</h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="akishima_intro">紹介文</label></th>
                    <td>
                        <textarea name="akishima_intro" id="akishima_intro" rows="6" class="large-text"><?php echo esc_textarea( $content['intro'] ); ?></textarea>
                        <p class="description">自治会の紹介文（ページの「自治会紹介文」ブロックに表示）</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="akishima_activities">主な活動</label></th>
                    <td>
                        <textarea name="akishima_activities" id="akishima_activities" rows="3" class="large-text"><?php echo esc_textarea( $content['activities'] ); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="akishima_fee">自治会費</label></th>
                    <td>
                        <input type="text" name="akishima_fee" id="akishima_fee" value="<?php echo esc_attr( $content['fee'] ); ?>" class="regular-text" placeholder="例: 年2,400円">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="akishima_organizations">構成団体</label></th>
                    <td>
                        <textarea name="akishima_organizations" id="akishima_organizations" rows="4" class="large-text"><?php echo esc_textarea( $content['organizations'] ); ?></textarea>
                        <p class="description">複数行で入力できます（1行ずつ段落になります）</p>
                    </td>
                </tr>
            </table>

            <?php akishima_community_admin_render_gallery_field( 'gallery', '活動の写真', $gallery_ids ); ?>

            <h2 class="title">自治会館・集会施設等</h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="akishima_facility_name">施設名</label></th>
                    <td>
                        <input type="text" name="akishima_facility_name" id="akishima_facility_name" value="<?php echo esc_attr( $content['facility_name'] ); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="akishima_facility_address">所在地</label></th>
                    <td>
                        <input type="text" name="akishima_facility_address" id="akishima_facility_address" value="<?php echo esc_attr( $content['facility_address'] ); ?>" class="large-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="akishima_facility_rental">会員外への貸出</label></th>
                    <td>
                        <textarea name="akishima_facility_rental" id="akishima_facility_rental" rows="5" class="large-text"><?php echo esc_textarea( $content['facility_rental'] ); ?></textarea>
                        <p class="description">複数行で入力できます</p>
                    </td>
                </tr>
            </table>

            <?php akishima_community_admin_render_gallery_field( 'facility_gallery', '施設の写真', $facility_gallery_ids ); ?>

            <?php submit_button( '保存する' ); ?>
        </form>
    </div>
    <?php
}

/**
 * 画像ギャラリー入力欄
 *
 * @param string $key   gallery|facility_gallery
 * @param string $label
 * @param int[]  $ids
 */
function akishima_community_admin_render_gallery_field( $key, $label, $ids ) {
    $ids = array_values( array_filter( array_map( 'absint', $ids ) ) );
    ?>
    <h2 class="title"><?php echo esc_html( $label ); ?></h2>
    <table class="form-table" role="presentation">
        <tr>
            <th scope="row"><?php echo esc_html( $label ); ?></th>
            <td>
                <input type="hidden" name="<?php echo esc_attr( $key ); ?>_ids" id="<?php echo esc_attr( $key ); ?>_ids" value="<?php echo esc_attr( implode( ',', $ids ) ); ?>">
                <ul class="akishima-gallery-preview" id="<?php echo esc_attr( $key ); ?>_preview" style="display:flex;flex-wrap:wrap;gap:12px;margin:0 0 12px;padding:0;list-style:none">
                    <?php foreach ( $ids as $id ) :
                        $thumb = wp_get_attachment_image_url( $id, 'thumbnail' );
                        if ( ! $thumb ) {
                            continue;
                        }
                    ?>
                    <li data-id="<?php echo esc_attr( $id ); ?>" style="position:relative">
                        <img src="<?php echo esc_url( $thumb ); ?>" alt="" style="width:96px;height:96px;object-fit:cover;border-radius:4px">
                        <button type="button" class="button-link akishima-gallery-remove" data-gallery="<?php echo esc_attr( $key ); ?>" data-id="<?php echo esc_attr( $id ); ?>" style="position:absolute;top:2px;right:2px;background:#fff;border-radius:50%;width:22px;height:22px;line-height:20px;text-align:center" aria-label="削除">×</button>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="button akishima-gallery-add" data-gallery="<?php echo esc_attr( $key ); ?>">画像を追加</button>
                <p class="description">メディアライブラリから選択、または新規アップロードできます。表示順は選択順です。</p>
            </td>
        </tr>
    </table>
    <?php
}
