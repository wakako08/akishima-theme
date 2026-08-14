<?php
/**
 * ニュース記事行（1行分）- WP_Queryのループ内で使用、または $args['post'] でネットワーク投稿
 */
$row_post = isset( $args['post'] ) ? $args['post'] : null;

if ( $row_post ) {
    $permalink      = ! empty( $row_post->akishima_permalink ) ? $row_post->akishima_permalink : get_permalink( $row_post );
    $community_slug = ! empty( $row_post->akishima_community_slug ) ? $row_post->akishima_community_slug : '';
    $title          = get_the_title( $row_post );
    $date_attr      = get_the_date( 'Y-m-d', $row_post );
    $date_label     = get_the_date( 'Y年n月j日', $row_post );
    $excerpt        = has_excerpt( $row_post ) ? get_the_excerpt( $row_post ) : wp_strip_all_tags( $row_post->post_content );
} else {
    $excerpt        = has_excerpt() ? get_the_excerpt() : wp_strip_all_tags( get_the_content() );
    $permalink      = get_permalink();
    $community_slug = get_query_var( 'akishima_news_community_slug' );
    $title          = get_the_title();
    $date_attr      = get_the_date( 'Y-m-d' );
    $date_label     = get_the_date( 'Y年n月j日' );
}

$post_type   = $row_post ? $row_post->post_type : get_post_type();
$is_news_cpt = in_array( $post_type, array( 'jichiren', 'kairan', 'jichikai' ), true );

if ( $community_slug && ( $row_post || 'jichikai' === $post_type ) ) {
    $archive_community = get_query_var( 'akishima_news_community_slug' );
    if ( $archive_community || ( is_multisite() && ! is_main_site() ) ) {
        $permalink = add_query_arg( 'from_community', sanitize_title( $community_slug ), $permalink );
    } else {
        $permalink = add_query_arg( 'from', 'main', $permalink );
    }
} elseif ( is_multisite() && is_main_site() && $is_news_cpt ) {
    $permalink = add_query_arg( 'from', 'main', $permalink );
}
?>
<article class="news-post-row" itemscope itemtype="https://schema.org/Article">
    <a href="<?php echo esc_url( $permalink ); ?>" class="news-post-row__link" aria-label="<?php echo esc_attr( $title ); ?>">
        <div class="news-post-row__body">
            <div class="news-post-row__meta">
                <time class="news-post-row__date" datetime="<?php echo esc_attr( $date_attr ); ?>">
                    <?php echo esc_html( $date_label ); ?>
                </time>
            </div>
            <h3 class="news-post-row__title" itemprop="headline"><?php echo esc_html( $title ); ?></h3>
            <?php if ( $excerpt ) : ?>
                <p class="news-post-row__excerpt"><?php echo esc_html( $excerpt ); ?></p>
            <?php endif; ?>
        </div>
        <div class="news-post-row__arrow" aria-hidden="true">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/btn-news-arrow.svg' ); ?>" alt="" width="24" height="24">
        </div>
    </a>
</article>
