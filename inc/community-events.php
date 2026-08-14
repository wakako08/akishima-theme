<?php
/**
 * 自治会個別ページ: 行事予定（EVENT）
 *
 * 各自治会子サイトの管理画面から投稿。
 * フィールド:
 *   タイトル / 本文     … WordPress 標準欄
 *   日付 / 時間 / 場所 … カスタムフィールド（_event_date / _event_time / _event_location）
 *   自治会             … 子サイトでは自動設定（_community_slug）
 */

/**
 * 行事予定 CPT 登録
 */
function akishima_register_community_event_post_type() {
    $show_ui = ! is_multisite() || ! is_main_site();

    register_post_type( 'community_event', array(
        'labels' => array(
            'name'               => '行事予定',
            'singular_name'      => '行事',
            'add_new'            => '行事を追加',
            'add_new_item'       => '行事を追加',
            'edit_item'          => '行事を編集',
            'new_item'           => '新規行事',
            'view_item'          => '行事を表示',
            'all_items'          => '行事一覧',
            'search_items'       => '行事を検索',
            'not_found'          => '行事が見つかりません',
        ),
        'public'             => true,
        'show_ui'            => $show_ui,
        'show_in_rest'       => false,
        'has_archive'        => true,
        'supports'           => array( 'title', 'editor' ),
        'rewrite'            => array( 'slug' => 'events', 'with_front' => false ),
        'menu_icon'          => 'dashicons-calendar-alt',
    ) );
}
add_action( 'init', 'akishima_register_community_event_post_type' );

/**
 * 行事予定はクラシック編集画面を使用（日付・時間・場所の入力欄を見やすくする）
 */
function akishima_community_event_use_classic_editor( $use_block_editor, $post_type ) {
    if ( 'community_event' === $post_type ) {
        return false;
    }

    return $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'akishima_community_event_use_classic_editor', 10, 2 );

/**
 * 行事クエリ引数
 *
 * @param array $community 自治会データ
 * @param int   $per_page  取得件数
 */
function akishima_community_events_query_args( $community, $per_page = 3 ) {
    $slug = isset( $community['slug'] ) ? $community['slug'] : '';

    $args = array(
        'post_type'           => 'community_event',
        'posts_per_page'      => $per_page,
        'post_status'         => 'publish',
        'ignore_sticky_posts' => true,
        'meta_key'            => '_event_date',
        'orderby'             => 'meta_value',
        'order'               => 'ASC',
        'meta_query'          => array(
            array(
                'key'     => '_event_date',
                'compare' => 'EXISTS',
            ),
        ),
    );

    // メインサイトのプレビュー時のみ自治会スラッグで絞り込み
    if ( is_multisite() && ! is_main_site() ) {
        return apply_filters( 'akishima_community_events_query_args', $args, $community );
    }

    if ( $slug ) {
        $args['meta_query'][] = array(
            'key'     => '_community_slug',
            'value'   => $slug,
            'compare' => '=',
        );
    }

    return apply_filters( 'akishima_community_events_query_args', $args, $community );
}

/**
 * 表示用に正規化した行事配列
 *
 * @return array<int, array{date:string,date_display:string,title:string,time:string,location:string,content:string,url:string}>
 */
function akishima_get_community_events_list( $community, $per_page = 3 ) {
    $query = new WP_Query( akishima_community_events_query_args( $community, $per_page ) );
    $items = array();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $items[] = akishima_normalize_community_event_from_post( get_the_ID() );
        }
        wp_reset_postdata();
    }

    if ( empty( $items ) ) {
        // 子サイトでは未投稿時に JSON ダミーを表示しない（管理画面の投稿のみ表示）
        if ( ! ( is_multisite() && ! is_main_site() ) ) {
            $items = akishima_community_events_from_json( $community );
            if ( $per_page > 0 && count( $items ) > $per_page ) {
                $items = array_slice( $items, 0, $per_page );
            }
        }
    }

    return apply_filters( 'akishima_community_events_list', $items, $community );
}

/**
 * 投稿から行事データを組み立て
 */
