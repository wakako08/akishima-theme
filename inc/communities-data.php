<?php
/**
 * 自治会マスターデータ（90自治会）
 *
 * 各自治会の子サイト URL は /wp3/{ブロック2桁}-{番号2桁}/ 形式（例: /wp3/01-01/）。
 * 子サイト未作成の自治会もリンク先は設定済み（作成後にそのまま有効）。
 */

function akishima_guide_base_url() {
    return 'https://akishimajichiren.sakura.ne.jp/wp3';
}

function akishima_community_guide_url( $slug ) {
    $slug = sanitize_title( $slug );
    if ( '' === $slug ) {
        return '#';
    }
    return trailingslashit( akishima_guide_base_url() ) . $slug . '/';
}

function akishima_community_slug( $block_no, $member_no ) {
    return sprintf( '%02d-%02d', (int) $block_no, (int) $member_no );
}

function akishima_block_slug( $block_no ) {
    return sprintf( 'block-%02d', (int) $block_no );
}

function akishima_block_guide_url( $block_no ) {
    return trailingslashit( akishima_guide_base_url() ) . akishima_block_slug( $block_no ) . '/';
}

function akishima_format_block_label( $block_no ) {
    $kanji = array(
        1 => '一', 2 => '二', 3 => '三', 4 => '四', 5 => '五',
        6 => '六', 7 => '七', 8 => '八', 9 => '九', 10 => '十',
        11 => '十一', 12 => '十二', 13 => '十三', 14 => '十四', 15 => '十五',
        16 => '十六', 17 => '十七', 18 => '十八', 19 => '十九', 20 => '二十', 21 => '二十一',
    );
    $no = (int) $block_no;
    $label = isset( $kanji[ $no ] ) ? $kanji[ $no ] : (string) $no;
    return '第' . $label . 'ブロック';
}

