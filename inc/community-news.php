<?php
/**
 * 自治会個別ページ: お知らせ（子サイトCMS連携）
 *
 * 子サイトで投稿 → 子サイトNEWS + メインNEWS（全自治会集約）に表示。
 * 子サイト VIEW ALL: /wp3/01-01/news/
 * メイン VIEW ALL:   /wp3/news/jichikai/
 */

/**
 * ブログパスから自治会／ブロックスラッグを取得（例: /wp3/01-01/ → 01-01, /wp3/block-01/ → block-01）
 */
function akishima_community_slug_from_blog_path( $path ) {
    $slug = basename( untrailingslashit( (string) $path ) );
    if ( preg_match( '/^\d{2}-\d+$/', $slug ) || preg_match( '/^block-\d{2}$/', $slug ) ) {
        return $slug;
    }
    return '';
}

/**
 * 自治会スラッグ → 子サイト blog_id
 */
function akishima_get_community_blog_ids() {
    static $map = null;
    if ( null !== $map ) {
        return $map;
    }
    $map = array();
    if ( ! is_multisite() ) {
        return $map;
    }
    foreach ( get_sites( array( 'number' => 200 ) ) as $site ) {
        if ( is_main_site( $site->blog_id ) ) {
            continue;
        }
        $slug = akishima_community_slug_from_blog_path( $site->path );
        if ( $slug ) {
            $map[ $slug ] = (int) $site->blog_id;
        }
    }
    return $map;
}

/**
 * 自治会お知らせのクエリ引数（単一サイト内）
 */
function akishima_community_news_query_args( $community, $per_page = 4 ) {
    $slug = isset( $community['slug'] ) ? $community['slug'] : '';

    $args = array(
        'post_type'           => 'jichikai',
        'posts_per_page'      => $per_page,
        'post_status'         => 'publish',
        'ignore_sticky_posts' => true,
        'orderby'             => 'date',
        'order'               => 'DESC',
    );

    if ( $slug ) {
        $args['meta_query'] = array(
            array(
                'key'     => '_community_slug',
                'value'   => $slug,
                'compare' => '=',
            ),
        );
    }

    return apply_filters( 'akishima_community_news_query_args', $args, $community );
}

/**
 * 自治会お知らせ WP_Query（子サイトトップ用・現在のサイト内）
 */
function akishima_get_community_news_query( $community, $per_page = 4 ) {
    return new WP_Query( akishima_community_news_query_args( $community, $per_page ) );
}

/**
 * ネットワーク全体の jichikai 投稿を収集（メイン集約・子サイト一覧用）
 *
 * @return object { posts, post_count, found_posts, max_num_pages }
 */
