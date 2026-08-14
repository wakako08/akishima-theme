<?php
/**
 * 自治連からのお知らせ 詳細ページ
 */
get_header(); ?>
<main id="main" class="site-main">
    <?php while ( have_posts() ) : the_post(); ?>
        <?php get_template_part( 'template-parts/news/single-content', null, array(
            'label' => '自治連からのお知らせ',
        ) ); ?>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
