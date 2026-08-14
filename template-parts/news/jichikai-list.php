<?php
/**
 * 自治会お知らせ一覧（ネットワーク集約 or 通常アーカイブ）
 *
 * $args['collection'] がある場合はネットワーク集約表示
 */
$label      = isset( $args['label'] ) ? $args['label'] : '自治会からのお知らせ';
$collection = isset( $args['collection'] ) ? $args['collection'] : null;
$paged      = isset( $args['paged'] ) ? (int) $args['paged'] : max( 1, (int) get_query_var( 'paged' ) );
$back_url   = isset( $args['back_url'] )
    ? $args['back_url']
    : ( ( is_multisite() && ! is_main_site() ) ? akishima_main_news_page_url() : akishima_news_page_url() );
$back_text  = isset( $args['back_text'] ) ? $args['back_text'] : 'NEWS TOP';

get_template_part( 'template-parts/news/page-hero', null, array(
    'title_en' => 'NEWS',
    'title_ja' => 'お知らせ',
) );
?>

<div class="news-archive-page">
    <div class="news-archive-page__inner">

        <div class="news-archive-page__header">
            <h1 class="news-archive-section-title"><?php echo esc_html( $label ); ?></h1>
        </div>

        <?php if ( $collection ) : ?>
            <?php if ( ! empty( $collection->posts ) ) : ?>
                <div class="news-post-list">
                    <?php foreach ( $collection->posts as $network_post ) : ?>
                        <?php
                        get_template_part(
                            'template-parts/news/post-row',
                            null,
                            array( 'post' => $network_post )
                        );
                        ?>
                    <?php endforeach; ?>
                </div>

                <?php if ( $collection->max_num_pages > 1 ) : ?>
                    <?php
                    $total   = $collection->max_num_pages;
                    $current = $paged;
                    $base    = trailingslashit( home_url( '/news/' ) ) . '%_%';
                    if ( is_main_site() ) {
                        $base = trailingslashit( akishima_news_archive_url( 'jichikai' ) ) . '%_%';
                    }
                    ?>
                    <nav class="news-pager" aria-label="ページナビゲーション">
                        <div class="news-pager__inner">
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

                            <div class="news-pager__pages">
                                <?php for ( $i = 1; $i <= $total; $i++ ) : ?>
                                    <?php if ( $i === $current ) : ?>
                                        <span class="news-pager__page is-current" aria-current="page"><?php echo (int) $i; ?></span>
                                    <?php else : ?>
                                        <a href="<?php echo esc_url( get_pagenum_link( $i ) ); ?>" class="news-pager__page"><?php echo (int) $i; ?></a>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>

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

        <?php elseif ( have_posts() ) : ?>
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
            <nav class="news-pager" aria-label="ページナビゲーション">
                <div class="news-pager__inner">
                    <?php if ( $current > 1 ) : ?>
                        <a href="<?php echo esc_url( get_pagenum_link( $current - 1 ) ); ?>" class="news-pager__btn news-pager__btn--prev" aria-label="前のページ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="14" viewBox="0 0 20 14" fill="none" aria-hidden="true">
                                <path d="M19 7H1M1 7L7 1M1 7L7 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                    <div class="news-pager__pages">
                        <?php for ( $i = 1; $i <= $total; $i++ ) : ?>
                            <?php if ( $i === $current ) : ?>
                                <span class="news-pager__page is-current" aria-current="page"><?php echo (int) $i; ?></span>
                            <?php else : ?>
                                <a href="<?php echo esc_url( get_pagenum_link( $i ) ); ?>" class="news-pager__page"><?php echo (int) $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <?php if ( $current < $total ) : ?>
                        <a href="<?php echo esc_url( get_pagenum_link( $current + 1 ) ); ?>" class="news-pager__btn news-pager__btn--next" aria-label="次のページ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="14" viewBox="0 0 20 14" fill="none" aria-hidden="true">
                                <path d="M1 7H19M19 7L13 1M19 7L13 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
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

    </div>
</div>
