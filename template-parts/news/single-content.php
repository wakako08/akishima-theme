<?php
/**
 * 詳細ページ共通テンプレート
 * $args['label'] … カテゴリラベル（例: '自治連からのお知らせ'）
 */
$pt    = get_post_type();
$label = isset( $args['label'] ) ? $args['label'] : get_post_type_object( $pt )->labels->name;

$back_url  = function_exists( 'akishima_news_single_back_url' )
    ? akishima_news_single_back_url( $pt )
    : ( akishima_news_archive_url( $pt ) ?: akishima_news_page_url() );
$back_text = 'BACK';

// 回覧PDFのURL
$pdf_url = ( $pt === 'kairan' ) ? get_post_meta( get_the_ID(), '_kairan_pdf_url', true ) : '';
?>

<!-- ページヒーロー（ライト版：背景なし、ダークネイビーの文字） -->
<div class="news-single-hero" aria-hidden="true">
    <div class="news-single-hero__titles">
        <p class="news-single-hero__en">NEWS</p>
        <p class="news-single-hero__ja">お知らせ</p>
    </div>
</div>

<div class="news-single-page">
    <div class="news-single-page__inner">

        <article class="news-single-article" itemscope itemtype="https://schema.org/Article">

            <!-- 記事ヘッダー -->
            <header class="news-single-article__header">
                <h1 class="news-single-article__title" itemprop="headline"><?php the_title(); ?></h1>
                <div class="news-single-article__meta">
                    <time class="news-single-article__date"
                          datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
                        <?php echo esc_html( get_the_date( 'Y年n月j日' ) ); ?>
                    </time>
                    <span class="news-single-article__badge"><?php echo esc_html( $label ); ?></span>
                </div>
            </header>

            <hr class="news-single-article__divider" aria-hidden="true">

            <!-- 回覧: PDFプレビュー -->
            <?php if ( $pt === 'kairan' && $pdf_url ) : ?>
                <div class="kairan-pdf-wrap">
                    <iframe
                        src="<?php echo esc_url( $pdf_url ); ?>"
                        class="kairan-pdf-embed"
                        title="<?php the_title_attribute(); ?>"
                        loading="lazy"
                    ></iframe>
                    <div class="kairan-pdf-actions">
                        <a href="<?php echo esc_url( $pdf_url ); ?>"
                           class="kairan-pdf-download"
                           target="_blank"
                           rel="noopener noreferrer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            PDFをダウンロード
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- 記事本文 -->
            <div class="news-single-article__body" itemprop="articleBody">
                <?php the_content(); ?>
            </div>

        </article>

        <!-- 一覧へ戻る -->
        <div class="news-single-back">
            <a href="<?php echo esc_url( $back_url ); ?>" class="news-back-btn" aria-label="<?php echo esc_attr( $back_text === 'BACK' ? '前のページへ戻る' : $label . '一覧へ戻る' ); ?>">
                <?php get_template_part( 'template-parts/shared/action-btn-arrow' ); ?>
                <span><?php echo esc_html( $back_text ); ?></span>
            </a>
        </div>

    </div><!-- /.news-single-page__inner -->
</div><!-- /.news-single-page -->
