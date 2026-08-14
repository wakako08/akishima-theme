<?php

/**
 * 自治会マスターデータ・個別ページルーティング
 */
require_once get_template_directory() . '/inc/communities-data.php';
require_once get_template_directory() . '/inc/communities-routing.php';
require_once get_template_directory() . '/inc/community-content.php';
require_once get_template_directory() . '/inc/community-news.php';
require_once get_template_directory() . '/inc/community-events.php';
require_once get_template_directory() . '/inc/community-admin.php';
require_once get_template_directory() . '/inc/links-data.php';

/**
 * テーマのセットアップ
 */
function akishima_setup() {
    // タイトルタグのサポート
    add_theme_support( 'title-tag' );

    // アイキャッチ画像のサポート
    add_theme_support( 'post-thumbnails' );

    // HTML5マークアップのサポート
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // カスタムロゴのサポート
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // ナビゲーションメニューの登録
    register_nav_menus( array(
        'header-menu'      => 'ヘッダーメニュー',
        'footer-menu-col1' => 'フッターメニュー（左列）',
        'footer-menu-col2' => 'フッターメニュー（右列）',
    ) );
}
add_action( 'after_setup_theme', 'akishima_setup' );


/**
 * ヘッダーメニューから「問い合わせ」を除外（header-cta / mobile-cta はテンプレート側で出力）
 */
function akishima_exclude_contact_from_header_menu( $items, $args ) {
    if ( empty( $args->theme_location ) || 'header-menu' !== $args->theme_location ) {
        return $items;
    }

    $contact_url = untrailingslashit( strtolower( home_url( '/contact' ) ) );

    return array_values(
        array_filter(
            $items,
            function ( $item ) use ( $contact_url ) {
                $item_url = untrailingslashit( strtolower( $item->url ) );
                if ( $contact_url === $item_url ) {
                    return false;
                }
                if ( in_array( $item->title, array( '問い合わせ', 'お問い合わせ' ), true ) ) {
                    return false;
                }
                return true;
            }
        )
    );
}
add_filter( 'wp_nav_menu_objects', 'akishima_exclude_contact_from_header_menu', 10, 2 );


/**
 * フッターメニューに「便利なリンク集」を追加（ヘッダーには出さない）
 */
function akishima_append_useful_links_to_footer_menu( $items, $args ) {
    if ( empty( $args->theme_location ) || 'footer-menu-col2' !== $args->theme_location ) {
        return $items;
    }

    $links_url = untrailingslashit( strtolower( akishima_useful_links_url() ) );

    foreach ( $items as $item ) {
        if ( untrailingslashit( strtolower( $item->url ) ) === $links_url ) {
            return $items;
        }
        if ( '便利なリンク集' === $item->title ) {
            return $items;
        }
    }

    $menu_item              = new stdClass();
    $menu_item->ID            = 1000001;
    $menu_item->db_id         = 1000001;
    $menu_item->title         = '便利なリンク集';
    $menu_item->url           = akishima_useful_links_url();
    $menu_item->menu_order    = 999;
    $menu_item->menu_item_parent = 0;
    $menu_item->type          = 'custom';
    $menu_item->object        = 'custom';
    $menu_item->object_id     = 1000001;
    $menu_item->classes       = array( 'menu-item', 'menu-item-useful-links' );
    $menu_item->target        = '';
    $menu_item->attr_title    = '';
    $menu_item->xfn           = '';
    $menu_item->current       = is_page( 'links' );
    $menu_item->current_item_ancestor = false;
    $menu_item->current_item_parent     = false;

    $items[] = $menu_item;
    return $items;
}
add_filter( 'wp_nav_menu_objects', 'akishima_append_useful_links_to_footer_menu', 20, 2 );


/**
 * Google Fonts 読み込み最適化
 */
function akishima_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
        );
        $urls[] = array(
            'href'        => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        );
    }

    return $urls;
}
add_filter( 'wp_resource_hints', 'akishima_resource_hints', 10, 2 );


/**
 * CSS・JSの読み込み
 */
