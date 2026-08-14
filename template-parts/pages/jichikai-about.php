<?php
/**
 * 自治会とはページ本文
 * 画像: assets/images/jichikai/
 * 下部CTA: page 側 shared/cta
 * 入口: page-jichikai-about.php
 */

/**
 * 自治会とはページ ヒーローセクション
 */
get_template_part(
    'template-parts/shared/page-hero',
    null,
    array(
        'title_en'       => 'JICHIKAI',
        'title_ja'       => '自治会とは',
        'image'          => 'assets/images/jichikai/jichikai-hero.png',
        'image_position' => 'center 30%',
    )
);
// --- template-parts/jichikai/content-section.php ---
/**
 * 自治会とはページ コンテンツセクション
 */
$img_dir        = get_template_directory_uri() . '/assets/images/jichikai/';
$material_base  = 'https://www.akishima-jichiren.jp/material/';
?>
<section class="jichikai-content">
    <div class="jichikai-content__inner">

        <!-- 昭島市長からのメッセージ -->
        <div class="jichikai-message">
            <div class="jichikai-message__text-col">
                <h2 class="jichikai-section-heading">
                    <span class="jichikai-section-heading__bar" aria-hidden="true"></span>
                    昭島市長からのメッセージ
                </h2>
                <div class="jichikai-message__body">
                    <p>人口減少・少子高齢化時代を迎え、社会を取り巻く環境は大きく様変わりをしています。こうした中で、住みよいまちづくりに向けた活動には、行政のみならず、地域の力が必要不可欠です。<br>
                    人と人との集まりやつながりを基本とする自治会活動は、地域活動の中心であり、日々の暮らしの中で、自治会の役割は重要であると存じます。<br>
                    「住んでみたい、住み続けたいまち昭島」の実現に向けて、多くの皆様が、地域のつながりの「源」であります自治会に加入していただければ幸いです。</p>
                    <p class="jichikai-message__signature">昭島市長　　臼井　伸介</p>
                </div>
            </div>
            <div class="jichikai-message__photo-col">
                <img
                    src="<?php echo esc_url( $img_dir . 'mayor-photo.png' ); ?>"
                    alt="昭島市長 臼井伸介"
                    class="jichikai-message__photo"
                >
            </div>
        </div>

        <!-- パンフレット -->
        <div class="jichikai-pamphlet">
            <p class="jichikai-pamphlet__lead">
                <a href="<?php echo esc_url( $material_base . 'PR.pdf' ); ?>" target="_blank" rel="noopener noreferrer">▼パンフレットはこちら</a>
            </p>
            <div class="jichikai-pamphlet__img-wrap">
                <a href="<?php echo esc_url( $material_base . 'PR.pdf' ); ?>" target="_blank" rel="noopener noreferrer">
                    <img
                        src="<?php echo esc_url( $img_dir . 'pamphlet.png' ); ?>"
                        alt="自治会加入パンフレット"
                        class="jichikai-pamphlet__img"
                    >
                </a>
            </div>
        </div>

        <!-- ごきんじょカード -->
        <div class="jichikai-card">
            <p class="jichikai-card__lead">平成２７年４月から、昭島市内の自治会加入世帯の皆様に会員証「ごきんじょカード」を、お届けいたしました。<br>
            協力店を利用した際、カードを提示するとサービスが受けれます。</p>
            <div class="jichikai-card__img-wrap">
                <img
                    src="<?php echo esc_url( $img_dir . 'gokinjyo-card.png' ); ?>"
                    alt="ごきんじょカード"
                    class="jichikai-card__img"
                >
            </div>
            <ul class="jichikai-card__links">
                <li><a href="https://docs.google.com/spreadsheets/d/13leAW8_43c3tTpabyqB81GoTeW_B4gyuEgWnZZcQKq4/edit?usp=sharing" target="_blank" rel="noopener noreferrer">協力店一覧</a></li>
            </ul>
        </div>

        <!-- 自治会の目的 -->
        <div class="jichikai-info-block">
            <div class="jichikai-info-block__header">
                <h3 class="jichikai-info-block__title">自治会の目的</h3>
            </div>
            <div class="jichikai-info-block__body">
                <ol class="jichikai-info-block__list">
                    <li>地域コミュニティの形成</li>
                    <li>自らのまちは自らの手で守る環境づくり</li>
                    <li>相互扶助の実践</li>
                </ol>
            </div>
        </div>

        <!-- 自治会の主な活動 -->
        <div class="jichikai-info-block">
            <div class="jichikai-info-block__header">
                <h3 class="jichikai-info-block__title">自治会の主な活動</h3>
            </div>
            <div class="jichikai-info-block__body">
                <ol class="jichikai-info-block__list">
                    <li>地域内と昭島市の情報の提供</li>
                    <li>地域要望の取りまとめ</li>
                    <li>防犯・交通安全への取組み</li>
                    <li>自主防災活動</li>
                    <li>環境整備</li>
                    <li>地域コミュニティ活動</li>
                    <li>地域福祉活動など</li>
                </ol>
            </div>
        </div>

        <!-- 締めの文 -->
        <p class="jichikai-closing-text">
            近年、住民の価値観の多様化や核家族化・共働き世帯の増加により「向こう三軒両隣」といわれるような、ご近所付き合いが薄れつつあります。<br>
            一人暮らし高齢者の増加による孤独死や児童虐待など悲しい事件が相次ぐ中、地域における"人と人とのつながり(絆づくり)"が見直されてきています。
        </p>

    </div>
