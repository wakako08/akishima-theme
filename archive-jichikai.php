<?php
/**
 * 自治会からのお知らせ 一覧ページ
 * メインサイト: 全子サイトの投稿を集約表示
 */
get_header();
?>
<main id="main" class="site-main news-archive-main">
    <?php
    if ( is_multisite() && is_main_site() ) {
        $paged      = max( 1, (int) get_query_var( 'paged' ) );
        $collection = akishima_get_network_jichikai_posts(
            array(
                'posts_per_page' => 9,
                'paged'          => $paged,
            )
        );
        get_template_part(
            'template-parts/news/jichikai-list',
            null,
            array(
                'label'      => '自治会からのお知らせ',
                'collection' => $collection,
                'paged'      => $paged,
            )
        );
    } else {
        get_template_part(
            'template-parts/news/archive-content',
            null,
            array(
                'post_type' => 'jichikai',
                'label'     => '自治会からのお知らせ',
            )
        );
    }
    ?>
</main>
<?php
get_footer();
