<?php
/**
 * 自治会個別ページテンプレート
 * URL: /{ブロック番号}-{自治会番号}/ （例: /01-01/）
 * 本文: template-parts/pages/community.php
 */

$community = get_query_var( 'akishima_community_data' );
if ( ! is_array( $community ) ) {
    $community = isset( $GLOBALS['akishima_current_community'] ) ? $GLOBALS['akishima_current_community'] : null;
}

if ( ! $community ) {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    get_template_part( '404' );
    return;
}

get_header();
?>

<main id="main" class="site-main community-page">

    <?php
    set_query_var( 'akishima_community_data', $community );
    get_template_part( 'template-parts/pages/community' );
    ?>

</main>

<?php get_footer(); ?>
