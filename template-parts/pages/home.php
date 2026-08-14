<?php
/**
 * トップページ本文（1ファイルに集約）
 *
 * 運用メモ:
 * - FV画像: assets/images/fv/
 * - 協賛バナー: 本ファイル内 banners ＋ assets/images/banners/
 * - 加入CTA: template-parts/shared/cta.php
 * 入口: front-page.php
 */
// --- template-parts/home/hero.php ---
/**
 * トップページ: FV（ファーストビュー）ヒーローセクション
 */

$img_dir  = get_template_directory_uri() . '/assets/images/fv/';
$img_path = get_template_directory() . '/assets/images/fv/';

// スライド画像（PC・スマホ共通。別画像は使わない）
$fv_slide_files = array(
    'fv_slide01.png' => 'overlay--blue',
    'fv_slide02.png' => 'overlay--warm',
    'fv_slide03.png' => 'overlay--cool',
    'fv_slide04.png' => 'overlay--amber',
);
$fv_images      = array();

foreach ( $fv_slide_files as $filename => $overlay ) {
    $file = $img_path . $filename;
    $ver  = file_exists( $file ) ? filemtime( $file ) : time();
    $fv_images[] = array(
        'src'     => add_query_arg( 'v', $ver, $img_dir . $filename ),
        'overlay' => $overlay,
        'alt'     => '',
    );
}

$slides = array();
for ( $i = 0; $i < 4; $i++ ) {
    $rotated = array_merge(
        array_slice( $fv_images, $i ),
        array_slice( $fv_images, 0, $i )
    );
    $slides[] = array(
        'main' => $rotated[0],
        'sub'  => array_slice( $rotated, 1, 3 ),
    );
}
?>

<section class="fv-section" aria-label="メインビジュアル">

    <!-- 背景テキスト（デコレーション） -->
    <div class="fv-bg-text" aria-hidden="true">AKISHIMA</div>

    <!-- スライダー -->
    <div class="fv-slider" data-fv-slider>

        <div class="fv-track" data-fv-track>
            <?php foreach ( $slides as $index => $slide ) : ?>
            <div class="fv-frame<?php echo $index === 0 ? ' is-active' : ''; ?>" aria-hidden="<?php echo $index === 0 ? 'false' : 'true'; ?>">

                <!-- 大パネル -->
                <div class="fv-panel fv-panel--main">
                    <div class="fv-panel-bg" style="background-image: url('<?php echo esc_url( $slide['main']['src'] ); ?>');">
                        <div class="fv-panel-overlay <?php echo esc_attr( $slide['main']['overlay'] ); ?>"></div>
                    </div>
                </div>

                <!-- 小パネル × 3 -->
                <?php foreach ( $slide['sub'] as $sub ) : ?>
                <div class="fv-panel fv-panel--sub">
                    <div class="fv-panel-bg" style="background-image: url('<?php echo esc_url( $sub['src'] ); ?>');">
                        <div class="fv-panel-overlay <?php echo esc_attr( $sub['overlay'] ); ?>"></div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- 左下: キャッチコピー + ドット（AKISHIMA横） -->
    <div class="fv-ui">
        <div class="fv-copy" aria-label="メインキャッチコピー">
            <div class="fv-copy-inner">
                <div class="fv-copy-header">
                    <span class="fv-copy-label">昭島市自治会</span>
                    <span class="fv-copy-tagline">Connecting, Living.</span>
                </div>
                <h1 class="fv-copy-headline">つながる、暮らす。</h1>
            </div>
        </div>

        <div class="fv-dots" role="tablist" aria-label="スライド選択">
            <?php foreach ( $slides as $index => $slide ) : ?>
            <button
                class="fv-dot<?php echo $index === 0 ? ' is-active' : ''; ?>"
                role="tab"
                aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                aria-label="スライド <?php echo $index + 1; ?>"
                data-fv-dot="<?php echo $index; ?>"
            ></button>
            <?php endforeach; ?>
        </div>
    </div>

</section>

<?php
// --- template-parts/home/news.php ---
/**
 * トップページ: NEWSセクション
 * /news/ と同じ3種類（自治連・回覧・自治会）の最新記事を表示
 */

$home_news_items = function_exists( 'akishima_get_home_news_posts' )
    ? akishima_get_home_news_posts( 15 )
    : array();

$total_posts    = count( $home_news_items );
$cards_per_page = 3;
$total_pages    = $total_posts > 0 ? (int) ceil( $total_posts / $cards_per_page ) : 1;

