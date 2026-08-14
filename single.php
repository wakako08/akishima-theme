<?php
/**
 * 投稿詳細ページテンプレート
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php
        while ( have_posts() ) :
            the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    <div class="entry-meta">
                        <time class="entry-date" datetime="<?php echo get_the_date( 'c' ); ?>">
                            <?php echo get_the_date(); ?>
                        </time>
                        <?php the_category( ', ' ); ?>
                    </div>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="entry-thumbnail">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    the_content();
                    wp_link_pages( array(
                        'before' => '<div class="page-links">ページ:',
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>

                <footer class="entry-footer">
                    <?php the_tags( '<div class="entry-tags">タグ: ', ', ', '</div>' ); ?>
                </footer>

            </article>

            <nav class="post-navigation">
                <?php
                the_post_navigation( array(
                    'prev_text' => '&larr; %title',
                    'next_text' => '%title &rarr;',
                ) );
                ?>
            </nav>

        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