function akishima_get_blocks_data_raw() {
    return array(
        array(
            'no'      => 1,
            'name'    => '第1ブロック',
            'members' => array(
                array( 'no' => 1, 'name' => '郷地第一自治会', 'url' => '#' ),
                array( 'no' => 2, 'name' => '郷地第二自治会', 'url' => '#' ),
                array( 'no' => 3, 'name' => '郷地第三自治会', 'url' => '#' ),
                array( 'no' => 4, 'name' => '五月自治会', 'url' => '#' ),
                array( 'no' => 5, 'name' => '東町第五自治会', 'url' => '#' ),
                array( 'no' => 6, 'name' => '東町東町会', 'url' => '#' ),
                array( 'no' => 7, 'name' => '東町親睦会', 'url' => '#' ),
                array( 'no' => 8, 'name' => '東町中央自治会', 'url' => '#' ),
                array( 'no' => 9, 'name' => '昭島団地自治会', 'url' => '#' ),
                array( 'no' => 11, 'name' => '郷地玉川自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 2,
            'name'    => '第2ブロック',
            'members' => array(
                array( 'no' => 12, 'name' => '福島第一自治会', 'url' => '#' ),
                array( 'no' => 13, 'name' => '福島第二自治会', 'url' => '#' ),
                array( 'no' => 14, 'name' => '福島第三自治会', 'url' => '#' ),
                array( 'no' => 15, 'name' => '福島第四自治会', 'url' => '#' ),
                array( 'no' => 16, 'name' => '福島第五自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 3,
            'name'    => '第3ブロック',
            'members' => array(
                array( 'no' => 18, 'name' => '八清親和会', 'url' => '#' ),
                array( 'no' => 19, 'name' => '築地地区自治会', 'url' => '#' ),
                array( 'no' => 20, 'name' => '都営玉川町自治会', 'url' => '#' ),
                array( 'no' => 22, 'name' => 'サーパス中神自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 4,
            'name'    => '第4ブロック',
            'members' => array(
                array( 'no' => 23, 'name' => '東中親睦会', 'url' => '#' ),
                array( 'no' => 24, 'name' => '富士見ヶ丘団地自治会', 'url' => '#' ),
                array( 'no' => 26, 'name' => '都営中神第二団地自治会', 'url' => '#' ),
                array( 'no' => 27, 'name' => '富士見町自治会', 'url' => '#' ),
                array( 'no' => 28, 'name' => '新栄会', 'url' => '#' ),
                array( 'no' => 29, 'name' => '昭島東部自治会', 'url' => '#' ),
                array( 'no' => 30, 'name' => '昭和伸栄自治会', 'url' => '#' ),
                array( 'no' => 31, 'name' => '昭島第二住宅自治会', 'url' => '#' ),
                array( 'no' => 32, 'name' => '昭島住宅自治会', 'url' => '#' ),
                array( 'no' => 104, 'name' => 'オハナ昭島中神自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 5,
            'name'    => '第5ブロック',
            'members' => array(
                array( 'no' => 33, 'name' => '中神始自治会', 'url' => '#' ),
                array( 'no' => 34, 'name' => '中神親和自治会', 'url' => '#' ),
                array( 'no' => 35, 'name' => '中神東上自治会', 'url' => '#' ),
                array( 'no' => 36, 'name' => '交友自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 6,
            'name'    => '第6ブロック',
            'members' => array(
                array( 'no' => 39, 'name' => '六親自治会', 'url' => '#' ),
                array( 'no' => 40, 'name' => '朝日町住宅自治会', 'url' => '#' ),
                array( 'no' => 42, 'name' => '中神駅前親交自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 7,
            'name'    => '第7ブロック',
            'members' => array(
                array( 'no' => 43, 'name' => '宮沢町自治会', 'url' => '#' ),
                array( 'no' => 44, 'name' => '大神町自治会', 'url' => '#' ),
                array( 'no' => 45, 'name' => '東ノ岡自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 8,
            'name'    => '第8ブロック',
            'members' => array(
                array( 'no' => 46, 'name' => '昭島中央自治会', 'url' => '#' ),
                array( 'no' => 47, 'name' => '上の原自治会', 'url' => '#' ),
                array( 'no' => 48, 'name' => '光華小前自治会', 'url' => '#' ),
                array( 'no' => 50, 'name' => '緑親交自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 9,
            'name'    => '第9ブロック',
            'members' => array(
                array( 'no' => 51, 'name' => '上川原自治会', 'url' => '#' ),
                array( 'no' => 52, 'name' => '昭島駅前上友自治会', 'url' => '#' ),
                array( 'no' => 53, 'name' => '仲よし自治会', 'url' => '#' ),
                array( 'no' => 54, 'name' => '昭島町一丁目自治会', 'url' => '#' ),
                array( 'no' => 79, 'name' => '上川原二丁目アパート自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 10,
            'name'    => '第10ブロック',
            'members' => array(
                array( 'no' => 56, 'name' => '坂下自治会', 'url' => '#' ),
                array( 'no' => 57, 'name' => '拝島町東自治会', 'url' => '#' ),
                array( 'no' => 58, 'name' => '坂上自治会', 'url' => '#' ),
                array( 'no' => 59, 'name' => '森ノ上町自治会', 'url' => '#' ),
                array( 'no' => 60, 'name' => '中宿自治会', 'url' => '#' ),
                array( 'no' => 61, 'name' => '拝島上町自治会', 'url' => '#' ),
                array( 'no' => 62, 'name' => '栗の沢自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 11,
            'name'    => '第11ブロック',
            'members' => array(
                array( 'no' => 63, 'name' => '富士見坂自治会', 'url' => '#' ),
                array( 'no' => 64, 'name' => '松原自治会', 'url' => '#' ),
                array( 'no' => 65, 'name' => '拝島駅前自治会', 'url' => '#' ),
                array( 'no' => 66, 'name' => '緑ヶ丘自治会', 'url' => '#' ),
                array( 'no' => 67, 'name' => '小荷田自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 12,
            'name'    => '第12ブロック',
            'members' => array(
                array( 'no' => 68, 'name' => '二葉自治会', 'url' => '#' ),
                array( 'no' => 69, 'name' => '美野里会', 'url' => '#' ),
                array( 'no' => 70, 'name' => '互助会', 'url' => '#' ),
                array( 'no' => 71, 'name' => '多摩野会', 'url' => '#' ),
                array( 'no' => 72, 'name' => '八八会', 'url' => '#' ),
                array( 'no' => 73, 'name' => '上向自治会', 'url' => '#' ),
                array( 'no' => 74, 'name' => '富士美自治会', 'url' => '#' ),
                array( 'no' => 75, 'name' => '美堀町つくし自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 13,
            'name'    => '第13ブロック',
            'members' => array(
                array( 'no' => 78, 'name' => 'みまつ自治会', 'url' => '#' ),
                array( 'no' => 81, 'name' => 'シティテラス昭島自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 14,
            'name'    => '第14ブロック',
            'members' => array(
                array( 'no' => 82, 'name' => '拝島団地中央連合自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 15,
            'name'    => '第15ブロック',
            'members' => array(
                array( 'no' => 83, 'name' => '中神団地自治会', 'url' => '#' ),
                array( 'no' => 84, 'name' => '昭文自治会', 'url' => '#' ),
                array( 'no' => 85, 'name' => 'むさしの自治会', 'url' => '#' ),
                array( 'no' => 86, 'name' => '日の出自治会', 'url' => '#' ),
                array( 'no' => 87, 'name' => '文化自治会', 'url' => '#' ),
                array( 'no' => 88, 'name' => 'メゾンエクレーレニュー昭島自治会', 'url' => '#' ),
                array( 'no' => 89, 'name' => 'ブルーミングガーデン昭島自治会', 'url' => '#' ),
                array( 'no' => 90, 'name' => 'バーデン昭島自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 16,
            'name'    => '第16ブロック',
            'members' => array(
                array( 'no' => 91, 'name' => '田中町自治会', 'url' => '#' ),
                array( 'no' => 92, 'name' => '昭島田中町住宅自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 17,
            'name'    => '第17ブロック',
            'members' => array(
                array( 'no' => 94, 'name' => 'つつじが丘東自治会', 'url' => '#' ),
                array( 'no' => 95, 'name' => 'つつじが丘西自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 18,
            'name'    => '第18ブロック',
            'members' => array(
                array( 'no' => 96, 'name' => '西武拝島ハイツ自治会', 'url' => '#' ),
                array( 'no' => 97, 'name' => '西武拝島ハイツ樹だち館自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 19,
            'name'    => '第19ブロック',
            'members' => array(
                array( 'no' => 98, 'name' => 'つつじが丘北自治会', 'url' => '#' ),
                array( 'no' => 100, 'name' => 'AYUMOCITY昭島自治会', 'url' => '#' ),
                array( 'no' => 102, 'name' => 'プラウドシーズン西武立川自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 20,
            'name'    => '第20ブロック',
            'members' => array(
                array( 'no' => 99, 'name' => 'プレイシア自治会', 'url' => '#' ),
                array( 'no' => 101, 'name' => 'ポレスター昭島自治会', 'url' => '#' ),
            ),
        ),
        array(
            'no'      => 21,
            'name'    => '第21ブロック',
            'members' => array(
                array( 'no' => 103, 'name' => '昭島法務自治会', 'url' => '#' ),
            ),
        ),
    );
}

function akishima_get_blocks_data() {
    static $cache = null;
    if ( null !== $cache ) {
        return $cache;
    }
    $blocks = akishima_get_blocks_data_raw();
    foreach ( $blocks as &$block ) {
        $block['label'] = akishima_format_block_label( $block['no'] );
        $block['slug']  = akishima_block_slug( $block['no'] );
        $block['guide_url'] = akishima_block_guide_url( $block['no'] );
        foreach ( $block['members'] as &$member ) {
            $member['block_no']    = (int) $block['no'];
            $member['block_name']  = $block['name'];
            $member['block_label'] = $block['label'];
            $member['slug']        = akishima_community_slug( $block['no'], $member['no'] );
            $member['guide_url']   = akishima_community_guide_url( $member['slug'] );
            if ( ! isset( $member['external_url'] ) || '' === $member['external_url'] || '#' === $member['external_url'] ) {
                $member['external_url'] = $member['guide_url'];
            }
        }
        unset( $member );
    }
    unset( $block );
    $cache = $blocks;
    return $cache;
}

function akishima_get_block_by_no( $block_no ) {
    $block_no = (int) $block_no;
    foreach ( akishima_get_blocks_data() as $block ) {
        if ( (int) $block['no'] === $block_no ) {
            return $block;
        }
    }
    return null;
}

/**
 * ブロックデータを自治会ページと同じ配列形に正規化
 * （community.php のテンプレート部品をそのまま使うため）
 *
 * @param array $block akishima_get_blocks_data() の1要素
 * @return array
 */
function akishima_normalize_block_as_community( $block ) {
    if ( ! is_array( $block ) || empty( $block['no'] ) ) {
        return array();
    }

    $name  = ! empty( $block['name'] ) ? $block['name'] : ( '第' . (int) $block['no'] . 'ブロック' );
    $label = ! empty( $block['label'] ) ? $block['label'] : akishima_format_block_label( $block['no'] );
    $slug  = ! empty( $block['slug'] ) ? $block['slug'] : akishima_block_slug( $block['no'] );
    $url   = ! empty( $block['guide_url'] ) ? $block['guide_url'] : akishima_block_guide_url( $block['no'] );

    return array(
        'no'            => (int) $block['no'],
        'name'          => $name,
        'slug'          => $slug,
        'block_no'      => (int) $block['no'],
        'block_name'    => $name,
        'block_label'   => $label,
        'guide_url'     => $url,
        'external_url'  => $url,
        'members'       => ! empty( $block['members'] ) && is_array( $block['members'] ) ? $block['members'] : array(),
        'is_block'      => true,
    );
}

function akishima_get_community_by_member_no( $member_no ) {
    $member_no = (int) $member_no;
    foreach ( akishima_get_blocks_data() as $block ) {
        foreach ( $block['members'] as $member ) {
            if ( (int) $member['no'] === $member_no ) {
                return $member;
            }
        }
    }
    return null;
}

function akishima_get_community_by_slug( $slug ) {
    $slug = sanitize_title( $slug );

    if ( preg_match( '/^block-(\d{2})$/', $slug, $m ) ) {
        $block = akishima_get_block_by_no( (int) $m[1] );
        return $block ? akishima_normalize_block_as_community( $block ) : null;
    }

    foreach ( akishima_get_blocks_data() as $block ) {
        foreach ( $block['members'] as $member ) {
            if ( $member['slug'] === $slug ) {
                return $member;
            }
        }
    }
    return null;
}

function akishima_community_url( $member ) {
    $external = isset( $member['external_url'] ) ? $member['external_url'] : '';
    if ( ! empty( $external ) && '#' !== $external ) {
        return $external;
    }
    if ( empty( $member['slug'] ) ) {
        return '#';
    }
    return akishima_community_guide_url( $member['slug'] );
}

function akishima_community_is_external( $member ) {
    $url = akishima_community_url( $member );
    if ( '#' === $url || '' === $url ) {
        return false;
    }
    $bases = array(
        untrailingslashit( home_url() ),
        untrailingslashit( akishima_guide_base_url() ),
    );
    foreach ( $bases as $base ) {
        if ( $base && 0 === strpos( $url, $base ) ) {
            return false;
        }
    }
    return true;
}

function akishima_get_all_communities() {
    $all = array();
    foreach ( akishima_get_blocks_data() as $block ) {
        foreach ( $block['members'] as $member ) {
            $all[] = $member;
        }
    }
    return $all;
}

/**
 * GeoJSON 内の url を自治会一覧と同じ slug / URL に差し替える。
 * 地図の properties.name は自治会番号（一覧の no）に対応する。
 */
function akishima_prepare_map_geojson( $json_string ) {
    $data = json_decode( $json_string, true );
    if ( ! is_array( $data ) || empty( $data['features'] ) ) {
        return $json_string;
    }

    foreach ( $data['features'] as &$feature ) {
        $props = isset( $feature['properties'] ) ? $feature['properties'] : array();
        if ( empty( $props['name'] ) ) {
            continue;
        }
        $member = akishima_get_community_by_member_no( $props['name'] );
        if ( $member ) {
            $feature['properties']['url'] = akishima_community_url( $member );
            continue;
        }
        if ( empty( $props['url'] ) || '#' === $props['url'] ) {
            continue;
        }
        if ( preg_match( '#/(?:guide/)?(\d+)-(\d+)/?#', $props['url'], $matches ) ) {
            $slug = akishima_community_slug( $matches[1], $matches[2] );
            $feature['properties']['url'] = akishima_community_guide_url( $slug );
        }
    }
    unset( $feature );

    return wp_json_encode( $data );
}