function akishima_scripts() {
    wp_enqueue_style(
        'akishima-fonts',
        'https://fonts.googleapis.com/css2?family=Gidugu&family=Noto+Sans+JP:wght@400;500;600;700&display=swap',
        array(),
        null
    );

    // メインCSS
    wp_enqueue_style(
        'akishima-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get( 'Version' )
    );

    // カスタムCSS
    wp_enqueue_style(
        'akishima-main',
        get_template_directory_uri() . '/assets/css/main.css',
        array( 'akishima-style', 'akishima-fonts' ),
        filemtime( get_template_directory() . '/assets/css/main.css' )
    );

    // メインJS
    wp_enqueue_script(
        'akishima-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        filemtime( get_template_directory() . '/assets/js/main.js' ),
        true
    );

    // Leaflet.js（自治会の紹介ページのみ読み込み）
    // is_page_template でテンプレートファイル名で判定（スラッグに依存しない）
    if ( is_page_template( 'page-communities.php' ) || is_page( array( 'communities', '自治会の紹介' ) ) ) {
        wp_enqueue_style(
            'leaflet',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
            array(),
            '1.9.4'
        );
        // false = <head> 内で読み込み（インラインスクリプトより前に確実に読み込む）
        wp_enqueue_script(
            'leaflet',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
            array(),
            '1.9.4',
            false
        );
    }
}
add_action( 'wp_enqueue_scripts', 'akishima_scripts' );


/**
 * NEWSトップページ（page-news.php）の URL
 */
function akishima_news_page_url() {
    $news_page = get_page_by_path( 'news' );

    if ( $news_page && 'publish' === get_post_status( $news_page ) ) {
        return get_permalink( $news_page );
    }

    $pages = get_pages(
        array(
            'meta_key'   => '_wp_page_template',
            'meta_value' => 'page-news.php',
            'number'     => 1,
        )
    );

    if ( ! empty( $pages ) ) {
        return get_permalink( $pages[0]->ID );
    }

    return home_url( '/news/' );
}


/**
 * 個人情報の取扱規程ページ（/privacy-policy/）を自動作成
 */
function akishima_ensure_privacy_policy_page() {
    if ( get_option( 'akishima_privacy_policy_page_created' ) ) {
        return;
    }

    $page = get_page_by_path( 'privacy-policy' );

    if ( ! $page ) {
        wp_insert_post(
            array(
                'post_title'  => '個人情報の取扱規程',
                'post_name'   => 'privacy-policy',
                'post_status' => 'publish',
                'post_type'   => 'page',
            )
        );
    }

    update_option( 'akishima_privacy_policy_page_created', '1' );
}
add_action( 'init', 'akishima_ensure_privacy_policy_page' );


/**
 * 便利なリンク集ページ（/links/）を自動作成
 */
function akishima_ensure_useful_links_page() {
    if ( get_option( 'akishima_useful_links_page_created' ) ) {
        return;
    }

    $page = get_page_by_path( 'links' );

    if ( ! $page ) {
        wp_insert_post(
            array(
                'post_title'  => '便利なリンク集',
                'post_name'   => 'links',
                'post_status' => 'publish',
                'post_type'   => 'page',
            )
        );
    }

    update_option( 'akishima_useful_links_page_created', '1' );
}
add_action( 'init', 'akishima_ensure_useful_links_page' );


/**
 * お問い合わせフォームのバリデーション
 *
 * @param string $name    お名前
 * @param string $email   メールアドレス
 * @param string $message お問い合わせ内容
 * @param bool   $privacy 個人情報同意
 * @return string[] エラーコード配列
 */
function akishima_validate_contact_form( $name, $email, $message, $privacy ) {
    $errors = array();

    if ( '' === trim( $name ) ) {
        $errors[] = 'name';
    }

    if ( '' === trim( $email ) ) {
        $errors[] = 'email';
    } elseif ( ! is_email( $email ) ) {
        $errors[] = 'email_invalid';
    }

    if ( '' === trim( $message ) ) {
        $errors[] = 'message';
    }

    if ( ! $privacy ) {
        $errors[] = 'privacy';
    }

    return $errors;
}


/**
 * お問い合わせフォームのフィールドエラーメッセージ
 *
 * @param string[] $error_codes エラーコード配列
 * @return array<string, string> フィールドキー => メッセージ
 */
