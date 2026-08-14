<?php
/**
 * 行事予定 一覧ページ
 */
get_header(); ?>
<main id="main" class="site-main community-event-archive">
    <div class="community-event-archive__inner">
        <h1 class="community-event-archive__title">行事予定</h1>
        <?php if ( have_posts() ) : ?>
        <div class="community-event__list community-event-archive__list">
            <?php
            while ( have_posts() ) :
                the_post();
                set_query_var( 'akishima_event_item', akishima_normalize_community_event_from_post( get_the_ID() ) );
                get_template_part( 'template-parts/community/event-row' );
            endwhile;
            ?>
        </div>
        <?php the_posts_pagination(); ?>
        <?php else : ?>
        <p class="community-event__empty">行事予定はまだありません。</p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