function akishima_get_network_jichikai_posts( $args = array() ) {
    $defaults = array(
        'posts_per_page' => 9,
        'paged'          => 1,
        'community_slug' => '',
    );
    $args = wp_parse_args( $args, $defaults );

    $result = (object) array(
        'posts'         => array(),
        'post_count'    => 0,
        'found_posts'   => 0,
        'max_num_pages' => 0,
    );

    $per_page_raw = (int) $args['posts_per_page'];
    $fetch_all    = ( -1 === $per_page_raw );
    $per_page     = $fetch_all ? 1 : max( 1, $per_page_raw );
    $paged        = max( 1, (int) $args['paged'] );
    $sources      = array();

    if ( is_multisite() && ! is_main_site() ) {
        $current_slug = function_exists( 'akishima_get_subsite_community_slug' ) ? akishima_get_subsite_community_slug() : '';
        if ( ! empty( $args['community_slug'] ) ) {
            $current_slug = $args['community_slug'];
        }
        // 子サイトは当該ブログの投稿のみ（他自治会は含めない）
        $sources[ get_current_blog_id() ] = $current_slug;
    } elseif ( is_multisite() && is_main_site() ) {
        if ( ! empty( $args['community_slug'] ) ) {
            $blog_map = akishima_get_community_blog_ids();
            $slug     = $args['community_slug'];
            if ( isset( $blog_map[ $slug ] ) ) {
                $sources[ $blog_map[ $slug ] ] = $slug;
            }
        } else {
            $sources[ get_main_site_id() ] = '';
            foreach ( akishima_get_community_blog_ids() as $slug => $blog_id ) {
                $sources[ $blog_id ] = $slug;
            }
        }
    } else {
        $sources[ get_current_blog_id() ] = isset( $args['community_slug'] ) ? $args['community_slug'] : '';
    }

    $collected = array();

    foreach ( $sources as $blog_id => $community_slug ) {
        switch_to_blog( $blog_id );

        $query_args = array(
            'post_type'      => 'jichikai',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        if ( $community_slug ) {
            $query_args['meta_query'] = array(
                array(
                    'key'     => '_community_slug',
                    'value'   => $community_slug,
                    'compare' => '=',
                ),
            );
        }

        $query = new WP_Query( $query_args );
        foreach ( $query->posts as $post ) {
            $post->akishima_blog_id         = (int) $blog_id;
            $post->akishima_community_slug  = $community_slug ? $community_slug : (string) get_post_meta( $post->ID, '_community_slug', true );
            $post->akishima_permalink       = get_permalink( $post );
            $collected[]                    = $post;
        }

        restore_current_blog();
    }

    usort(
        $collected,
        function ( $a, $b ) {
            return strtotime( $b->post_date ) - strtotime( $a->post_date );
        }
    );

    $result->found_posts   = count( $collected );
    $result->max_num_pages = $fetch_all ? 1 : (int) ceil( $result->found_posts / $per_page );
    if ( $fetch_all ) {
        $result->posts      = $collected;
        $result->post_count = count( $collected );
    } else {
        $offset                = ( $paged - 1 ) * $per_page;
        $result->posts         = array_slice( $collected, $offset, $per_page );
        $result->post_count    = count( $result->posts );
    }

    return $result;
}

/**
 * TOPお知らせ固定用メタキー
 */
function akishima_home_news_pin_meta_key() {
    return '_akishima_home_pin';
}

/**
 * TOPお知らせに固定されているか
 *
 * @param int $post_id  投稿ID
 * @param int $blog_id  マルチサイト時のブログID（0なら現在のブログ）
 */
function akishima_is_home_news_pinned( $post_id, $blog_id = 0 ) {
    $blog_id  = (int) $blog_id;
    $meta_key = akishima_home_news_pin_meta_key();

    if ( $blog_id && is_multisite() && get_current_blog_id() !== $blog_id ) {
        switch_to_blog( $blog_id );
        $pinned = '1' === get_post_meta( $post_id, $meta_key, true );
        restore_current_blog();
        return $pinned;
    }

    return '1' === get_post_meta( $post_id, $meta_key, true );
}

/**
 * トップNEWSカード用に投稿データを正規化
 *
 * @param WP_Post $post           投稿オブジェクト
 * @param string  $category_label カテゴリ表示名
 */
function akishima_normalize_home_news_item( $post, $category_label ) {
    $permalink = ! empty( $post->akishima_permalink ) ? $post->akishima_permalink : get_permalink( $post );
    $excerpt   = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( $post->post_content, 60, '…' );

    if ( is_multisite() && is_main_site() ) {
        $permalink = add_query_arg( 'from', 'main', $permalink );
    } elseif ( ! empty( $post->akishima_community_slug ) ) {
        $permalink = add_query_arg( 'from_community', sanitize_title( $post->akishima_community_slug ), $permalink );
    }

    $blog_id = ! empty( $post->akishima_blog_id ) ? (int) $post->akishima_blog_id : 0;

    return (object) array(
        'post'            => $post,
        'permalink'       => $permalink,
        'title'           => get_the_title( $post ),
        'excerpt'         => $excerpt,
        'category_label'  => $category_label,
        'timestamp'       => strtotime( $post->post_date ),
        'is_pinned'       => akishima_is_home_news_pinned( $post->ID, $blog_id ),
    );
}

/**
 * トップページNEWS: 自治連・回覧・自治会の最新記事を日付順で取得
 *
 * @param int $limit 取得件数
 * @return object[]
 */
function akishima_get_home_news_posts( $limit = 15 ) {
    $limit  = max( 1, (int) $limit );
    $labels = array(
        'jichiren' => '自治連からのお知らせ',
        'kairan'   => '回覧',
        'jichikai' => '自治会からのお知らせ',
    );
    $items  = array();

    foreach ( array( 'jichiren', 'kairan' ) as $post_type ) {
        $query = new WP_Query(
            array(
                'post_type'           => $post_type,
                'posts_per_page'      => -1,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true,
                'orderby'             => 'date',
                'order'               => 'DESC',
            )
        );

        foreach ( $query->posts as $post ) {
            $items[] = akishima_normalize_home_news_item( $post, $labels[ $post_type ] );
        }

        wp_reset_postdata();
    }

    if ( is_multisite() && is_main_site() ) {
        $collection = akishima_get_network_jichikai_posts(
            array(
                'posts_per_page' => -1,
                'paged'          => 1,
            )
        );
        foreach ( $collection->posts as $post ) {
            $items[] = akishima_normalize_home_news_item( $post, $labels['jichikai'] );
        }
    } else {
        $query = new WP_Query(
            array(
                'post_type'           => 'jichikai',
                'posts_per_page'      => -1,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true,
                'orderby'             => 'date',
                'order'               => 'DESC',
            )
        );

        foreach ( $query->posts as $post ) {
            $items[] = akishima_normalize_home_news_item( $post, $labels['jichikai'] );
        }

        wp_reset_postdata();
    }

    usort(
        $items,
        function ( $a, $b ) {
            if ( $a->is_pinned !== $b->is_pinned ) {
                return $b->is_pinned <=> $a->is_pinned;
            }

            return $b->timestamp - $a->timestamp;
        }
    );

    return array_slice( $items, 0, $limit );
}

/**
 * カスタムクエリ変数
 */
function akishima_register_query_vars( $vars ) {
    $vars[] = 'akishima_subsite_news';
    $vars[] = 'akishima_community';
    return $vars;
}
add_filter( 'query_vars', 'akishima_register_query_vars' );

/**
 * 子サイト /news/ リライト
 */
function akishima_subsite_news_rewrite_rules() {
    if ( ! is_multisite() || is_main_site() ) {
        return;
    }
    add_rewrite_tag( '%akishima_subsite_news%', '1' );
    add_rewrite_rule( '^news/page/([0-9]{1,})/?$', 'index.php?akishima_subsite_news=1&paged=$matches[1]', 'top' );
    add_rewrite_rule( '^news/?$', 'index.php?akishima_subsite_news=1', 'top' );
}
add_action( 'init', 'akishima_subsite_news_rewrite_rules', 11 );

/**
 * 自治会お知らせ一覧の見出し（例: 郷地第一自治会からのお知らせ）
 *
 * @param array|string|null $community_or_slug 自治会データまたはスラッグ
 */
function akishima_community_news_archive_label( $community_or_slug = null ) {
    $community = null;

    if ( is_string( $community_or_slug ) && '' !== $community_or_slug ) {
        $community = akishima_get_community_by_slug( $community_or_slug );
    } elseif ( is_array( $community_or_slug ) ) {
        $community = $community_or_slug;
    } elseif ( ! is_main_site() && is_multisite() && function_exists( 'akishima_get_subsite_community_slug' ) ) {
        $slug = akishima_get_subsite_community_slug();
        if ( $slug ) {
            $community = akishima_get_community_by_slug( $slug );
        }
    }

    if ( $community && ! empty( $community['name'] ) ) {
        return $community['name'] . 'からのお知らせ';
    }

    return '自治会からのお知らせ';
}

/**
 * メインサイト /news/ を3セクションのNEWSトップとして表示
 * （子サイト用リライトが誤ってメインに残っている場合のフォールバック含む）
 */
function akishima_main_news_top_template_include( $template ) {
    if ( ! is_multisite() || ! is_main_site() || is_admin() ) {
        return $template;
    }

    if ( is_singular() || is_post_type_archive( array( 'jichiren', 'kairan', 'jichikai' ) ) ) {
        return $template;
    }

    $is_news_top = is_page( 'news' ) || (bool) get_query_var( 'akishima_subsite_news' );
    if ( ! $is_news_top ) {
        return $template;
    }

    $news_tpl = locate_template( 'page-news.php' );
    return $news_tpl ? $news_tpl : $template;
}
add_filter( 'template_include', 'akishima_main_news_top_template_include', 99 );

/**
 * 子サイト /news/ テンプレート
 */
function akishima_subsite_news_template_include( $template ) {
    if ( is_main_site() ) {
        return $template;
    }

    if ( ! get_query_var( 'akishima_subsite_news' ) ) {
        return $template;
    }
    $news_template = locate_template( 'community-news-archive.php' );
    if ( $news_template ) {
        return $news_template;
    }
    return $template;
}
add_filter( 'template_include', 'akishima_subsite_news_template_include', 100 );

/**
 * 自治会個別ページへの戻りURL
 */
function akishima_community_news_back_url( $community_or_slug ) {
    if ( is_string( $community_or_slug ) ) {
        $community = akishima_get_community_by_slug( $community_or_slug );
    } elseif ( is_array( $community_or_slug ) ) {
        $community = $community_or_slug;
    } else {
        $community = null;
    }

    if ( ! $community ) {
        return '';
    }

    $url = akishima_community_url( $community );
    return ( $url && '#' !== $url ) ? $url : '';
}

/**
 * お知らせ詳細の戻り先（メイン集約・自治会ページ・子サイトを判別）
 *
 * @param string|null $post_type 投稿タイプ
 * @param int|null    $post_id   投稿ID
 */
function akishima_news_single_back_url( $post_type = null, $post_id = null ) {
    if ( null === $post_type ) {
        $post_type = get_post_type( $post_id ?: null );
    }
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $from = isset( $_GET['from'] ) ? sanitize_key( wp_unslash( $_GET['from'] ) ) : '';
    if ( in_array( $from, array( 'main', 'home', 'news' ), true ) ) {
        $archive = akishima_news_archive_url( $post_type );
        return $archive ?: akishima_news_page_url();
    }

    if ( 'jichikai' === $post_type ) {
        return akishima_jichikai_single_back_url( $post_id );
    }

    if ( ! is_main_site() && is_multisite() ) {
        return home_url( '/news/' );
    }

    $archive = akishima_news_archive_url( $post_type );
    return $archive ?: akishima_news_page_url();
}

/**
 * 自治会お知らせ詳細の戻り先
 */
function akishima_jichikai_single_back_url( $post_id = null ) {
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }

    $from = isset( $_GET['from'] ) ? sanitize_key( wp_unslash( $_GET['from'] ) ) : '';
    if ( in_array( $from, array( 'main', 'home', 'news' ), true ) ) {
        return akishima_news_archive_url( 'jichikai' ) ?: akishima_news_page_url();
    }

    $from_community = isset( $_GET['from_community'] )
        ? sanitize_title( wp_unslash( $_GET['from_community'] ) )
        : '';

    // メインサイトの集約表示では自治会トップへ戻さない
    if ( ! $from_community && $post_id && ! is_main_site() ) {
        $from_community = (string) get_post_meta( $post_id, '_community_slug', true );
    }

    if ( $from_community ) {
        $back = akishima_community_news_back_url( $from_community );
        if ( $back ) {
            return $back;
        }
    }

    if ( ! is_main_site() && is_multisite() ) {
        return home_url( '/news/' );
    }

    return akishima_news_archive_url( 'jichikai' ) ?: akishima_news_page_url();
}