function akishima_contact_form_field_errors( $error_codes ) {
    $messages = array(
        'name'          => 'お名前を入力してください。',
        'email'         => 'メールアドレスを入力してください。',
        'email_invalid' => '正しいメールアドレスを入力してください。',
        'message'       => 'お問い合わせ内容を入力してください。',
        'privacy'       => '個人情報の取扱規程への同意が必要です。',
    );

    $field_errors = array(
        'name'    => '',
        'email'   => '',
        'message' => '',
        'privacy' => '',
    );

    foreach ( $error_codes as $code ) {
        if ( ! isset( $messages[ $code ] ) ) {
            continue;
        }

        if ( 'email_invalid' === $code ) {
            $field_errors['email'] = $messages[ $code ];
        } elseif ( isset( $field_errors[ $code ] ) ) {
            $field_errors[ $code ] = $messages[ $code ];
        }
    }

    return $field_errors;
}


/**
 * GET パラメータからお問い合わせフォームのエラーコードを取得
 *
 * @return string[]
 */
function akishima_contact_form_errors_from_request() {
    if ( empty( $_GET['_errors'] ) ) {
        return array();
    }

    $allowed = array( 'name', 'email', 'email_invalid', 'message', 'privacy' );
    $codes   = explode( ',', sanitize_text_field( wp_unslash( $_GET['_errors'] ) ) );

    return array_values( array_intersect( $codes, $allowed ) );
}


/**
 * メインサイトの NEWS トップ URL（子サイトからも参照）
 */
function akishima_main_news_page_url() {
    if ( ! is_multisite() ) {
        return akishima_news_page_url();
    }

    $main_id = get_main_site_id();
    if ( get_current_blog_id() === (int) $main_id ) {
        return akishima_news_page_url();
    }

    switch_to_blog( $main_id );
    $url = akishima_news_page_url();
    restore_current_blog();

    return $url;
}


/**
 * 子サイトでもメインサイトと同じヘッダーURL・メニューを使う
 */
function akishima_main_site_home_url() {
    if ( ! is_multisite() ) {
        return home_url( '/' );
    }

    return trailingslashit( get_home_url( get_main_site_id(), '/' ) );
}

function akishima_header_home_url() {
    if ( is_multisite() && ! is_main_site() ) {
        return akishima_main_site_home_url();
    }

    return home_url( '/' );
}

function akishima_header_contact_url() {
    if ( ! is_multisite() || is_main_site() ) {
        return home_url( '/contact' );
    }

    switch_to_blog( get_main_site_id() );
    $url = home_url( '/contact' );
    restore_current_blog();

    return $url;
}

/**
 * 子サイトではメインサイトに登録済みのナビメニューを表示
 *
 * @param array $args wp_nav_menu() の引数
 */
function akishima_wp_nav_menu_main_site( $args = array() ) {
    if ( ! is_multisite() || is_main_site() ) {
        wp_nav_menu( $args );
        return;
    }

    switch_to_blog( get_main_site_id() );
    wp_nav_menu( $args );
    restore_current_blog();
}


/**
 * お知らせカテゴリ別アーカイブ URL（/news/jichiren/ など）
 *
 * @param string $post_type jichiren|kairan|jichikai
 */
function akishima_news_archive_url( $post_type ) {
    $allowed = array( 'jichiren', 'kairan', 'jichikai' );
    if ( ! in_array( $post_type, $allowed, true ) ) {
        return get_post_type_archive_link( $post_type );
    }

    $link = get_post_type_archive_link( $post_type );
    if ( $link ) {
        return $link;
    }

    return home_url( '/news/' . $post_type . '/' );
}


/**
 * カスタム投稿タイプの登録
 */