function akishima_normalize_community_event_from_post( $post_id ) {
    $date_raw = get_post_meta( $post_id, '_event_date', true );
    if ( ! $date_raw ) {
        $date_raw = get_the_date( 'Y-m-d', $post_id );
    }

    $timestamp = strtotime( $date_raw );
    $date_display = $timestamp ? date_i18n( 'Y年n月j日', $timestamp ) : '';

    $content = wp_strip_all_tags( get_post_field( 'post_content', $post_id ) );

    return array(
        'date'          => $date_raw,
        'date_display'  => $date_display,
        'title'         => get_the_title( $post_id ),
        'time'          => (string) get_post_meta( $post_id, '_event_time', true ),
        'location'      => (string) get_post_meta( $post_id, '_event_location', true ),
        'content'       => $content,
        'url'           => get_permalink( $post_id ),
    );
}

/**
 * JSON フォールバック（ローカルプレビュー・未投稿時）
 */
function akishima_community_events_from_json( $community ) {
    $slug = isset( $community['slug'] ) ? $community['slug'] : '';
    $path = akishima_community_json_path( $slug );
    if ( ! is_readable( $path ) ) {
        return array();
    }

    $json = json_decode( file_get_contents( $path ), true );
    if ( ! is_array( $json ) || empty( $json['events'] ) || ! is_array( $json['events'] ) ) {
        return array();
    }

    $items = array();
    foreach ( $json['events'] as $row ) {
        if ( ! is_array( $row ) ) {
            continue;
        }
        $date_raw = isset( $row['date'] ) ? $row['date'] : '';
        $timestamp = $date_raw ? strtotime( $date_raw ) : false;
        $items[] = array(
            'date'          => $date_raw,
            'date_display'  => $timestamp ? date_i18n( 'Y年n月j日', $timestamp ) : ( isset( $row['date_display'] ) ? $row['date_display'] : '' ),
            'title'         => isset( $row['title'] ) ? $row['title'] : '',
            'time'          => isset( $row['time'] ) ? $row['time'] : '',
            'location'      => isset( $row['location'] ) ? $row['location'] : '',
            'content'       => isset( $row['content'] ) ? $row['content'] : ( isset( $row['excerpt'] ) ? $row['excerpt'] : '' ),
            'url'           => isset( $row['url'] ) ? $row['url'] : '',
        );
    }

    return $items;
}

/**
 * VIEW ALL URL
 */
function akishima_community_events_archive_url( $community ) {
    if ( is_multisite() && ! is_main_site() ) {
        $archive = get_post_type_archive_link( 'community_event' );
        return $archive ? $archive : home_url( '/events/' );
    }

    $external = '';
    if ( ! empty( $community['external_url'] ) && '#' !== $community['external_url'] ) {
        $external = $community['external_url'];
    }

    if ( $external ) {
        return apply_filters(
            'akishima_community_events_archive_url_external',
            trailingslashit( $external ) . 'events/',
            $community
        );
    }

    $slug = isset( $community['slug'] ) ? $community['slug'] : '';
    $base = get_post_type_archive_link( 'community_event' );
    if ( ! $base ) {
        $base = home_url( '/events/' );
    }

    return add_query_arg( 'community', $slug, $base );
}

/**
 * アーカイブで community クエリを絞り込み
 */
function akishima_filter_community_event_archive_by_community( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( ! $query->is_post_type_archive( 'community_event' ) ) {
        return;
    }

    $query->set( 'meta_key', '_event_date' );
    $query->set( 'orderby', 'meta_value' );
    $query->set( 'order', 'ASC' );

    if ( is_multisite() && ! is_main_site() ) {
        return;
    }

    $community_slug = isset( $_GET['community'] ) ? sanitize_title( wp_unslash( $_GET['community'] ) ) : '';
    if ( empty( $community_slug ) ) {
        return;
    }

    $meta_query = (array) $query->get( 'meta_query' );
    $meta_query[] = array(
        'key'     => '_community_slug',
        'value'   => $community_slug,
        'compare' => '=',
    );
    $query->set( 'meta_query', $meta_query );
}
add_action( 'pre_get_posts', 'akishima_filter_community_event_archive_by_community' );

/**
 * 子サイト: 行事の個別ページ・アーカイブへのアクセスをトップへリダイレクト
 */