/**
 * お知らせ一覧（VIEW ALL）URL
 */
function akishima_community_news_archive_url( $community ) {
    if ( ! is_main_site() && is_multisite() ) {
        return home_url( '/news/' );
    }

    $external = '';
    if ( ! empty( $community['external_url'] ) && '#' !== $community['external_url'] ) {
        $external = $community['external_url'];
    }

    if ( $external ) {
        return apply_filters(
            'akishima_community_news_archive_url_external',
            trailingslashit( $external ) . 'news/',
            $community
        );
    }

    $slug = isset( $community['slug'] ) ? $community['slug'] : '';
    $base = akishima_news_archive_url( 'jichikai' );
    if ( ! $base ) {
        $base = home_url( '/news/jichikai/' );
    }

    return add_query_arg( 'community', $slug, $base );
}

/**
 * メインサイトの jichikai アーカイブをネットワーク集約に差し替え
 */
function akishima_main_jichikai_archive_use_network( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }
    if ( ! is_multisite() || ! is_main_site() ) {
        return;
    }
    if ( ! $query->is_post_type_archive( 'jichikai' ) ) {
        return;
    }
    $query->set( 'post__in', array( 0 ) );
}
add_action( 'pre_get_posts', 'akishima_main_jichikai_archive_use_network' );