$news_page_url = akishima_news_page_url();
?>

<section id="news" class="news-section">
    <div class="news-inner">

        <!-- ヘッダー行: タイトル + ALL NEWS ボタン -->
        <div class="news-header">

            <div class="section-deco-title" aria-label="お知らせ NEWS">
                <div class="section-deco-title__ja">お知らせ</div>
                <div class="section-deco-title__en">NEWS</div>
            </div>

            <a href="<?php echo esc_url( $news_page_url ); ?>" class="news-all-btn" aria-label="お知らせ一覧へ">
                <span>ALL NEWS</span>
                <svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                    <rect width="18" height="12" fill="#4AEB38"/>
                    <path d="M2.65017 7.5C2.58453 7.5 2.5177 7.38337 2.51166 7.31701C2.47437 6.90131 2.54107 6.42722 2.51192 6.00517C2.50496 5.94962 2.59227 5.86354 2.63691 5.86354H11.7423L11.7297 5.8157L10.2002 3.84921C10.1112 3.68799 10.1722 3.52324 10.3587 3.5L11.3888 3.50432C11.4283 3.51406 11.5754 3.65988 11.6181 3.70082C12.493 4.54075 13.3526 5.41797 14.2046 6.28208C14.3165 6.39547 14.5848 6.5994 14.4735 6.77576C14.409 6.8778 14.2039 7.04024 14.112 7.13349C13.9916 7.25539 13.8712 7.37769 13.7508 7.49986L2.65017 7.5Z" fill="#002239"/>
                </svg>
            </a>

        </div>

        <!-- カードスライダー -->
        <div class="news-slider-wrap" data-news-slider>

            <div class="news-track" data-news-track>
                <?php if ( ! empty( $home_news_items ) ) : ?>
                    <?php foreach ( $home_news_items as $news_item ) : ?>

                        <a href="<?php echo esc_url( $news_item->permalink ); ?>" class="news-card" aria-label="<?php echo esc_attr( $news_item->title ); ?>">

                            <span class="news-card__glow" aria-hidden="true"></span>

                            <span class="news-card__surface">
                                <div class="news-card-body">
                                    <h3 class="news-card-title"><?php echo esc_html( $news_item->title ); ?></h3>
                                    <p class="news-card-excerpt"><?php echo esc_html( $news_item->excerpt ); ?></p>
                                </div>

                                <div class="news-card-footer">
                                    <span class="news-card-cat"><?php echo esc_html( $news_item->category_label ); ?></span>
                                    <span class="news-card-arrow" aria-hidden="true">
                                        <img
                                            src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/news-card-arrow.svg' ); ?>"
                                            alt=""
                                            width="14"
                                            height="5"
                                        >
                                    </span>
                                </div>
                            </span>

                        </a>

                    <?php endforeach; ?>

                <?php else : ?>
                    <p class="news-empty">投稿はまだありません。</p>
                <?php endif; ?>
            </div>

        </div>

        <!-- ナビゲーション: 前へ / ドット / 次へ -->
        <div class="news-nav">

            <button class="news-nav-btn news-nav-prev" type="button" aria-label="前のページ" data-news-prev>
                <img
                    src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/news-nav-arrow-prev.svg' ); ?>"
                    alt=""
                    width="51"
                    height="34"
                    class="news-nav-btn__img"
                >
            </button>

            <div class="news-nav-dots" data-news-dots aria-label="ページ" role="tablist">
                <?php for ( $i = 0; $i < $total_pages; $i++ ) : ?>
                    <button
                        class="news-nav-dot<?php echo $i === 0 ? ' is-active' : ''; ?>"
                        role="tab"
                        aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                        aria-label="<?php echo $i + 1; ?>ページ目"
                        data-news-page="<?php echo $i; ?>"
                    ></button>
                <?php endfor; ?>
            </div>

            <button class="news-nav-btn news-nav-next" type="button" aria-label="次のページ" data-news-next>
                <img
                    src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/news-nav-arrow-next.svg' ); ?>"
                    alt=""
                    width="51"
                    height="34"
                    class="news-nav-btn__img"
                >
            </button>

        </div>

    </div>
</section>

<?php
// --- template-parts/home/about.php ---
/**
 * トップページ: ABOUTセクション
 * 「自治連とは」 — 背景動画 + 2カラムレイアウト
 */