function akishima_community_event_redirect_subsite_singles() {
    if ( ! is_multisite() || is_main_site() ) {
        return;
    }

    if ( is_singular( 'community_event' ) || is_post_type_archive( 'community_event' ) ) {
        wp_safe_redirect( home_url( '/' ) );
        exit;
    }
}
add_action( 'template_redirect', 'akishima_community_event_redirect_subsite_singles' );

/**
 * メインサイトでは行事予定の投稿・編集を不可（各子サイトからのみ）
 */
function akishima_community_event_block_main_site_admin_access() {
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

    if ( 'community_event' !== $post_type ) {
        return;
    }

    wp_die(
        '<p>行事予定は、各自治会子サイトの管理画面から投稿・編集してください。</p>' .
        '<p><a href="' . esc_url( admin_url( 'index.php' ) ) . '">ダッシュボードへ戻る</a></p>',
        '行事予定',
        array( 'response' => 403 )
    );
}
add_action( 'load-post-new.php', 'akishima_community_event_block_main_site_admin_access' );
add_action( 'load-post.php', 'akishima_community_event_block_main_site_admin_access' );
add_action( 'load-edit.php', 'akishima_community_event_block_main_site_admin_access' );

/**
 * 子サイト投稿時に自治会スラッグを常に自動設定
 */
function akishima_community_event_force_subsite_community_slug( $post_id ) {
    if ( is_main_site() || ! is_multisite() || 'community_event' !== get_post_type( $post_id ) ) {
        return;
    }

    $slug = function_exists( 'akishima_get_subsite_community_slug' ) ? akishima_get_subsite_community_slug() : '';
    if ( $slug ) {
        update_post_meta( $post_id, '_community_slug', $slug );
    }
}
add_action( 'save_post_community_event', 'akishima_community_event_force_subsite_community_slug', 20 );

/**
 * 管理画面: タイトル直下にカスタムフィールド（日付・時間・場所）
 */
function akishima_community_event_custom_fields_after_title( $post ) {
    if ( 'community_event' !== $post->post_type ) {
        return;
    }

    wp_nonce_field( 'akishima_community_event_details', 'akishima_community_event_details_nonce' );

    $event_date     = get_post_meta( $post->ID, '_event_date', true );
    $event_time     = get_post_meta( $post->ID, '_event_time', true );
    $event_location = get_post_meta( $post->ID, '_event_location', true );
    ?>
    <div class="akishima-event-fields" style="margin:16px 0;padding:16px;background:#f6f7f7;border:1px solid #c3c4c7;border-radius:4px">
        <p style="margin:0 0 12px;font-size:13px;color:#555">
            <strong>タイトル</strong>は上の欄、<strong>本文</strong>は下のエディターで入力してください。
        </p>
        <table class="form-table" role="presentation" style="margin:0">
            <tbody>
                <tr>
                    <th scope="row"><label for="event_date">日付 <span style="color:#d63638">*</span></label></th>
                    <td>
                        <input type="date" name="event_date" id="event_date" value="<?php echo esc_attr( $event_date ); ?>" required>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="event_time">時間</label></th>
                    <td>
                        <input type="text" name="event_time" id="event_time" value="<?php echo esc_attr( $event_time ); ?>" class="regular-text" placeholder="例: 10:00〜12:00">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="event_location">場所</label></th>
                    <td>
                        <input type="text" name="event_location" id="event_location" value="<?php echo esc_attr( $event_location ); ?>" class="large-text" placeholder="例: つつじが丘公園">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}
add_action( 'edit_form_after_title', 'akishima_community_event_custom_fields_after_title' );

/**
 * 管理画面メタボックス（投稿先自治会）
 */
