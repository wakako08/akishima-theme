<?php
/**
 * 自治会 / ブロック個別ページ本文
 * 入口: community.php / block.php
 * データ: inc/community-content.php / assets/data/communities/
 */
// --- page-hero ---
/**
 * 自治会個別ページ: ページトップ（ブロックバッジ + 自治会名）
 */
$community = get_query_var( 'akishima_community_data' );
if ( is_array( $community ) ) :
$block_label = isset( $community['block_label'] ) ? $community['block_label'] : '';
$name        = isset( $community['name'] ) ? $community['name'] : '';
?>
<section class="community-page-top">
    <div class="community-page-top__inner">
        <?php if ( $block_label ) : ?>
        <p class="community-page-top__badge"><?php echo esc_html( $block_label ); ?></p>
        <?php endif; ?>
        <?php if ( $name ) : ?>
        <h1 class="community-page-top__title"><?php echo esc_html( $name ); ?></h1>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php
// --- info-section ---
/**
 * 自治会個別ページ: 基本情報 + ギャラリー
 *
 * コンテンツ: assets/data/communities/{スラッグ}.json
 * 画像:      assets/images/communities/{スラッグ}/gallery-1.jpg ～
 */
$community = get_query_var( 'akishima_community_data' );
if ( is_array( $community ) ) :

$content = akishima_get_community_content( $community );
$slug    = isset( $community['slug'] ) ? $community['slug'] : '';
$gallery = akishima_get_community_gallery_images( $slug );