// AboutページのURLを取得（スラッグ "about" のページを参照）
$about_page = get_page_by_path( 'about' );
$about_url  = $about_page ? get_permalink( $about_page->ID ) : home_url( '/about/' );
?>

<section id="about" class="about-section">

    <!-- 背景レイヤー（Figma 2169:159） -->
    <div class="about-bg" aria-hidden="true">
        <div class="about-bg-video-wrap">
            <video
                class="about-bg-video"
                data-about-bg-video
                autoplay
                muted
                loop
                playsinline
                preload="auto"
            >
                <source src="<?php echo esc_url( get_template_directory_uri() . '/assets/videos/about-bg.mp4' ); ?>" type="video/mp4">
            </video>
        </div>
        <div class="about-bg-overlay about-bg-overlay--top"></div>
        <div class="about-bg-overlay about-bg-overlay--bottom">
            <div class="about-bg-overlay__inner"></div>
        </div>
        <div class="about-deco">
            <span class="about-deco__text">OVERVIEW</span>
        </div>
    </div>

    <!-- コンテンツ -->
    <div class="about-inner">

        <!-- 左: タイトル -->
        <div class="section-deco-title about-title">
            <div class="section-deco-title__ja">自治連とは</div>
            <div class="section-deco-title__en">ABOUT</div>
        </div>

        <!-- 右: テキスト + ボタン -->
        <div class="about-content">

            <div class="about-text">
                <p><strong>昭島市自治会連合会</strong><small>（以下、「自治連」という。）</small>は、<br>
                1959年（昭和34年）5月に設立された昭島市自治会連絡協議会を前身とし、<br>
                1971年（昭和46年）に組織名を変更し現在に至っております。</p>

                <p>自治連は、現在約90の単一自治会で構成されており、<br>
                その活動、運用を円滑に行うため２０のブロックに分けて組織しています。</p>

                <p>自治連の組織は、常任委員会を最高執行機関に位置付け、<br>
                そのもとに三役会、事務局、運営に携わる常設の4つの委員会<small>（総務委員会、事業委員会、研修・防災委員会、広報委員会）</small>、<br>
                必要に応じ設置する専門委員会を設けております。</p>
            </div>

            <a href="<?php echo esc_url( $about_url ); ?>" class="about-more-btn">
                <span>ABOUT</span>
                <svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                    <rect width="18" height="12" fill="#4AEB38"/>
                    <path d="M2.65017 7.5C2.58453 7.5 2.5177 7.38337 2.51166 7.31701C2.47437 6.90131 2.54107 6.42722 2.51192 6.00517C2.50496 5.94962 2.59227 5.86354 2.63691 5.86354H11.7423L11.7297 5.8157L10.2002 3.84921C10.1112 3.68799 10.1722 3.52324 10.3587 3.5L11.3888 3.50432C11.4283 3.51406 11.5754 3.65988 11.6181 3.70082C12.493 4.54075 13.3526 5.41797 14.2046 6.28208C14.3165 6.39547 14.5848 6.5994 14.4735 6.77576C14.409 6.8778 14.2039 7.04024 14.112 7.13349C13.9916 7.25539 13.8712 7.37769 13.7508 7.49986L2.65017 7.5Z" fill="#002239"/>
                </svg>
            </a>

        </div>

    </div>

</section>

<script>
(function () {
    var video = document.querySelector('[data-about-bg-video]');
    if (!video) return;

    function playVideo() {
        video.muted = true;
        video.defaultMuted = true;
        video.loop = true;
        video.setAttribute('playsinline', '');
        var promise = video.play();
        if (promise && typeof promise.catch === 'function') {
            promise.catch(function () {});
        }
    }

    playVideo();
    ['loadedmetadata', 'loadeddata', 'canplay', 'canplaythrough'].forEach(function (eventName) {
        video.addEventListener(eventName, playVideo);
    });
    video.addEventListener('ended', playVideo);
    video.addEventListener('stalled', playVideo);
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) playVideo();
    });

    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) playVideo();
            });
        }, { threshold: 0.1 });
        observer.observe(video);
    }

    var retryCount = 0;
    var retryTimer = setInterval(function () {
        if (video.paused && !video.ended && retryCount < 20) {
            playVideo();
            retryCount++;
            return;
        }
        clearInterval(retryTimer);
    }, 500);
})();
</script>

<?php get_template_part( 'template-parts/shared/cta' ); ?>

<?php
// --- template-parts/home/support.php ---
/**
 * トップページ: SUPPORTセクション（お問い合わせ）
 */