/**
 * アーカイブで community クエリを絞り込み（メイン・単一サイト用）
 */
function akishima_filter_jichikai_archive_by_community( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }
    if ( ! $query->is_post_type_archive( 'jichikai' ) ) {
        return;
    }
    if ( is_multisite() && is_main_site() ) {
        return;
    }

    $community_slug = isset( $_GET['community'] ) ? sanitize_title( wp_unslash( $_GET['community'] ) ) : '';
    if ( empty( $community_slug ) ) {
        return;
    }

    $meta_query   = (array) $query->get( 'meta_query' );
    $meta_query[] = array(
        'key'     => '_community_slug',
        'value'   => $community_slug,
        'compare' => '=',
    );
    $query->set( 'meta_query', $meta_query );
}
add_action( 'pre_get_posts', 'akishima_filter_jichikai_archive_by_community' );

/**
 * 子サイト投稿時に自治会スラッグを常に自動設定（他自治会への紐づけ不可）
 */
function akishima_jichikai_force_subsite_community_slug( $post_id ) {
    if ( is_main_site() || ! is_multisite() || 'jichikai' !== get_post_type( $post_id ) ) {
        return;
    }

    $slug = function_exists( 'akishima_get_subsite_community_slug' ) ? akishima_get_subsite_community_slug() : '';
    if ( $slug ) {
        update_post_meta( $post_id, '_community_slug', $slug );
    }
}
add_action( 'save_post_jichikai', 'akishima_jichikai_force_subsite_community_slug', 20 );

