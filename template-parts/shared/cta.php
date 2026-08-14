<?php
/**
 * 共通: 加入申し込み CTA（ホーム / ABOUT / 自治会とは で利用）
 * 自治会への加入申し込みバナー
 *
 * ボタンリンクを変更する場合は $cta_url を編集してください。
 */
$img_dir = get_template_directory_uri() . '/assets/images/cta/';

// 申し込みページURL（昭島市公式サイトなど）
$cta_url = get_theme_mod( 'akishima_application_url', 'https://www.city.akishima.lg.jp/kurashi/sumai/1001777/1001781.html' );
?>

<section id="application" class="application-section">
    <a
        href="<?php echo esc_url( $cta_url ); ?>"
        class="application-card"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="自治会への加入申し込み（昭島市公式サイトへ）"
    >

        <!-- 背景: PC=img1 / SP=img_sp -->
        <div class="application-bg" aria-hidden="true">
            <picture>
                <source media="(max-width: 768px)" srcset="<?php echo esc_url( $img_dir . 'img_sp.png' ); ?>">
                <img src="<?php echo esc_url( $img_dir . 'img1.png' ); ?>" alt="" loading="lazy">
            </picture>
        </div>

        <!-- オーバーレイ + コンテンツ -->
        <div class="application-overlay">
            <div class="application-content">

                <div class="application-heading">
                    <p class="application-heading-ja">自治会への加入申し込み</p>
                    <p class="application-heading-en">APPLICATION</p>
                </div>

                <p class="application-desc">
                    自治会への加入につきましては、<br>
                    昭島市の公式ホームページから<br class="application-desc__br" aria-hidden="true">お申し込みいただけます。
                </p>

                <span class="application-btn" aria-hidden="true">
                    <svg width="54" height="54" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
                        <rect width="54" height="54" fill="#F7F7F7"/>
                        <path d="M15.9911 37.1424C15.8489 37.2244 15.5584 37.0555 15.4624 36.9194C14.8621 36.0659 14.4139 34.9561 13.8232 34.0788C13.7387 33.9672 13.8201 33.6717 13.9168 33.6159L33.6304 22.2342L33.5434 22.1464L27.7739 19.8007C27.3796 19.5629 27.3059 19.1299 27.6805 18.8465L29.9162 17.5682C30.0139 17.5398 30.5146 17.6717 30.6581 17.7071C33.6024 18.4319 36.56 19.2566 39.4847 20.0625C39.8686 20.1681 40.7046 20.2742 40.684 20.7952C40.672 21.0967 40.431 21.7048 40.3486 22.0215C40.2403 22.436 40.1325 22.8513 40.0245 23.2663L15.9911 37.1424Z" fill="#013458"/>
                    </svg>
                </span>

            </div>
        </div>

    </a>
</section>
