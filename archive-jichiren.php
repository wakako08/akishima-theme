<?php
/**
 * 自治連からのお知らせ 一覧ページ
 */
get_header(); ?>
<main id="main" class="site-main">
    <?php get_template_part( 'template-parts/news/archive-content', null, array(
        'post_type' => 'jichiren',
        'label'     => '自治連からのお知らせ',
    ) ); ?>
</main>
<?php get_footer(); ?>
