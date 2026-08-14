<?php
/**
 * ブロックページテンプレート
 * URL: /block-01/ （メインサイトプレビュー） or 子サイト /wp3/block-01/
 *
 * 自治会個別ページ（community.php）と同一のデザイン・仕様。
 * 本文: template-parts/pages/community.php
 */

$block = get_query_var( 'akishima_block_data' );
if ( ! is_array( $block ) ) {
    $block = isset( $GLOBALS['akishima_current_block'] ) ? $GLOBALS['akishima_current_block'] : null;
}

$community = null;
if ( is_array( $block ) && function_exists( 'akishima_normalize_block_as_community' ) ) {
    $community = akishima_normalize_block_as_community( $block );
}

if ( ! is_array( $community ) || empty( $community ) ) {
    $slug = get_query_var( 'akishima_block' );
    if ( $slug ) {
        $community = akishima_get_community_by_slug( 'block-' . sprintf( '%02d', (int) $slug ) );
    }
}

if ( ! is_array( $community ) || empty( $community ) ) {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    get_template_part( '404' );
    return;
}

$GLOBALS['akishima_current_community'] = $community;
set_query_var( 'akishima_community_data', $community );

get_header();
?>

<main id="main" class="site-main community-page block-page">

    <?php get_template_part( 'template-parts/pages/community' ); ?>

</main>

<?php get_footer(); ?>
