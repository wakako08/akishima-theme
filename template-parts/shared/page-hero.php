<?php
/**
 * 固定ページ共通ヒーロー（画像 + オーバーレイ + 英日タイトル）
 *
 * @param string $title_en        英語タイトル
 * @param string $title_ja        日本語タイトル
 * @param string $image           テーマ内画像パス or 絶対URL
 * @param string $image_position  object-position（省略時: center）
 */
$hero_title_en = isset( $args['title_en'] ) ? $args['title_en'] : '';
$hero_title_ja = isset( $args['title_ja'] ) ? $args['title_ja'] : '';
$hero_image    = isset( $args['image'] ) ? $args['image'] : '';
$hero_position = isset( $args['image_position'] ) ? $args['image_position'] : 'center';

if ( 0 === strpos( $hero_image, 'http' ) ) {
    $hero_image_url = $hero_image;
} else {
    $hero_image_url = get_template_directory_uri() . '/' . ltrim( $hero_image, '/' );
}

$img_style = 'center' !== $hero_position ? 'object-position: ' . $hero_position . ';' : '';
?>
<div class="page-hero">
    <div class="page-hero__bg" aria-hidden="true">
        <img
            src="<?php echo esc_url( $hero_image_url ); ?>"
            alt=""
            class="page-hero__bg-img"
            loading="eager"
            fetchpriority="high"
            <?php if ( $img_style ) : ?>style="<?php echo esc_attr( $img_style ); ?>"<?php endif; ?>
        >
        <div class="page-hero__overlay"></div>
    </div>
    <div class="page-hero__content">
        <?php if ( $hero_title_en ) : ?>
            <p class="page-hero__title-en"><?php echo esc_html( $hero_title_en ); ?></p>
        <?php endif; ?>
        <?php if ( $hero_title_ja ) : ?>
            <p class="page-hero__title-ja"><?php echo esc_html( $hero_title_ja ); ?></p>
        <?php endif; ?>
    </div>
</div>
