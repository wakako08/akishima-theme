<?php
/**
 * 回覧 一覧ページ
 */
get_header(); ?>
<main id="main" class="site-main">
    <?php get_template_part( 'template-parts/news/archive-content', null, array(
        'post_type' => 'kairan',
        'label'     => '回覧',
    ) ); ?>
</main>
<?php get_footer(); ?>