</section>

<?php
// --- template-parts/jichikai/join-section.php ---
/**
 * 自治会とはページ: 加入案内セクション
 */
?>
<section class="jichikai-join">
    <div class="jichikai-join__inner">
        <div class="jichikai-join__card">
            <div class="jichikai-join__body">
                <p>
                    ◎新しく引っ越してこられたみなさん！<br>
                    ◎マンション＆集合住宅にお住まいのみなさん！<br>
                    ◎自治会活動に参加する機会のなかったみなさん！
                </p>
                <p>
                    この機会に自治会に加入してまちづくりに参加してみませんか？<br>
                    地域の自治会がわからない方は、下記に連絡ください。
                </p>
                <p>〈事務局〉昭島市役所／市民部生活コミュニティ課内 <a href="tel:0425445111">042-544-5111</a></p>
            </div>
        </div>
    </div>
</section>

<?php
// --- template-parts/jichikai/review-section.php ---
/**
 * 自治会とはページ: 自治会が見直されています セクション
 */
$img_dir = get_template_directory_uri() . '/assets/images/jichikai/';
?>
<section class="jichikai-review">
    <div class="jichikai-review__inner">

        <div class="jichikai-review__intro">
            <h2 class="jichikai-section-heading">
                <span class="jichikai-section-heading__bar" aria-hidden="true"></span>
                自治会が見直されています！
            </h2>
            <div class="jichikai-review__text">
                <p>平成7年1月17日に発生した阪神･淡路大震災、平成２３年３月11日の東日本大震災、子供を狙った凶悪な犯罪など度重なる痛ましい事件、孤独死・孤立死が周辺でも起こっています。</p>
                <p>いま地域社会の核となる"自治会"の役割が見直されています。</p>
                <p>災害や犯罪が起きた際、自治会活動が活発で地域のつながり、助け合いが日常的にしっかり連携が取れている地域とそうでない地域では、大きな差がおこり、阪神･淡路大震災の時はマスコミが大きく自治会の活躍を取り上げました。</p>
                <p>本サイトのテーマでもありますが"地域力アップで／安全安心のまちつくり／住みよいまちをみんなでつくろう！"　いざという時のためにも顔が見える人間関係をつくることが大切です。</p>
                <p>日常的に地域のつながりや交流がある温かい地域社会を、自治会は目指しています。</p>
            </div>
            <figure class="jichikai-review__figure jichikai-review__figure--photo">
                <img
                    src="<?php echo esc_url( $img_dir . 'review-photo-1.png' ); ?>"
                    alt="地域の公園で交流する様子"
                    class="jichikai-review__img"
                    loading="lazy"
                >
            </figure>
        </div>

        <div class="jichikai-review__docs">
            <div class="jichikai-review__doc-group">
                <p class="jichikai-review__doc-title">◆加入促進資料</p>
                <ul class="jichikai-review__doc-list">
                    <li>入会勧誘（Word）</li>
                    <li>会員特典サービスの依頼文</li>
                    <li>2019年作成自治会魅力アピール資料</li>
                </ul>
            </div>
            <div class="jichikai-review__doc-group">
                <p class="jichikai-review__doc-title">◆提出書類様式</p>
                <ul class="jichikai-review__doc-list">
                    <li>自治会加入届（Word）</li>
                    <li>会員特典サービス申込書（Word）</li>
                </ul>
            </div>
            <div class="jichikai-review__doc-group">
                <p class="jichikai-review__doc-title">◆自治会運営資料</p>
                <ul class="jichikai-review__doc-list">
                    <li>自治会運営マニュアルver.4（2018年12月改定）</li>
                    <li>自治会運営ハンドブック（2012年11月・第2次改訂版）</li>
                </ul>
            </div>
        </div>

    </div>
</section>
