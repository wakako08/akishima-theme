<?php
/**
 * 子サイトお知らせ一覧（/wp3/01-01/news/）
 */
$slug       = function_exists( 'akishima_get_subsite_community_slug' ) ? akishima_get_subsite_community_slug() : '';
$paged      = max( 1, (int) get_query_var( 'paged' ) );
$collection = akishima_get_network_jichikai_posts(
    array(
        'posts_per_page' => 9,
        'paged'          => $paged,
        'community_slug' => $slug,
    )
);

$label     = akishima_community_news_archive_label( $slug );
$back_url  = $slug ? akishima_community_news_back_url( $slug ) : home_url( '/' );
$back_text = 'BACK';

get_header();
?>
<main id="main" class="site-main news-archive-main">
    <?php
    get_template_part(
        'template-parts/news/jichikai-list',
        null,
        array(
            'label'      => $label,
            'collection' => $collection,
            'paged'      => $paged,
            'back_url'   => $back_url,
            'back_text'  => $back_text,
        )
    );
    ?>
</main>
<?php
get_footer();
