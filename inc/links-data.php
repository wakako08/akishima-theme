<?php
/**
 * 便利なリンク集データ（旧サイト https://akishima-jichiren.jp/links/ より）
 */

/**
 * @return array<int, array{title: string, groups: array<int, array{title: string, items: array<int, array{label: string, url: string}>}>}>
 */
function akishima_get_useful_links_sections() {
    static $sections = null;

    if ( null !== $sections ) {
        return $sections;
    }

    $path = get_template_directory() . '/assets/data/useful-links.json';
    if ( ! file_exists( $path ) ) {
        $sections = array();
        return $sections;
    }

    $json = file_get_contents( $path );
    $data = json_decode( $json, true );

    $sections = is_array( $data ) ? $data : array();
    return $sections;
}

/**
 * 便利なリンク集ページURL
 */
function akishima_useful_links_url() {
    $page = get_page_by_path( 'links' );
    return $page ? get_permalink( $page->ID ) : home_url( '/links/' );
}

/**
 * ラベル末尾の電話番号を名前と番号に分割
 *
 * @param string $label 表示ラベル
 * @return array{name: string, phones: string[]}|null
 */
function akishima_parse_phone_label( $label ) {
    $label = trim( wp_strip_all_tags( $label ) );
    if ( '' === $label ) {
        return null;
    }

    $label = mb_convert_kana( $label, 'n', 'UTF-8' );
    $label = str_replace( array( '＃', '　' ), array( '#', ' ' ), $label );

    if ( preg_match( '/又は/u', $label ) ) {
        $parts = preg_split( '/\s*又は\s*/u', $label );
        $name  = '';
        $phones = array();
        foreach ( $parts as $part ) {
            $parsed = akishima_extract_phone_from_label_part( trim( $part ) );
            if ( ! $parsed ) {
                continue;
            }
            if ( '' === $name && '' !== $parsed['name'] ) {
                $name = $parsed['name'];
            }
            $phones[] = $parsed['phone'];
        }
        if ( empty( $phones ) ) {
            return null;
        }
        return array(
            'name'   => $name ? rtrim( $name ) : $label,
            'phones' => array_values( array_unique( $phones ) ),
        );
    }

    $parsed = akishima_extract_phone_from_label_part( $label );
    if ( ! $parsed ) {
        return null;
    }

    return array(
        'name'   => $parsed['name'],
        'phones' => array( $parsed['phone'] ),
    );
}

/**
 * @param string $part ラベルまたはその一部
 * @return array{name: string, phone: string}|null
 */
function akishima_extract_phone_from_label_part( $part ) {
    $part = preg_replace( '/\([^)]*\)\s*$/u', '', $part );
    $part = trim( $part );

    $patterns = array(
        '/^(?<name>.*?)(?<phone>0\d{1,4}-\d{1,4}-\d{3,4})$/u',
        '/^(?<name>.*?)(?<phone>0120-\d{3}-\d{3})$/u',
        '/^(?<name>.*?)(?<phone>050-\d{4}-\d{4})$/u',
        '/^(?<name>.*?)(?<phone>#\d{3,4})$/u',
        '/^(?<name>.*?)(?<phone>\d{3}-\d{4})$/u',
    );

    foreach ( $patterns as $pattern ) {
        if ( preg_match( $pattern, $part, $matches ) ) {
            $name  = rtrim( $matches['name'] );
            $phone = $matches['phone'];
            if ( preg_match( '/^\d{3}-\d{4}$/', $phone ) ) {
                $phone = '042-' . $phone;
            }
            if ( '#' === $phone[0] ) {
                $phone = '0' . substr( $phone, 1 );
            }
            return array(
                'name'  => $name,
                'phone' => $phone,
            );
        }
    }

    return null;
}

/**
 * tel: リンク用に電話番号を正規化
 *
 * @param string $phone 電話番号
 * @return string
 */
function akishima_normalize_tel_href( $phone ) {
    $digits = preg_replace( '/\D+/u', '', $phone );
    if ( '' === $digits ) {
        return '';
    }
    if ( '0' !== $digits[0] && 10 <= strlen( $digits ) ) {
        $digits = '0' . $digits;
    }
    return 'tel:' . $digits;
}