/**
 * 子サイト管理画面: 他自治会のお知らせを一覧に表示しない
 */
function akishima_jichikai_admin_limit_to_subsite_community( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() || ! is_multisite() || is_main_site() ) {
        return;
    }

    global $pagenow;
    if ( 'edit.php' !== $pagenow || 'jichikai' !== $query->get( 'post_type' ) ) {
        return;
    }

    $slug = function_exists( 'akishima_get_subsite_community_slug' ) ? akishima_get_subsite_community_slug() : '';
    if ( ! $slug ) {
        return;
    }

    $meta_query   = (array) $query->get( 'meta_query' );
    $meta_query[] = array(
        'relation' => 'OR',
        array(
            'key'     => '_community_slug',
            'value'   => $slug,
            'compare' => '=',
        ),
        array(
            'key'     => '_community_slug',
            'compare' => 'NOT EXISTS',
        ),
    );
    $query->set( 'meta_query', $meta_query );
}
add_action( 'pre_get_posts', 'akishima_jichikai_admin_limit_to_subsite_community' );

/**
 * 子サイト: 他自治会に紐づくお知らせの編集を拒否
 */
function akishima_jichikai_block_cross_community_edit() {
    if ( ! is_admin() || is_main_site() || ! is_multisite() ) {
        return;
    }

    global $pagenow;
    if ( 'post.php' !== $pagenow || empty( $_GET['post'] ) ) {
        return;
    }

    $post_id = (int) $_GET['post'];
    if ( 'jichikai' !== get_post_type( $post_id ) ) {
        return;
    }

    $expected = function_exists( 'akishima_get_subsite_community_slug' ) ? akishima_get_subsite_community_slug() : '';
    if ( ! $expected ) {
        return;
    }

    $actual = (string) get_post_meta( $post_id, '_community_slug', true );
    if ( $actual && $actual !== $expected ) {
        wp_die(
            '<p>このお知らせは他の自治会に属しているため、編集できません。</p><p><a href="' . esc_url( admin_url( 'edit.php?post_type=jichikai' ) ) . '">一覧へ戻る</a></p>',
            '編集できません',
            array( 'response' => 403 )
        );
    }
}
add_action( 'load-post.php', 'akishima_jichikai_block_cross_community_edit' );

/**
 * メインサイトでは自治会お知らせの投稿・編集を不可（各子サイトからのみ）
 */