$contact_page = get_page_by_path( 'contact' );
$contact_url  = $contact_page ? get_permalink( $contact_page->ID ) : home_url( '/contact/' );
?>

<section id="support" class="support-section">
    <div class="support-inner">

        <!-- タイトル -->
        <div class="section-deco-title" aria-label="お問い合わせ SUPPORT">
            <div class="section-deco-title__ja">お問い合わせ</div>
            <div class="section-deco-title__en">SUPPORT</div>
        </div>

        <!-- テキスト + ボタン -->
        <div class="support-row">
            <p class="support-desc">
                本サービスに関するご相談やご不明点がございましたら、お問い合わせフォームよりご連絡ください。
            </p>
            <a href="<?php echo esc_url( $contact_url ); ?>" class="support-btn">
                <span>CONTACT FORM</span>
                <svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                    <rect width="18" height="12" fill="#4AEB38"/>
                    <path d="M2.65017 7.5C2.58453 7.5 2.5177 7.38337 2.51166 7.31701C2.47437 6.90131 2.54107 6.42722 2.51192 6.00517C2.50496 5.94962 2.59227 5.86354 2.63691 5.86354H11.7423L11.7297 5.8157L10.2002 3.84921C10.1112 3.68799 10.1722 3.52324 10.3587 3.5L11.3888 3.50432C11.4283 3.51406 11.5754 3.65988 11.6181 3.70082C12.493 4.54075 13.3526 5.41797 14.2046 6.28208C14.3165 6.39547 14.5848 6.5994 14.4735 6.77576C14.409 6.8778 14.2039 7.04024 14.112 7.13349C13.9916 7.25539 13.8712 7.37769 13.7508 7.49986L2.65017 7.5Z" fill="#002239"/>
                </svg>
            </a>
        </div>

    </div>
</section>

<?php
// --- template-parts/home/banners.php ---
/**
 * トップページ: 協賛バナーセクション
 * SUPPORT とフッターの間
 */

$img_base = get_template_directory_uri() . '/assets/images/banners';

$banners = array(
    array(
        'file' => 'ceremore.png',
        'alt'  => 'セレモアの家族葬 セレモアパック葬',
        'url'  => 'https://www.ceremore.co.jp/search/gkaikan_akishima.php',
    ),
    array(
        'file' => 'asahi-ceremony.png',
        'alt'  => '株式会社あさひセレモニー',
        'url'  => 'http://www.asahi-ceremony.co.jp/',
    ),
    array(
        'file' => 'sohshin.png',
        'alt'  => '葬儀のそうしん そうしんホール昭島',
        'url'  => 'https://www.sohshin.co.jp/akishima/',
    ),
    array(
        'file' => 'kawabe-printing.png',
        'alt'  => '河辺印刷株式会社',
        'url'  => 'https://kawabe-p.co.jp/',
    ),
    array(
        'file' => 'clover.png',
        'alt'  => '有限会社クローバー クローバー ジャズポップコーン',
        'url'  => '',
    ),
    array(
        'file' => 'akishima-gas.png',
        'alt'  => '昭島ガス株式会社',
        'url'  => 'http://www.akishimagas.co.jp/',
    ),
    array(
        'file' => 'dreamy.png',
        'alt'  => '株式会社ドリーミー',
        'url'  => '',
    ),
);
?>

<section id="sponsor-banners" class="sponsor-banners" aria-label="協賛バナー">
    <div class="sponsor-banners__inner">
        <ul class="sponsor-banners__grid">
            <?php foreach ( $banners as $banner ) : ?>
                <?php
                $src = $img_base . '/' . $banner['file'];
                $alt = $banner['alt'];
                $url = ! empty( $banner['url'] ) ? $banner['url'] : '';
                ?>
                <li class="sponsor-banners__item">
                    <?php if ( $url ) : ?>
                        <a
                            class="sponsor-banners__link"
                            href="<?php echo esc_url( $url ); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <img
                                class="sponsor-banners__img"
                                src="<?php echo esc_url( $src ); ?>"
                                alt="<?php echo esc_attr( $alt ); ?>"
                                loading="lazy"
                                decoding="async"
                            >
                        </a>
                    <?php else : ?>
                        <span class="sponsor-banners__link sponsor-banners__link--static">
                            <img
                                class="sponsor-banners__img"
                                src="<?php echo esc_url( $src ); ?>"
                                alt="<?php echo esc_attr( $alt ); ?>"
                                loading="lazy"
                                decoding="async"
                            >
                        </span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
