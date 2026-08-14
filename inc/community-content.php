<?php
/**
 * 自治会個別ページ: コンテンツ・画像の取得
 *
 * テキスト: assets/data/communities/{スラッグ}.json
 * 画像:    子サイト管理画面のメディア（優先）
 *          なければ assets/images/communities/{スラッグ}/gallery-1.jpg など
 *          どちらも無い場合は非表示（default 仮画像は使わない）
 */

/**
 * 自治会JSONのパス
 */
function akishima_community_json_path( $slug ) {
    $slug = sanitize_title( $slug );
    return get_template_directory() . '/assets/data/communities/' . $slug . '.json';
}

/**
 * 自治会画像ディレクトリ（テーマ内相対パス）
 */
function akishima_community_images_dir( $slug ) {
    $slug = sanitize_title( $slug );
    return 'assets/images/communities/' . $slug;
}

/**
 * 自治会ページ用コンテンツを取得
 *
 * @param array $community 自治会マスターデータ
 * @return array{name,intro,activities,fee,organizations}
 */
function akishima_get_community_content( $community ) {
    $name = isset( $community['name'] ) ? $community['name'] : '';
    $slug = isset( $community['slug'] ) ? $community['slug'] : '';

    $defaults = array(
        'name'              => $name,
        'intro'             => '',
        'activities'        => '',
        'fee'               => '',
        'organizations'     => '',
        'facility_name'     => '',
        'facility_address'  => '',
        'facility_rental'   => '',
    );

    // テーマ JSON をベースに、子サイト管理画面の非空値で上書き
    $json_path = akishima_community_json_path( $slug );
    if ( is_readable( $json_path ) ) {
        $json = json_decode( file_get_contents( $json_path ), true );
        if ( is_array( $json ) ) {
            $defaults = array_merge( $defaults, $json );
        }
    }

    if ( is_multisite() && ! is_main_site() && function_exists( 'akishima_get_community_content_from_options' ) ) {
        foreach ( akishima_get_community_content_from_options() as $field => $value ) {
            if ( '' !== trim( (string) $value ) ) {
                $defaults[ $field ] = $value;
            }
        }
    }

    if ( empty( $defaults['name'] ) ) {
        $defaults['name'] = $name;
    }

    /**
     * 自治会コンテンツのフィルター（本番・子サイト連携用）
     */
    return apply_filters( 'akishima_community_content', $defaults, $community );
}

/**
 * メディアライブラリの画像IDから表示用配列を生成
 *
 * @param string $option_key
 * @return array<int, array{url:string, alt:string}>
 */
function akishima_get_community_images_from_options( $option_key ) {
    $ids = get_option( $option_key, array() );
    if ( ! is_array( $ids ) ) {
        return array();
    }

    $images = array();
    foreach ( $ids as $id ) {
        $id = (int) $id;
        if ( ! $id ) {
            continue;
        }
        $url = wp_get_attachment_image_url( $id, 'large' );
        if ( ! $url ) {
            continue;
        }
        $alt = (string) get_post_meta( $id, '_wp_attachment_image_alt', true );
        $images[] = array(
            'url' => $url,
            'alt' => $alt,
        );
    }

    return $images;
}

/**
 * 画像URL一覧を取得（{prefix}-1 ～、jpg/png/webp）
 *
 * @param string $slug   自治会スラッグ
 * @param string $prefix ファイル名プレフィックス（gallery / facility）
 * @param int    $max    最大枚数
 * @return array<int, array{url:string, alt:string}>
 */
function akishima_get_community_images_by_prefix( $slug, $prefix = 'gallery', $max = 10 ) {
    $slug       = sanitize_title( $slug );
    $prefix     = sanitize_title( $prefix );
    $rel_dir    = akishima_community_images_dir( $slug );
    $abs_dir    = get_template_directory() . '/' . $rel_dir;
    $extensions = array( 'jpg', 'jpeg', 'png', 'webp' );
    $images     = array();

    if ( ! is_dir( $abs_dir ) ) {
        return $images;
    }

    for ( $i = 1; $i <= $max; $i++ ) {
        foreach ( $extensions as $ext ) {
            $filename = $prefix . '-' . $i . '.' . $ext;
            $filepath = $abs_dir . '/' . $filename;
            if ( is_readable( $filepath ) ) {
                $images[] = array(
                    'url' => get_template_directory_uri() . '/' . $rel_dir . '/' . $filename,
                    'alt' => '',
                );
                break;
            }
        }
    }

    return $images;
}

/**
 * 活動ギャラリー画像
 */
function akishima_get_community_gallery_images( $slug ) {
    if ( is_multisite() && ! is_main_site() ) {
        $images = akishima_get_community_images_from_options( 'akishima_community_gallery_ids' );
        if ( ! empty( $images ) ) {
            return apply_filters( 'akishima_community_gallery_images', $images, $slug );
        }
    }

    $images = akishima_get_community_images_by_prefix( $slug, 'gallery', 10 );
    return apply_filters( 'akishima_community_gallery_images', $images, $slug );
}

/**
 * 自治会館・集会施設ギャラリー画像（facility-1 ～）
 */
function akishima_get_community_facility_images( $slug ) {
    if ( is_multisite() && ! is_main_site() ) {
        $images = akishima_get_community_images_from_options( 'akishima_community_facility_gallery_ids' );
        if ( ! empty( $images ) ) {
            return apply_filters( 'akishima_community_facility_images', $images, $slug );
        }
    }

    $images = akishima_get_community_images_by_prefix( $slug, 'facility', 10 );
    return apply_filters( 'akishima_community_facility_images', $images, $slug );
}
