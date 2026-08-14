<?php
/**
 * 便利なリンク集ページ本文
 * データ: assets/data/useful-links.json
 * 入口: page-links.php
 */

/**
 * 便利なリンク集ページ ヒーローセクション
 */
get_template_part(
    'template-parts/shared/page-hero',
    null,
    array(
        'title_en'       => 'LINKS',
        'title_ja'       => '便利なリンク集',
        'image'          => 'assets/images/contact/contact-hero.png',
        'image_position' => 'center 40%',
    )
);
// --- template-parts/links/content.php ---
/**
 * 便利なリンク集 本文
 */
$sections = akishima_get_useful_links_sections();
?>
<section class="links-page-body">
    <div class="links-page-body__inner">

        <?php if ( empty( $sections ) ) : ?>
            <p class="links-page-body__empty">リンク情報を読み込めませんでした。</p>
        <?php else : ?>
            <div class="links-sections">
                <?php foreach ( $sections as $section ) : ?>
                    <section class="links-section" id="<?php echo esc_attr( sanitize_title( $section['title'] ) ); ?>">
                        <h2 class="links-section__title"><?php echo esc_html( $section['title'] ); ?></h2>

                        <?php foreach ( $section['groups'] as $group ) : ?>
                            <?php if ( ! empty( $group['title'] ) ) : ?>
                                <h3 class="links-section__subtitle"><?php echo esc_html( $group['title'] ); ?></h3>
                            <?php endif; ?>

                            <?php if ( ! empty( $group['items'] ) ) : ?>
                                <ul class="links-list">
                                    <?php foreach ( $group['items'] as $item ) : ?>
                                        <li class="links-list__item">
                                            <?php
                                            $phone_entry = empty( $item['url'] ) ? akishima_parse_phone_label( $item['label'] ) : null;
                                            if ( $phone_entry ) :
                                                ?>
                                                <span class="links-list__text">
                                                    <?php if ( '' !== $phone_entry['name'] ) : ?>
                                                        <span class="links-list__name"><?php echo esc_html( $phone_entry['name'] ); ?></span>
                                                    <?php endif; ?>
                                                    <?php foreach ( $phone_entry['phones'] as $phone_index => $phone ) : ?>
                                                        <?php if ( $phone_index > 0 ) : ?>
                                                            <span class="links-list__phone-sep">又は</span>
                                                        <?php endif; ?>
                                                        <a class="links-list__phone" href="<?php echo esc_attr( akishima_normalize_tel_href( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                                                    <?php endforeach; ?>
                                                </span>
                                            <?php elseif ( ! empty( $item['url'] ) ) : ?>
                                                <a
                                                    class="links-list__link"
                                                    href="<?php echo esc_url( $item['url'] ); ?>"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                ><?php echo esc_html( $item['label'] ); ?></a>
                                            <?php else : ?>
                                                <span class="links-list__text"><?php echo esc_html( $item['label'] ); ?></span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </section>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
