<?php
/**
 * 行事予定 1行分
 *
 * @var array $event date, date_display, title, time, location, content, url
 */
$event = get_query_var( 'akishima_event_item' );
if ( ! is_array( $event ) ) {
    return;
}

$linked   = (bool) get_query_var( 'akishima_event_row_linked', true );
$has_meta = ! empty( $event['time'] ) || ! empty( $event['location'] );
$url      = ( $linked && ! empty( $event['url'] ) ) ? $event['url'] : '';
$body     = ! empty( $event['content'] ) ? $event['content'] : '';
?>
<article class="community-event-row">
    <?php if ( $url ) : ?>
    <a href="<?php echo esc_url( $url ); ?>" class="community-event-row__link">
    <?php else : ?>
    <div class="community-event-row__inner">
    <?php endif; ?>
        <div class="community-event-row__body">
            <div class="community-event-row__head">
                <?php if ( ! empty( $event['date_display'] ) ) : ?>
                <time class="community-event-row__date" datetime="<?php echo esc_attr( $event['date'] ); ?>">
                    <?php echo esc_html( $event['date_display'] ); ?>
                </time>
                <?php endif; ?>
                <?php if ( ! empty( $event['title'] ) ) : ?>
                <h3 class="community-event-row__title"><?php echo esc_html( $event['title'] ); ?></h3>
                <?php endif; ?>
            </div>

            <?php if ( $has_meta ) : ?>
            <div class="community-event-row__meta">
                <?php if ( ! empty( $event['time'] ) ) : ?>
                <span class="community-event-row__meta-item">
                    <svg class="community-event-row__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                        <circle cx="8" cy="8" r="6.5" stroke="#002239" stroke-width="1"/>
                        <path d="M8 4.5V8l2.5 1.5" stroke="#002239" stroke-width="1" stroke-linecap="round"/>
                    </svg>
                    <span><?php echo esc_html( $event['time'] ); ?></span>
                </span>
                <?php endif; ?>
                <?php if ( ! empty( $event['location'] ) ) : ?>
                <span class="community-event-row__meta-item">
                    <svg class="community-event-row__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                        <path d="M8 1.5C5.5 1.5 3.5 3.6 3.5 6.2c0 3.4 4.5 8.3 4.5 8.3S12.5 9.6 12.5 6.2C12.5 3.6 10.5 1.5 8 1.5z" stroke="#002239" stroke-width="1"/>
                        <circle cx="8" cy="6" r="1.5" fill="#002239"/>
                    </svg>
                    <span><?php echo esc_html( $event['location'] ); ?></span>
                </span>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if ( $body ) : ?>
            <p class="community-event-row__excerpt"><?php echo esc_html( $body ); ?></p>
            <?php endif; ?>
        </div>
        <?php if ( $url ) : ?>
        <div class="community-event-row__arrow" aria-hidden="true">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/btn-news-arrow.svg' ); ?>" alt="" width="24" height="24">
        </div>
        <?php endif; ?>
    <?php if ( $url ) : ?>
    </a>
    <?php else : ?>
    </div>
    <?php endif; ?>
</article>