function akishima_jichikai_block_main_site_admin_access() {
    if ( ! is_multisite() || ! is_main_site() || ! is_admin() ) {
        return;
    }

    global $pagenow;
    $post_type = '';

    if ( 'post-new.php' === $pagenow ) {
        $post_type = isset( $_GET['post_type'] ) ? sanitize_key( wp_unslash( $_GET['post_type'] ) ) : 'post';
    } elseif ( 'post.php' === $pagenow && ! empty( $_GET['post'] ) ) {
        $post_type = get_post_type( (int) $_GET['post'] );
    } elseif ( 'edit.php' === $pagenow ) {
        $post_type = isset( $_GET['post_type'] ) ? sanitize_key( wp_unslash( $_GET['post_type'] ) ) : '';
    }

    if ( 'jichikai' !== $post_type ) {
        return;
    }

    wp_die(
        '<p>自治会からのお知らせは、各自治会子サイトの管理画面から投稿・編集してください。</p>' .
        '<p><a href="' . esc_url( admin_url( 'index.php' ) ) . '">ダッシュボードへ戻る</a></p>',
        '自治会からのお知らせ',
        array( 'response' => 403 )
    );
}
add_action( 'load-post-new.php', 'akishima_jichikai_block_main_site_admin_access' );
add_action( 'load-post.php', 'akishima_jichikai_block_main_site_admin_access' );
add_action( 'load-edit.php', 'akishima_jichikai_block_main_site_admin_access' );

/**
 * メインサイト管理画面メニューから自治会お知らせを非表示
 */
function akishima_jichikai_remove_main_site_admin_menu() {
    if ( ! is_multisite() || ! is_main_site() || ! is_admin() ) {
        return;
    }

    remove_menu_page( 'edit.php?post_type=jichikai' );
}
add_action( 'admin_menu', 'akishima_jichikai_remove_main_site_admin_menu', 999 );

/**
 * メインサイト専用お知らせ（自治連・回覧）の post type
 */
function akishima_main_site_only_news_post_types() {
    return array( 'jichiren', 'kairan' );
}

/**
 * 子サイトでは自治連・回覧の管理画面アクセスを不可
 */
function akishima_main_only_news_block_subsite_admin_access() {
    if ( ! is_multisite() || is_main_site() || ! is_admin() ) {
        return;
    }

    global $pagenow;
    $blocked_types = akishima_main_site_only_news_post_types();
    $post_type     = '';

    if ( 'post-new.php' === $pagenow ) {
        $post_type = isset( $_GET['post_type'] ) ? sanitize_key( wp_unslash( $_GET['post_type'] ) ) : 'post';
    } elseif ( 'post.php' === $pagenow && ! empty( $_GET['post'] ) ) {
        $post_type = get_post_type( (int) $_GET['post'] );
    } elseif ( 'edit.php' === $pagenow ) {
        $post_type = isset( $_GET['post_type'] ) ? sanitize_key( wp_unslash( $_GET['post_type'] ) ) : '';
    }

    if ( ! $post_type || ! in_array( $post_type, $blocked_types, true ) ) {
        return;
    }

    $labels = array(
        'jichiren' => '自治連からのお知らせ',
        'kairan'   => '回覧',
    );
    $label     = isset( $labels[ $post_type ] ) ? $labels[ $post_type ] : 'お知らせ';
    $main_url  = get_admin_url( get_main_site_id(), 'edit.php?post_type=' . $post_type );
    $back_url  = admin_url( 'index.php' );

    wp_die(
        '<p>' . esc_html( $label ) . 'はメインサイトの管理画面からのみ投稿・編集できます。</p>' .
        '<p><a href="' . esc_url( $main_url ) . '">メインサイトの' . esc_html( $label ) . '一覧へ</a></p>' .
        '<p><a href="' . esc_url( $back_url ) . '">ダッシュボードへ戻る</a></p>',
        esc_html( $label ),
        array( 'response' => 403 )
    );
}
add_action( 'load-post-new.php', 'akishima_main_only_news_block_subsite_admin_access' );
add_action( 'load-post.php', 'akishima_main_only_news_block_subsite_admin_access' );
add_action( 'load-edit.php', 'akishima_main_only_news_block_subsite_admin_access' );

/**
 * 子サイト管理画面メニューから自治連・回覧を非表示
 */
function akishima_main_only_news_remove_subsite_admin_menus() {
    if ( ! is_multisite() || is_main_site() || ! is_admin() ) {
        return;
    }

    foreach ( akishima_main_site_only_news_post_types() as $post_type ) {
        remove_menu_page( 'edit.php?post_type=' . $post_type );
    }
}
add_action( 'admin_menu', 'akishima_main_only_news_remove_subsite_admin_menus', 999 );

/**
 * 管理画面: 自治会スラッグメタボックス
 */