function akishima_register_post_types() {

    // 自治連からのお知らせ
    register_post_type( 'jichiren', array(
        'labels' => array(
            'name'               => '自治連からのお知らせ',
            'singular_name'      => '自治連お知らせ',
            'add_new_item'       => '新規追加',
            'edit_item'          => '編集',
            'view_item'          => '表示',
            'all_items'          => '一覧',
            'search_items'       => '検索',
            'not_found'          => '見つかりません',
        ),
        'public'             => true,
        'show_in_rest'       => true,
        'has_archive'        => true,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'rewrite'            => array( 'slug' => 'news/jichiren', 'with_front' => false ),
        'menu_icon'          => 'dashicons-megaphone',
    ) );

    // 回覧
    register_post_type( 'kairan', array(
        'labels' => array(
            'name'               => '回覧',
            'singular_name'      => '回覧',
            'add_new_item'       => '新規追加',
            'edit_item'          => '編集',
            'view_item'          => '表示',
            'all_items'          => '一覧',
            'search_items'       => '検索',
            'not_found'          => '見つかりません',
        ),
        'public'             => true,
        'show_in_rest'       => true,
        'has_archive'        => true,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'rewrite'            => array( 'slug' => 'news/kairan', 'with_front' => false ),
        'menu_icon'          => 'dashicons-email',
    ) );

    // 自治会からのお知らせ
    register_post_type( 'jichikai', array(
        'labels' => array(
            'name'               => '自治会からのお知らせ',
            'singular_name'      => '自治会お知らせ',
            'add_new_item'       => '新規追加',
            'edit_item'          => '編集',
            'view_item'          => '表示',
            'all_items'          => '一覧',
            'search_items'       => '検索',
            'not_found'          => '見つかりません',
        ),
        'public'             => true,
        'show_in_rest'       => true,
        'has_archive'        => true,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'rewrite'            => array( 'slug' => 'news/jichikai', 'with_front' => false ),
        'menu_icon'          => 'dashicons-groups',
    ) );
}
add_action( 'init', 'akishima_register_post_types' );


/**
 * CPT アーカイブの表示件数を 9 件/ページに設定
 */
function akishima_cpt_posts_per_page( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }
    $cpt_slugs = array( 'jichiren', 'kairan', 'jichikai', 'community_event' );
    if ( $query->is_post_type_archive( $cpt_slugs ) ) {
        $query->set( 'posts_per_page', 9 );
    }
}
add_action( 'pre_get_posts', 'akishima_cpt_posts_per_page' );


/**
 * 回覧投稿タイプ: PDFメタボックス
 */
function akishima_kairan_pdf_metabox() {
    add_meta_box(
        'kairan-pdf-metabox',
        '回覧PDF',
        'akishima_kairan_pdf_render',
        'kairan',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'akishima_kairan_pdf_metabox' );

function akishima_kairan_pdf_render( $post ) {
    $pdf_url = get_post_meta( $post->ID, '_kairan_pdf_url', true );
    wp_nonce_field( 'akishima_kairan_pdf_nonce_action', 'akishima_kairan_pdf_nonce' );
    ?>
    <p style="margin-bottom:8px">PDFファイルを選択してください。</p>
    <div style="display:flex;gap:8px;align-items:center">
        <input
            type="text"
            id="kairan-pdf-url"
            name="kairan_pdf_url"
            value="<?php echo esc_url( $pdf_url ); ?>"
            style="flex:1"
            placeholder="https://..."
        >
        <button type="button" id="kairan-pdf-upload-btn" class="button">PDFを選択</button>
        <button type="button" id="kairan-pdf-clear-btn" class="button">クリア</button>
    </div>
    <?php if ( $pdf_url ) : ?>
        <p style="margin-top:6px;font-size:12px;color:#555">
            現在: <a href="<?php echo esc_url( $pdf_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $pdf_url ); ?></a>
        </p>
    <?php endif; ?>
    <script>
    (function($) {
        $('#kairan-pdf-upload-btn').on('click', function() {
            var uploader = wp.media({
                title: 'PDFファイルを選択',
                button: { text: '選択する' },
                library: { type: 'application/pdf' },
                multiple: false
            });
            uploader.on('select', function() {
                var attachment = uploader.state().get('selection').first().toJSON();
                $('#kairan-pdf-url').val(attachment.url);
            });
            uploader.open();
        });
        $('#kairan-pdf-clear-btn').on('click', function() {
            $('#kairan-pdf-url').val('');
        });
    })(jQuery);
    </script>
    <?php
}

function akishima_kairan_pdf_save( $post_id ) {
    if ( ! isset( $_POST['akishima_kairan_pdf_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['akishima_kairan_pdf_nonce'], 'akishima_kairan_pdf_nonce_action' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['kairan_pdf_url'] ) ) {
        update_post_meta( $post_id, '_kairan_pdf_url', esc_url_raw( $_POST['kairan_pdf_url'] ) );
    }
}
add_action( 'save_post_kairan', 'akishima_kairan_pdf_save' );


/**
 * ウィジェットエリアの登録
 */
function akishima_widgets_init() {
    register_sidebar( array(
        'name'          => 'サイドバー',
        'id'            => 'sidebar-1',
        'description'   => 'サイドバーにウィジェットを追加します。',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'akishima_widgets_init' );
