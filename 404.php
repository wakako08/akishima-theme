<?php
/**
 * 404ページテンプレート
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <section class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title">404 - ページが見つかりません</h1>
            </header>
            <div class="page-content">
                <p>お探しのページは移動または削除された可能性があります。</p>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                    トップページへ戻る
                </a>
            </div>
        </section>
    </div>
</main>

<?php get_footer(); ?>