function akishima_jichikai_community_metabox() {
    if ( is_multisite() && is_main_site() ) {
        return;
    }

    add_meta_box(
        'jichikai-community-slug',
        '投稿先自治会',
        'akishima_jichikai_community_metabox_render',
        'jichikai',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'akishima_jichikai_community_metabox' );

function akishima_jichikai_community_metabox_render( $post ) {
    wp_nonce_field( 'akishima_jichikai_community_slug', 'akishima_jichikai_community_slug_nonce' );

    if ( ! is_main_site() && is_multisite() ) {
        $slug      = function_exists( 'akishima_get_subsite_community_slug' ) ? akishima_get_subsite_community_slug() : '';
        $community = $slug ? akishima_get_community_by_slug( $slug ) : null;
        $name      = $community && ! empty( $community['name'] ) ? $community['name'] : ( $slug ? $slug : '—' );
        ?>
        <p style="margin:0;font-size:13px;font-weight:600;color:#002239">
            <?php echo esc_html( $name ); ?>
        </p>
        <p style="margin:8px 0 0;font-size:12px;color:#555;line-height:1.6">
            この子サイトのお知らせとして公開されます。他の自治会のお知らせは投稿・編集できません。メインサイトのNEWSにも表示されます。
        </p>
        <?php
        return;
    }

    $slug      = get_post_meta( $post->ID, '_community_slug', true );
    $community = $slug ? akishima_get_community_by_slug( $slug ) : null;
    $name      = $community && ! empty( $community['name'] ) ? $community['name'] : '';
    ?>
    <p style="margin:0;font-size:12px;color:#555;line-height:1.6">
        <?php if ( $name ) : ?>
            投稿先: <?php echo esc_html( $name ); ?>
        <?php else : ?>
            このサイトでは自治会の選択はできません。
        <?php endif; ?>
    </p>
    <?php
}

function akishima_jichikai_community_metabox_save( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( ! is_main_site() && is_multisite() ) {
        akishima_jichikai_force_subsite_community_slug( $post_id );
        return;
    }

    if ( ! isset( $_POST['akishima_jichikai_community_slug_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['akishima_jichikai_community_slug_nonce'], 'akishima_jichikai_community_slug' ) ) {
        return;
    }

    // メインサイトでは手動での紐づけ変更を受け付けない
}
add_action( 'save_post_jichikai', 'akishima_jichikai_community_metabox_save' );

/**
 * 管理画面: TOPお知らせ固定メタボックス
 */
function akishima_home_news_pin_post_types() {
    if ( is_multisite() && is_main_site() ) {
        return array( 'jichiren', 'kairan' );
    }

    if ( is_multisite() && ! is_main_site() ) {
        return array( 'jichikai' );
    }

    return array( 'jichiren', 'kairan', 'jichikai' );
}

function akishima_home_news_pin_metaboxes() {
    foreach ( akishima_home_news_pin_post_types() as $post_type ) {
        add_meta_box(
            'akishima-home-news-pin',
            'TOPお知らせ',
            'akishima_home_news_pin_metabox_render',
            $post_type,
            'side',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'akishima_home_news_pin_metaboxes' );

function akishima_home_news_pin_metabox_render( $post ) {
    wp_nonce_field( 'akishima_home_pin', 'akishima_home_pin_nonce' );

    $pinned = akishima_is_home_news_pinned( $post->ID );
    ?>
    <p style="margin:0 0 8px">
        <label>
            <input type="checkbox" name="akishima_home_pin" value="1" <?php checked( $pinned ); ?>>
            トップのお知らせ先頭に固定
        </label>
    </p>
    <p style="margin:0;font-size:12px;color:#555;line-height:1.6">
        チェックした投稿は、トップページのお知らせで投稿日に関わらず先頭に表示されます。複数ある場合は投稿日の新しい順です。
    </p>
    <?php
}

function akishima_home_news_pin_save( $post_id, $post ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! $post instanceof WP_Post || ! in_array( $post->post_type, akishima_home_news_pin_post_types(), true ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( ! isset( $_POST['akishima_home_pin_nonce'] ) || ! wp_verify_nonce( $_POST['akishima_home_pin_nonce'], 'akishima_home_pin' ) ) {
        return;
    }

    if ( ! empty( $_POST['akishima_home_pin'] ) ) {
        update_post_meta( $post_id, akishima_home_news_pin_meta_key(), '1' );
        return;
    }

    delete_post_meta( $post_id, akishima_home_news_pin_meta_key() );
}
add_action( 'save_post', 'akishima_home_news_pin_save', 10, 2 );