$blocks = array(
    array(
        'title' => '自治会名',
        'body'  => $content['name'],
        'show'  => ! empty( $content['name'] ),
    ),
    array(
        'title' => '自治会紹介文',
        'body'  => $content['intro'],
        'show'  => ! empty( $content['intro'] ),
    ),
    array(
        'title' => '主な活動',
        'body'  => $content['activities'],
        'show'  => ! empty( $content['activities'] ),
    ),
    array(
        'title' => '自治会費',
        'body'  => $content['fee'],
        'show'  => ! empty( $content['fee'] ),
    ),
    array(
        'title' => '構成団体',
        'body'  => $content['organizations'],
        'show'  => ! empty( $content['organizations'] ),
        'multiline' => true,
    ),
);
?>
<section class="community-info">
    <div class="community-info__inner">

        <?php foreach ( $blocks as $block ) : ?>
            <?php if ( ! $block['show'] ) {
                continue;
            } ?>
        <div class="community-info-block">
            <div class="community-info-block__header">
                <h2 class="community-info-block__title"><?php echo esc_html( $block['title'] ); ?></h2>
            </div>
            <div class="community-info-block__body">
                <?php if ( ! empty( $block['multiline'] ) ) : ?>
                    <?php
                    $lines = array_filter( array_map( 'trim', explode( "\n", $block['body'] ) ) );
                    foreach ( $lines as $line ) :
                    ?>
                    <p><?php echo esc_html( $line ); ?></p>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p><?php echo esc_html( $block['body'] ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if ( ! empty( $gallery ) ) : ?>
        <div class="community-gallery">
            <?php foreach ( $gallery as $index => $image ) :
                $alt = ! empty( $image['alt'] ) ? $image['alt'] : ( $content['name'] . ' の写真 ' . ( $index + 1 ) );
            ?>
            <figure class="community-gallery__item">
                <img
                    src="<?php echo esc_url( $image['url'] ); ?>"
                    alt="<?php echo esc_attr( $alt ); ?>"
                    class="community-gallery__img"
                    loading="lazy"
                >
            </figure>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </div>
</section>
<?php endif; ?>

<?php
// --- facilities-section ---
/**
 * 自治会個別ページ: 自治会館・集会施設等
 *
 * テキスト: assets/data/communities/{スラッグ}.json
 * 画像:    assets/images/communities/{スラッグ}/facility-1.jpg ～
 */
$community = get_query_var( 'akishima_community_data' );
if ( is_array( $community ) ) :

$content = akishima_get_community_content( $community );
$slug    = isset( $community['slug'] ) ? $community['slug'] : '';
$gallery = akishima_get_community_facility_images( $slug );

$blocks = array(
    array(
        'title' => '自治会館・集会施設等',
        'body'  => $content['facility_name'],
        'show'  => ! empty( $content['facility_name'] ),
    ),
    array(
        'title' => '所在地',
        'body'  => $content['facility_address'],
        'show'  => ! empty( $content['facility_address'] ),
    ),
    array(
        'title'     => '会員外への貸出',
        'body'      => $content['facility_rental'],
        'show'      => ! empty( $content['facility_rental'] ),
        'multiline' => true,
    ),
);

$has_content = false;
foreach ( $blocks as $block ) {
    if ( $block['show'] ) {
        $has_content = true;
        break;
    }
}
// NOTE: 統合ファイル内では return 禁止（後続の NEWS / EVENT まで止まる）
if ( $has_content || ! empty( $gallery ) ) :
?>
<section class="community-facilities">
    <div class="community-facilities__inner">

        <h2 class="community-section-heading">
            <span class="community-section-heading__bar" aria-hidden="true"></span>
            自治会館・集会施設等
        </h2>

        <div class="community-facilities__blocks">
            <?php foreach ( $blocks as $block ) : ?>
                <?php if ( ! $block['show'] ) {
                    continue;
                } ?>
            <div class="community-info-block">
                <div class="community-info-block__header">
                    <h3 class="community-info-block__title"><?php echo esc_html( $block['title'] ); ?></h3>
                </div>
                <div class="community-info-block__body">
                    <?php if ( ! empty( $block['multiline'] ) ) : ?>
                        <?php
                        $lines = array_filter( array_map( 'trim', explode( "\n", $block['body'] ) ) );
                        foreach ( $lines as $line ) :
                        ?>
                        <p><?php echo esc_html( $line ); ?></p>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p><?php echo esc_html( $block['body'] ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if ( ! empty( $gallery ) ) : ?>
            <div class="community-gallery">
                <?php foreach ( $gallery as $index => $image ) :
                    $alt = ! empty( $image['alt'] ) ? $image['alt'] : ( '自治会館・集会施設の写真 ' . ( $index + 1 ) );
                ?>
                <figure class="community-gallery__item">
                    <img
                        src="<?php echo esc_url( $image['url'] ); ?>"
                        alt="<?php echo esc_attr( $alt ); ?>"
                        class="community-gallery__img"
                        loading="lazy"
                    >
                </figure>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

    </div>
</section>
<?php
endif; // has facilities content
endif; // is_array community (facilities)

// --- news-section ---
/**
 * 自治会個別ページ: NEWS（最近の投稿）
 * 子サイトCMSのお知らせを表示（ローカルは jichikai + 自治会スラッグ）
 */
$community = get_query_var( 'akishima_community_data' );
if ( is_array( $community ) ) :
$news_query  = akishima_get_community_news_query( $community, 4 );
$archive_url = akishima_community_news_archive_url( $community );
$is_external = akishima_community_is_external( $community );
?>
<section class="community-news">
    <div class="community-news__inner">

        <div class="community-news__header">
            <div class="section-deco-title" aria-label="最近の投稿 NEWS">
                <p class="section-deco-title__ja">最近の投稿</p>
                <p class="section-deco-title__en">NEWS</p>
            </div>
        </div>

        <?php if ( $news_query->have_posts() ) : ?>
        <div class="news-post-list community-news__list">
            <?php
            set_query_var( 'akishima_news_community_slug', $community['slug'] );
            while ( $news_query->have_posts() ) :
                $news_query->the_post();
                get_template_part( 'template-parts/news/post-row' );
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
        <?php else : ?>
        <p class="community-news__empty">お知らせはまだありません。</p>
        <?php endif; ?>

        <?php if ( $archive_url ) : ?>
        <div class="community-news__footer">
            <a
                href="<?php echo esc_url( $archive_url ); ?>"
                class="news-viewmore-btn community-news__viewall"
                <?php echo $is_external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>
            >
                <span>VIEW ALL</span>
                <?php get_template_part( 'template-parts/shared/action-btn-arrow' ); ?>
            </a>
        </div>
        <?php endif; ?>

    </div>
</section>
<?php endif; ?>

<?php
// --- event-section ---
/**
 * 自治会個別ページ: 行事予定（EVENT）
 */
$community = get_query_var( 'akishima_community_data' );
if ( is_array( $community ) ) :
$per_page = ( is_multisite() && ! is_main_site() ) ? -1 : 3;
$events   = akishima_get_community_events_list( $community, $per_page );

set_query_var( 'akishima_event_row_linked', ! ( is_multisite() && ! is_main_site() ) );
?>
<section class="community-event">
    <div class="community-event__inner">

        <div class="community-event__header">
            <div class="section-deco-title" aria-label="行事予定 EVENT">
                <p class="section-deco-title__ja">行事予定</p>
                <p class="section-deco-title__en">EVENT</p>
            </div>
        </div>

        <?php if ( ! empty( $events ) ) : ?>
        <div class="community-event__list">
            <?php foreach ( $events as $event ) : ?>
                <?php
                set_query_var( 'akishima_event_item', $event );
                get_template_part( 'template-parts/community/event-row' );
                ?>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
        <p class="community-event__empty">行事予定はまだありません。</p>
        <?php endif; ?>

    </div>
</section>
<?php endif; ?>
