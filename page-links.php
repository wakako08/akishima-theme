<?php
/**
 * 便利なリンク集ページ（links スラッグ用）
 * 本文: template-parts/pages/links.php
 * データ: assets/data/useful-links.json
 */

get_header(); ?>

<main id="main" class="site-main links-page">

    <?php get_template_part( 'template-parts/pages/links' ); ?>

</main>

<?php get_footer(); ?>
