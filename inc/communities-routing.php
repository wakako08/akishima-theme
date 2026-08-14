<?php
/**
 * 自治会個別ページのルーティング（/01-01/ 形式・子サイト未作成時のメインサイトプレビュー用）
 * 本番: 子サイト（/wp3/01-01/）へリンク。未作成時はメインサイトで community.php を表示。
 */

/**
 * リライトルール登録
 */
function akishima_community_rewrite_rules() {
    add_rewrite_tag( '%akishima_community%', '([^&]+)' );
    add_rewrite_tag( '%akishima_block%', '([^&]+)' );
    add_rewrite_rule(
        '^([0-9]{2}-[0-9]+)/?$',
        'index.php?akishima_community=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^block-([0-9]{2})/?$',
        'index.php?akishima_block=$matches[1]',
        'top'
    );
}
add_action( 'init', 'akishima_community_rewrite_rules' );

/**
 * 全サイトのリライトルールをフラッシュ
 */
function akishima_flush_all_site_rewrite_rules() {
    if ( is_multisite() ) {
        $site_ids = get_sites( array( 'fields' => 'ids', 'number' => 500 ) );
        foreach ( $site_ids as $blog_id ) {
            switch_to_blog( $blog_id );
            akishima_community_rewrite_rules();
            if ( function_exists( 'akishima_subsite_news_rewrite_rules' ) ) {
                akishima_subsite_news_rewrite_rules();
            }
            flush_rewrite_rules( false );
            restore_current_blog();
        }
        update_site_option( 'akishima_community_rewrite_version', '7' );
        return;
    }

    akishima_community_rewrite_rules();
    if ( function_exists( 'akishima_subsite_news_rewrite_rules' ) ) {
        akishima_subsite_news_rewrite_rules();
    }
    flush_rewrite_rules( false );
    update_option( 'akishima_community_rewrite_version', '7' );
}

/**
 * テーマ有効化時にパーマリンクをフラッシュ
 */
function akishima_community_flush_rewrites() {
    akishima_flush_all_site_rewrite_rules();
}
add_action( 'after_switch_theme', 'akishima_community_flush_rewrites' );

/**
 * リライトルール追加後の初回フラッシュ（ローカル開発用）
 */
function akishima_community_maybe_flush_rewrites() {
    $version = is_multisite()
        ? get_site_option( 'akishima_community_rewrite_version' )
        : get_option( 'akishima_community_rewrite_version' );

    if ( '7' === $version ) {
        return;
    }

    akishima_flush_all_site_rewrite_rules();
}
add_action( 'init', 'akishima_community_maybe_flush_rewrites', 99 );

/**
 * 子サイトのパス（例: /wp3/01-01/ /wp3/block-01/）からスラッグを取得
 */
function akishima_get_subsite_community_slug() {
    if ( ! is_multisite() || is_main_site() ) {
        return '';
    }

    $site = get_site( get_current_blog_id() );
    if ( ! $site || empty( $site->path ) ) {
        return '';
    }

    $slug = basename( untrailingslashit( $site->path ) );
    if ( preg_match( '/^\d{2}-\d+$/', $slug ) || preg_match( '/^block-\d{2}$/', $slug ) ) {
        return $slug;
    }

    return '';
}

function akishima_get_subsite_block_no() {
    if ( ! is_multisite() || is_main_site() ) {
        return '';
    }

    $site = get_site( get_current_blog_id() );
    if ( ! $site || empty( $site->path ) ) {
        return '';
    }

    $slug = basename( untrailingslashit( $site->path ) );
    if ( preg_match( '/^block-(\d{2})$/', $slug, $m ) ) {
        return $m[1];
    }

    return '';
}

/**
 * 子サイトのトップを自治会／ブロックページとして表示（同一テンプレ）
 */
function akishima_subsite_community_template_include( $template ) {
    if ( is_admin() || is_main_site() ) {
        return $template;
    }

    if ( get_query_var( 'akishima_subsite_news' ) ) {
        return $template;
    }

    if ( ! is_front_page() && ! is_home() ) {
        return $template;
    }

    $slug = akishima_get_subsite_community_slug();
    if ( '' === $slug ) {
        return $template;
    }

    $community = akishima_get_community_by_slug( $slug );
    if ( ! $community ) {
        return $template;
    }

    $GLOBALS['akishima_current_community'] = $community;
    set_query_var( 'akishima_community_data', $community );

    if ( ! empty( $community['is_block'] ) ) {
        $GLOBALS['akishima_current_block'] = $community;
        set_query_var( 'akishima_block_data', $community );
    }

    // ブロックも自治会と同じ community.php を使う
    $community_template = locate_template( 'community.php' );
    if ( $community_template ) {
        return $community_template;
    }

    return $template;
}
add_filter( 'template_include', 'akishima_subsite_community_template_include', 98 );

/**
 * blog_id から自治会／ブロックスラッグを取得
 */
function akishima_get_community_slug_by_blog_id( $blog_id ) {
    $site = get_site( $blog_id );
    if ( ! $site || empty( $site->path ) ) {
        return '';
    }

    $slug = basename( untrailingslashit( $site->path ) );
    if ( preg_match( '/^\d{2}-\d+$/', $slug ) || preg_match( '/^block-\d{2}$/', $slug ) ) {
        return $slug;
    }

    return '';
}

/**
 * blog_id からマスターデータのサイト名を取得（自治会 or ブロック）
 */
