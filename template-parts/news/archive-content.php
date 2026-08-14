<?php
/**
 * アーカイブ共通テンプレート（一覧ページ本体）
 * 呼び出し元から $args['post_type'] と $args['label'] を受け取る
 */
$pt        = isset( $args['post_type'] ) ? $args['post_type'] : get_post_type();
$label     = isset( $args['label'] )     ? $args['label']     : get_post_type_object( $pt )->labels->name;
$back_url  = ( is_multisite() && ! is_main_site() )
    ? akishima_main_news_page_url()
    : akishima_news_page_url();
$back_text = 'NEWS TOP';

$community_slug = isset( $_GET['community'] ) ? sanitize_title( wp_unslash( $_GET['community'] ) ) : '';
if ( $community_slug && 'jichikai' === $pt && function_exists( 'akishima_community_news_back_url' ) ) {
    $community_back = akishima_community_news_back_url( $community_slug );
    if ( $community_back ) {
        $back_url  = $community_back;
        $back_text = 'BACK';
        set_query_var( 'akishima_news_community_slug', $community_slug );
    }
}

// メインサイト: 自治会お知らせは子サイト投稿を集約表示（標準 WP_Query は空にしている）
if ( 'jichikai' === $pt && is_multisite() && is_main_site() && function_exists( 'akishima_get_network_jichikai_posts' ) ) {
    $paged      = max( 1, (int) get_query_var( 'paged' ) );
    $collection = akishima_get_network_jichikai_posts(
        array(
            'posts_per_page' => 9,
            'paged'          => $paged,
            'community_slug' => $community_slug,
        )
    );

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
    return;
}

get_template_part( 'template-parts/news/page-hero', null, array(
    'title_en' => 'NEWS',
    'title_ja' => 'お知らせ',
) );
?>

<div class="news-archive-page">
    <div class="news-archive-page__inner">

        <!-- セクションタイトル -->
        <div class="news-archive-page__header">
            <h1 class="news-archive-section-title"><?php echo esc_html( $label ); ?></h1>
        </div>

        <?php if ( have_posts() ) : ?>
            <div class="news-post-list">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template-parts/news/post-row' ); ?>
                <?php endwhile; ?>
            </div>

            <?php
            $total   = $GLOBALS['wp_query']->max_num_pages;
            $current = max( 1, get_query_var( 'paged' ) );
            if ( $total > 1 ) :
            ?>
            <!-- ページネーション（2ページ以上あるときのみ） -->
            <nav class="news-pager" aria-label="ページナビゲーション">
                <div class="news-pager__inner">
                    <!-- Prev -->
                    <?php if ( $current > 1 ) : ?>
                        <a href="<?php echo esc_url( get_pagenum_link( $current - 1 ) ); ?>" class="news-pager__btn news-pager__btn--prev" aria-label="前のページ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="14" viewBox="0 0 20 14" fill="none" aria-hidden="true">
                                <path d="M19 7H1M1 7L7 1M1 7L7 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    <?php else : ?>
                        <span class="news-pager__btn news-pager__btn--prev is-disabled" aria-disabled="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="14" viewBox="0 0 20 14" fill="none" aria-hidden="true">
                                <path d="M19 7H1M1 7L7 1M1 7L7 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    <?php endif; ?>

                    <!-- ページ番号 -->
                    <div class="news-pager__pages">
                        <?php for ( $i = 1; $i <= $total; $i++ ) : ?>
                            <?php if ( $i === $current ) : ?>
                                <span class="news-pager__page is-current" aria-current="page"><?php echo $i; ?></span>
                            <?php else : ?>
                                <a href="<?php echo esc_url( get_pagenum_link( $i ) ); ?>" class="news-pager__page"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>

                    <!-- Next -->
                    <?php if ( $current < $total ) : ?>
                        <a href="<?php echo esc_url( get_pagenum_link( $current + 1 ) ); ?>" class="news-pager__btn news-pager__btn--next" aria-label="次のページ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="14" viewBox="0 0 20 14" fill="none" aria-hidden="true">
                                <path d="M1 7H19M19 7L13 1M19 7L13 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    <?php else : ?>
                        <span class="news-pager__btn news-pager__btn--next is-disabled" aria-disabled="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="14" viewBox="0 0 20 14" fill="none" aria-hidden="true">
                                <path d="M1 7H19M19 7L13 1M19 7L13 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    <?php endif; ?>
                </div>
            </nav>
            <?php endif; ?>

        <?php else : ?>
            <p class="news-archive-page__empty">現在お知らせはありません。</p>
        <?php endif; ?>

        <div class="news-single-back">
            <a href="<?php echo esc_url( $back_url ); ?>" class="news-back-btn" aria-label="<?php echo esc_attr( 'BACK' === $back_text ? '前のページへ戻る' : 'お知らせトップへ' ); ?>">
                <?php get_template_part( 'template-parts/shared/action-btn-arrow' ); ?>
                <span><?php echo esc_html( $back_text ); ?></span>
            </a>
        </div>

    </div><!-- /.news-archive-page__inner -->
</div><!-- /.news-archive-page -->