function akishima_community_event_metaboxes() {
    if ( is_multisite() && is_main_site() ) {
        return;
    }

    add_meta_box(
        'community-event-community',
        '投稿先自治会',
        'akishima_community_event_community_metabox_render',
        'community_event',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'akishima_community_event_metaboxes' );

function akishima_community_event_community_metabox_render( $post ) {
    wp_nonce_field( 'akishima_community_event_community', 'akishima_community_event_community_nonce' );

    if ( ! is_main_site() && is_multisite() ) {
        $slug      = function_exists( 'akishima_get_subsite_community_slug' ) ? akishima_get_subsite_community_slug() : '';
        $community = $slug ? akishima_get_community_by_slug( $slug ) : null;
        $name      = $community && ! empty( $community['name'] ) ? $community['name'] : ( $slug ? $slug : '—' );
        ?>
        <p style="margin:0;font-size:13px;font-weight:600;color:#002239">
            <?php echo esc_html( $name ); ?>
        </p>
        <p style="margin:8px 0 0;font-size:12px;color:#555;line-height:1.6">
            この子サイトの行事予定として公開されます。自治会ページの EVENT セクションに表示されます。
        </p>
        <?php
        return;
    }

    $value       = get_post_meta( $post->ID, '_community_slug', true );
    $communities = akishima_get_all_communities();
    ?>
    <p style="margin:0 0 8px;font-size:12px;color:#555">
        自治会個別ページの「行事予定」に表示します。
    </p>
    <select name="community_slug" id="community_slug" style="width:100%">
        <option value="">— 未設定 —</option>
        <?php foreach ( $communities as $member ) : ?>
        <option value="<?php echo esc_attr( $member['slug'] ); ?>" <?php selected( $value, $member['slug'] ); ?>>
            <?php echo esc_html( $member['name'] . ' (' . $member['slug'] . ')' ); ?>
        </option>
        <?php endforeach; ?>
    </select>
    <?php
}

/**
 * 一覧画面の列
 */
function akishima_community_event_admin_columns( $columns ) {
    $new = array();

    foreach ( $columns as $key => $label ) {
        $new[ $key ] = $label;
        if ( 'title' === $key ) {
            $new['event_date']     = '日付';
            $new['event_time']     = '時間';
            $new['event_location'] = '場所';
        }
    }

    return $new;
}
add_filter( 'manage_community_event_posts_columns', 'akishima_community_event_admin_columns' );

function akishima_community_event_admin_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'event_date':
            $date = get_post_meta( $post_id, '_event_date', true );
            if ( $date ) {
                $timestamp = strtotime( $date );
                echo esc_html( $timestamp ? date_i18n( 'Y年n月j日', $timestamp ) : $date );
            } else {
                echo '—';
            }
            break;
        case 'event_time':
            $time = get_post_meta( $post_id, '_event_time', true );
            echo $time ? esc_html( $time ) : '—';
            break;
        case 'event_location':
            $location = get_post_meta( $post_id, '_event_location', true );
            echo $location ? esc_html( $location ) : '—';
            break;
    }
}
add_action( 'manage_community_event_posts_custom_column', 'akishima_community_event_admin_column_content', 10, 2 );

function akishima_community_event_metabox_save( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if (
        isset( $_POST['akishima_community_event_details_nonce'] )
        && wp_verify_nonce( $_POST['akishima_community_event_details_nonce'], 'akishima_community_event_details' )
    ) {
        $date = isset( $_POST['event_date'] ) ? sanitize_text_field( wp_unslash( $_POST['event_date'] ) ) : '';
        if ( $date ) {
            update_post_meta( $post_id, '_event_date', $date );
        } else {
            delete_post_meta( $post_id, '_event_date' );
        }

        $time = isset( $_POST['event_time'] ) ? sanitize_text_field( wp_unslash( $_POST['event_time'] ) ) : '';
        if ( $time ) {
            update_post_meta( $post_id, '_event_time', $time );
        } else {
            delete_post_meta( $post_id, '_event_time' );
        }

        $location = isset( $_POST['event_location'] ) ? sanitize_text_field( wp_unslash( $_POST['event_location'] ) ) : '';
        if ( $location ) {
            update_post_meta( $post_id, '_event_location', $location );
        } else {
            delete_post_meta( $post_id, '_event_location' );
        }
    }

    if ( ! is_main_site() && is_multisite() ) {
        akishima_community_event_force_subsite_community_slug( $post_id );
        return;
    }

    if (
        isset( $_POST['akishima_community_event_community_nonce'] )
        && wp_verify_nonce( $_POST['akishima_community_event_community_nonce'], 'akishima_community_event_community' )
    ) {
        $slug = isset( $_POST['community_slug'] ) ? sanitize_title( wp_unslash( $_POST['community_slug'] ) ) : '';
        if ( $slug ) {
            update_post_meta( $post_id, '_community_slug', $slug );
        } else {
            delete_post_meta( $post_id, '_community_slug' );
        }
    }
}
add_action( 'save_post_community_event', 'akishima_community_event_metabox_save' );