function akishima_get_community_name_by_blog_id( $blog_id ) {
    $site = get_site( $blog_id );
    if ( ! $site || empty( $site->path ) ) {
        return '';
    }

    $slug = basename( untrailingslashit( $site->path ) );

    if ( preg_match( '/^\d{2}-\d+$/', $slug ) ) {
        $community = akishima_get_community_by_slug( $slug );
        if ( $community && ! empty( $community['name'] ) ) {
            return $community['name'];
        }
        return '';
    }

    if ( preg_match( '/^block-(\d{2})$/', $slug, $m ) ) {
        $block = function_exists( 'akishima_get_block_by_no' ) ? akishima_get_block_by_no( (int) $m[1] ) : null;
        if ( $block && ! empty( $block['name'] ) ) {
            return $block['name'];
        }
        return '第' . (int) $m[1] . 'ブロック';
    }

    return '';
}

/**
 * 参加サイト一覧など: 子サイト名をドメインではなく自治会名で表示
 */
function akishima_filter_subsite_blogname_option( $value, $blog_id, $option ) {
    static $running = false;

    if ( $running || 'blogname' !== $option || ! is_multisite() ) {
        return $value;
    }

    if ( (int) $blog_id === (int) get_main_site_id() ) {
        return $value;
    }

    $running = true;
    $name    = akishima_get_community_name_by_blog_id( $blog_id );
    $running = false;

    return $name ? $name : $value;
}
add_filter( 'get_blog_option', 'akishima_filter_subsite_blogname_option', 10, 3 );

/**
 * 子サイト表示中のサイト名（ブラウザタイトル等）
 */
function akishima_filter_current_subsite_blogname( $value ) {
    static $running = false;

    if ( $running || ! is_multisite() || is_main_site() ) {
        return $value;
    }

    $running = true;
    $name    = akishima_get_community_name_by_blog_id( get_current_blog_id() );
    $running = false;

    return $name ? $name : $value;
}
add_filter( 'option_blogname', 'akishima_filter_current_subsite_blogname' );

/**
 * 自治会・ブロック子サイト作成時: テーマ有効化・リライト登録・サイト名設定
 */
function akishima_setup_new_community_site( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
    if ( ! is_multisite() ) {
        return;
    }

    $slug = basename( untrailingslashit( $path ) );
    $site_name = '';

    if ( preg_match( '/^\d{2}-\d+$/', $slug ) ) {
        $community = akishima_get_community_by_slug( $slug );
        if ( $community && ! empty( $community['name'] ) ) {
            $site_name = $community['name'];
        }
    } elseif ( preg_match( '/^block-(\d{2})$/', $slug, $m ) ) {
        $block = function_exists( 'akishima_get_block_by_no' ) ? akishima_get_block_by_no( (int) $m[1] ) : null;
        if ( $block && ! empty( $block['name'] ) ) {
            $site_name = $block['name'];
        } else {
            $site_name = '第' . (int) $m[1] . 'ブロック';
        }
    } else {
        return;
    }

    switch_to_blog( $blog_id );

    if ( $site_name ) {
        update_option( 'blogname', $site_name );
    }

    if ( wp_get_theme( 'akishima' )->exists() ) {
        switch_theme( 'akishima' );
    }

    akishima_community_rewrite_rules();
    if ( function_exists( 'akishima_subsite_news_rewrite_rules' ) ) {
        akishima_subsite_news_rewrite_rules();
    }
    flush_rewrite_rules( false );

    restore_current_blog();

    if ( $site_name ) {
        update_blog_details(
            $blog_id,
            array(
                'blogname' => $site_name,
            )
        );
    }
}
add_action( 'wpmu_new_blog', 'akishima_setup_new_community_site', 10, 6 );

/**
 * 自治会ページテンプレートを読み込む（メインサイトの /01-01/ プレビュー用）
 */
function akishima_community_template_include( $template ) {
    $slug = get_query_var( 'akishima_community' );
    if ( ! empty( $slug ) ) {
        $community = akishima_get_community_by_slug( $slug );
        if ( ! $community ) {
            global $wp_query;
            $wp_query->set_404();
            status_header( 404 );
            nocache_headers();
            return get_404_template();
        }

        $GLOBALS['akishima_current_community'] = $community;
        set_query_var( 'akishima_community_data', $community );

        $community_template = locate_template( 'community.php' );
        if ( $community_template ) {
            return $community_template;
        }
        return $template;
    }

    $block_no = get_query_var( 'akishima_block' );
    if ( empty( $block_no ) ) {
        return $template;
    }

    $community = akishima_get_community_by_slug( 'block-' . sprintf( '%02d', (int) $block_no ) );
    if ( ! $community ) {
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
        nocache_headers();
        return get_404_template();
    }

    $GLOBALS['akishima_current_community'] = $community;
    $GLOBALS['akishima_current_block']     = $community;
    set_query_var( 'akishima_community_data', $community );
    set_query_var( 'akishima_block_data', $community );

    // メインサイト /block-01/ プレビューも自治会ページと同一テンプレ
    $community_template = locate_template( 'community.php' );
    if ( $community_template ) {
        return $community_template;
    }

    $block_template = locate_template( 'block.php' );
    if ( $block_template ) {
        return $block_template;
    }

    return $template;
}
add_filter( 'template_include', 'akishima_community_template_include', 99 );

/**
 * 自治会ページの document title
 */
function akishima_community_document_title( $title ) {
    $community = get_query_var( 'akishima_community_data' );
    if ( is_array( $community ) && ! empty( $community['name'] ) ) {
        $title['title'] = $community['name'];
    }
    return $title;
}
add_filter( 'document_title_parts', 'akishima_community_document_title' );
