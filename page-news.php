<?php
/**
 * Template Name: NEWSページ
 * NEWSトップ：3つのCMS（自治連・回覧・自治会）を一覧化
 */

get_header(); ?>

<main id="main" class="site-main news-top-page">

    <?php get_template_part( 'template-parts/news/page-hero', null, array(
        'title_en' => 'NEWS',
        'title_ja' => 'お知らせ',
    ) ); ?>

    <!-- アンカーナビ（Figma 29:1065） -->
    <nav class="news-tab-nav" aria-label="お知らせカテゴリ">
        <div class="news-tab-nav__inner">
            <span class="news-tab-nav__sep" aria-hidden="true"></span>

            <a href="#section-jichiren" class="news-tab-nav__item">
                <span class="news-tab-nav__label">自治連からのお知らせ</span>
                <span class="news-tab-nav__icon" aria-hidden="true">
                    <svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
                        <rect width="18" height="12" fill="#4AEB38"/>
                        <path d="M2.65017 7.5C2.58453 7.5 2.5177 7.38337 2.51166 7.31701C2.47437 6.90131 2.54107 6.42722 2.51192 6.00517C2.50496 5.94962 2.59227 5.86354 2.63691 5.86354H11.7423L11.7297 5.8157L10.2002 3.84921C10.1112 3.68799 10.1722 3.52324 10.3587 3.5L11.3888 3.50432C11.4283 3.51406 11.5754 3.65988 11.6181 3.70082C12.493 4.54075 13.3526 5.41797 14.2046 6.28208C14.3165 6.39547 14.5848 6.5994 14.4735 6.77576C14.409 6.8778 14.2039 7.04024 14.112 7.13349C13.9916 7.25539 13.8712 7.37769 13.7508 7.49986L2.65017 7.5Z" fill="#002239"/>
                    </svg>
                </span>
            </a>

            <span class="news-tab-nav__sep" aria-hidden="true"></span>

            <a href="#section-kairan" class="news-tab-nav__item">
                <span class="news-tab-nav__label">回覧</span>
                <span class="news-tab-nav__icon" aria-hidden="true">
                    <svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
                        <rect width="18" height="12" fill="#4AEB38"/>
                        <path d="M2.65017 7.5C2.58453 7.5 2.5177 7.38337 2.51166 7.31701C2.47437 6.90131 2.54107 6.42722 2.51192 6.00517C2.50496 5.94962 2.59227 5.86354 2.63691 5.86354H11.7423L11.7297 5.8157L10.2002 3.84921C10.1112 3.68799 10.1722 3.52324 10.3587 3.5L11.3888 3.50432C11.4283 3.51406 11.5754 3.65988 11.6181 3.70082C12.493 4.54075 13.3526 5.41797 14.2046 6.28208C14.3165 6.39547 14.5848 6.5994 14.4735 6.77576C14.409 6.8778 14.2039 7.04024 14.112 7.13349C13.9916 7.25539 13.8712 7.37769 13.7508 7.49986L2.65017 7.5Z" fill="#002239"/>
                    </svg>
                </span>
            </a>

            <span class="news-tab-nav__sep" aria-hidden="true"></span>

            <a href="#section-jichikai" class="news-tab-nav__item news-tab-nav__item--pad">
                <span class="news-tab-nav__label">自治会からのお知らせ</span>
                <span class="news-tab-nav__icon" aria-hidden="true">
                    <svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
                        <rect width="18" height="12" fill="#4AEB38"/>
                        <path d="M2.65017 7.5C2.58453 7.5 2.5177 7.38337 2.51166 7.31701C2.47437 6.90131 2.54107 6.42722 2.51192 6.00517C2.50496 5.94962 2.59227 5.86354 2.63691 5.86354H11.7423L11.7297 5.8157L10.2002 3.84921C10.1112 3.68799 10.1722 3.52324 10.3587 3.5L11.3888 3.50432C11.4283 3.51406 11.5754 3.65988 11.6181 3.70082C12.493 4.54075 13.3526 5.41797 14.2046 6.28208C14.3165 6.39547 14.5848 6.5994 14.4735 6.77576C14.409 6.8778 14.2039 7.04024 14.112 7.13349C13.9916 7.25539 13.8712 7.37769 13.7508 7.49986L2.65017 7.5Z" fill="#002239"/>
                    </svg>
                </span>
            </a>

            <span class="news-tab-nav__sep" aria-hidden="true"></span>
        </div>
    </nav>

    <!-- CMSセクション一覧 -->
    <div class="news-top-sections">

        <?php
        $cms_sections = array(
            array(
                'id'        => 'section-jichiren',
                'post_type' => 'jichiren',
                'label'     => '自治連からのお知らせ',
                'archive'   => akishima_news_archive_url( 'jichiren' ),
            ),
            array(
                'id'        => 'section-kairan',
                'post_type' => 'kairan',
                'label'     => '回覧',
                'archive'   => akishima_news_archive_url( 'kairan' ),
            ),
            array(
                'id'        => 'section-jichikai',
                'post_type' => 'jichikai',
                'label'     => '自治会からのお知らせ',
                'archive'   => akishima_news_archive_url( 'jichikai' ),
            ),
        );

        foreach ( $cms_sections as $section ) :
            if ( 'jichikai' === $section['post_type'] && is_multisite() && is_main_site() ) {
                $collection = akishima_get_network_jichikai_posts( array( 'posts_per_page' => 3 ) );
            } else {
                $collection = null;
                $q          = new WP_Query( array(
                    'post_type'           => $section['post_type'],
                    'posts_per_page'      => 3,
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => true,
                ) );
            }
        ?>
        <section id="<?php echo esc_attr( $section['id'] ); ?>" class="news-top-section">
            <div class="news-top-section__inner">
                <div class="news-top-section__header">
                    <h2 class="news-top-section__title"><?php echo esc_html( $section['label'] ); ?></h2>
                </div>

                <?php if ( $collection ) : ?>
                    <?php if ( ! empty( $collection->posts ) ) : ?>
                    <div class="news-post-list">
                        <?php foreach ( $collection->posts as $network_post ) : ?>
                            <?php get_template_part( 'template-parts/news/post-row', null, array( 'post' => $network_post ) ); ?>
                        <?php endforeach; ?>
                    </div>
                    <?php else : ?>
                    <p class="news-top-section__empty">現在お知らせはありません。</p>
                    <?php endif; ?>
                <?php elseif ( $q->have_posts() ) : ?>
                    <div class="news-post-list">
                        <?php while ( $q->have_posts() ) : $q->the_post(); ?>
                            <?php get_template_part( 'template-parts/news/post-row' ); ?>
                        <?php endwhile; ?>
                    </div>
                <?php else : ?>
                    <p class="news-top-section__empty">現在お知らせはありません。</p>
                <?php endif;
                if ( ! $collection ) {
                    wp_reset_postdata();
                }
                ?>

                <?php if ( $section['archive'] ) : ?>
                    <div class="news-top-section__footer">
                        <a href="<?php echo esc_url( $section['archive'] ); ?>" class="news-viewmore-btn">
                            <span>VIEW ALL</span>
                            <?php get_template_part( 'template-parts/shared/action-btn-arrow' ); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php endforeach; ?>

    </div><!-- /.news-top-sections -->

</main>

<?php get_footer(); ?>
