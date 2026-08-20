<?php
/**
 * ABOUTページ本文（ヒーロー〜構成図まで1ファイル）
 *
 * 運用メモ:
 * - 会長挨拶フォールバック / 写真: 本ファイル内 MESSAGE
 * - 自治連とは文・写真: about-overview
 * - 構成図・規約: about-chart
 * - 下部CTA: page-about.php で shared/cta
 * 入口: page-about.php
 */

/**
 * ABOUTページ ヒーローセクション
 */
get_template_part(
    'template-parts/shared/page-hero',
    null,
    array(
        'title_en'         => 'ABOUT',
        'title_ja'         => '自治連について',
        'image'            => 'assets/images/about-page-hero.jpg',
        'image_position'   => 'center 40%',
    )
);

while ( have_posts() ) : the_post(); ?>
<?php
/**
 * ABOUTページ: 会長挨拶（MESSAGE）セクション
 * - 本文テキスト: WordPress エディタ（the_content）
 * - 会長写真: アイキャッチ画像
 */
?>
<section class="about-message">

    <!-- 背景デコレーション画像（半透明） -->
    <div class="about-message__bg-deco" aria-hidden="true">
        <img
            src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/about-message-bg-deco.png' ); ?>"
            alt=""
            class="about-message__bg-img"
            loading="lazy"
        >
    </div>

    <div class="about-message__inner">

        <div class="about-message__layout">

            <!-- 左カラム: タイトル + 本文 -->
            <div class="about-message__left">

                <!-- タイトルブロック -->
                <div class="section-deco-title about-message__title-block" aria-label="会長挨拶 MESSAGE">
                    <p class="section-deco-title__ja">会長挨拶</p>
                    <p class="section-deco-title__en">MESSAGE</p>
                </div>

                <!-- 本文 -->
                <div class="about-message__body">
                    <?php if ( get_post()->post_content ) : ?>
                        <?php the_content(); ?>
                    <?php else : ?>
                        <p>　どうぞよろしくお願いいたします。私は１２ブロック長を兼任しております。１２ブロックとは堀向地区と言われる地域です。小学校で言いますと拝島第二小学校地区となります。</p>
                        <p>　各自治会、役員不足、会員の減少と様々な問題もあるとは思います。</p>
                        <p>先ずは、我々役員、そして会員の皆様が楽しめる会にしていきましょう。楽しくなければ、仲間を増やしていく事も難しくなります。自治会連合会に於いても、会員様向けの楽しめる行事も計画しています。多くの皆様の参加をお待ちしております。</p>
                        <p>　またこの度ホームページをリニューアルいたしました。少しでも皆様のコミュニケーションツールとしてご活用していただければと思います。</p>
                        <p>　皆様と共に、安心安全な地域で笑顔が溢れるまちづくりを目指していきましょう。</p>
                        <div class="about-message__signature">
                            <p>東京都昭島市自治会連合会</p>
                            <p>会長　　高橋　靖和</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div><!-- /.about-message__left -->

            <!-- 右カラム: 会長写真 -->
            <div class="about-message__right">
                <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'large', array(
                        'class'   => 'about-message__photo',
                        'alt'     => '会長写真',
                        'loading' => 'lazy',
                    ) ); ?>
                <?php else : ?>
                    <img
                        src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/about-chairman.png' ); ?>"
                        alt="会長 高橋靖和"
                        class="about-message__photo"
                        loading="lazy"
                    >
                <?php endif; ?>
            </div><!-- /.about-message__right -->

        </div><!-- /.about-message__layout -->

    </div><!-- /.about-message__inner -->

</section>

<?php endwhile; ?>
<?php
/**
 * Template Part: ABOUTページ - 自治連とは セクション
 */
$img_base    = get_template_directory_uri() . '/assets/images/about';
$library_page = get_page_by_path( 'library' );
$library_url  = $library_page ? get_permalink( $library_page ) : home_url( '/library/' );
?>
<section class="about-overview">
    <div class="about-overview__inner">

        <!-- タイトルブロック（Figma 29:2503 / MESSAGEと同型） -->
        <div class="section-deco-title about-overview__title" aria-label="自治連とは ABOUT">
            <p class="section-deco-title__ja">自治連とは</p>
            <p class="section-deco-title__en">ABOUT</p>
        </div>

        <!-- 本文 -->
        <div class="about-overview__body">
            <p>昭島市自治会連合会(以下、「自治連」という。)は、1959年(昭和34年)5月に設立された昭島市自治会連絡協議会を前身とし、<br>
            1971年(昭和46年)に組織名を変更し現在に至っております。</p>
            <p>自治連は、現在約90の単一自治会で構成されており、その活動、運用を円滑に行うため２０のブロックに分けて組織しています。</p>
            <p>自治連の組織は、常任委員会を最高執行機関に位置付け、そのもとに三役会、事務局、運営に携わる常設の4つの委員会(総務委員会、事業委員会、研修・防災委員会、広報委員会)、必要に応じ設置する専門委員会を設けております。</p>
        </div>

        <!-- 写真: PC=3枚 / SP=about01_sp -->
        <div class="about-overview__photos">
            <img src="<?php echo esc_url( $img_base . '/about-photo1.png' ); ?>" alt="" class="about-overview__photo-img about-overview__photo-img--pc" loading="lazy" width="773" height="620" aria-hidden="true">
            <img src="<?php echo esc_url( $img_base . '/about-photo2.png' ); ?>" alt="" class="about-overview__photo-img about-overview__photo-img--pc" loading="lazy" width="773" height="620" aria-hidden="true">
            <img src="<?php echo esc_url( $img_base . '/about-photo3.png' ); ?>" alt="" class="about-overview__photo-img about-overview__photo-img--pc" loading="lazy" width="773" height="620" aria-hidden="true">
            <img src="<?php echo esc_url( $img_base . '/about01_sp.png' ); ?>" alt="自治連の活動写真" class="about-overview__photo-img about-overview__photo-img--sp" loading="lazy" width="695" height="1056">
        </div>

    </div><!-- /.about-overview__inner -->

    <!-- 自治連についてもっと知る バナー（全体リンク） -->
    <a
        href="<?php echo esc_url( $library_url ); ?>"
        class="about-overview__readmore"
        aria-label="自治連についてもっと知る。各自治連広報・その他資料はこちら"
    >
        <div class="about-overview__readmore-bg" aria-hidden="true">
            <img src="<?php echo esc_url( $img_base . '/about-readmore-bg.jpg' ); ?>" alt="">
        </div>
        <div class="about-overview__readmore-overlay" aria-hidden="true"></div>
        <div class="about-overview__readmore-content">
            <div class="about-overview__readmore-lead">
                <!-- 緑ピクセルアイコン（Figma 29:2524 / 3×3） -->
                <div class="about-overview__readmore-icon" aria-hidden="true">
                    <span style="background:#4aeb38;"></span>
                    <span style="background:rgba(138,247,126,0.8);"></span>
                    <span style="background:rgba(183,250,175,0.6);"></span>
                    <span style="background:rgba(138,247,126,0.8);"></span>
                    <span style="background:rgba(183,250,175,0.6);"></span>
                    <span style="background:rgba(227,253,224,0.4);"></span>
                    <span style="background:rgba(183,250,175,0.6);"></span>
                    <span style="background:rgba(227,253,224,0.4);"></span>
                    <span style="background:rgba(242,254,241,0.2);"></span>
                </div>
                <p class="about-overview__readmore-text">自治連についてもっと知る</p>
            </div>
            <span class="about-overview__readmore-btn" aria-hidden="true">
                <span>各自治連広報・その他資料はこちら</span>
                <?php get_template_part( 'template-parts/shared/action-btn-arrow' ); ?>
            </span>
        </div>
    </a><!-- /.about-overview__readmore -->

</section><!-- /.about-overview -->
<?php
/**
 * Template Part: ABOUTページ - 自治会連合会構成図 ＋ 自治会連合規約 セクション
 */
$img_base = get_template_directory_uri() . '/assets/images/about';
?>
<section class="about-chart">

    <!-- 背景デコレーション（Figma 29:2473 / opacity 50%） -->
    <div class="about-chart__bg-deco" aria-hidden="true">
        <img
            src="<?php echo esc_url( $img_base . '/about-chart-bg-deco.png' ); ?>"
            alt=""
            class="about-chart__bg-img"
            loading="lazy"
        >
    </div>

    <div class="about-chart__inner">

        <!-- CHART タイトルブロック（Figma 29:2475 / MESSAGE・ABOUTと同型） -->
        <div class="section-deco-title about-chart__title" aria-label="自治会連合会構成図 CHART">
            <p class="section-deco-title__ja">自治会連合会構成図</p>
            <p class="section-deco-title__en">CHART</p>
        </div>

        <!-- 構成図画像 ＋ 規約ブロック -->
        <div class="about-chart__container">

            <!-- 構成図画像 -->
            <div class="about-chart__image-wrap">
                <img src="<?php echo esc_url( $img_base . '/about-chart.png' ); ?>" alt="昭島市自治会連合会・全体構成運営図" class="about-chart__image">
            </div>

            <!-- 昭島市自治会連合規約 -->
            <div class="about-charter">

                <!-- タイトル行 -->
                <div class="about-charter__title-row">
                    <!-- ネイビーのピクセルアイコン（3×3） -->
                    <div class="about-charter__icon" aria-hidden="true">
                        <span style="background:#002239;"></span>
                        <span style="background:rgba(64,96,111,0.8);"></span>
                        <span style="background:rgba(128,157,170,0.6);"></span>
                        <span style="background:rgba(64,96,111,0.8);"></span>
                        <span style="background:rgba(128,157,170,0.6);"></span>
                        <span style="background:rgba(217,227,232,0.4);"></span>
                        <span style="background:rgba(128,157,170,0.6);"></span>
                        <span style="background:rgba(217,227,232,0.4);"></span>
                        <span style="background:rgba(228,232,234,0.2);"></span>
                    </div>
                    <h2 class="about-charter__title">昭島市自治会連合規約</h2>
                </div>

                <!-- スクロール可能テキストエリア（本文: template-parts/about/charter-scroll-content.php） -->
                <div class="about-charter__box">
                    <div class="about-charter__scroll">
                        <?php /* 規約本文（旧 charter-scroll-content.php をインライン） */ ?>

<div class="about-charter__block">
    <p><strong>（名称および事務所）</strong><br>
    第１条　本会は昭島市自治会連合会（以下「自治連」という。）と称し、事務所を昭島市役所内に置く。</p>
    <p><strong>（組　織）</strong><br>
    第２条　本会は昭島市内で組織されている各単一自治会（以下「自治会」という）をもって組織する。<br>
    ２　本会の運営を円滑にするため、前項の自治会を区分してブロックを組織する。</p>
    <p><strong>（目的）</strong><br>
    第３条　本会は、元気で活力のある地域づくりを行政と協働していくため、自治会相互の連携と親睦を図り、共通の問題を協議し、行政に協力すると共に、市民自治意識の高揚と地域社会の発展に寄与することを目的とする。</p>
    <p><strong>（事　業）</strong><br>
    第４条　本会は前条の目的を達成するため、次の事業を行う。<br>
    (1) 本会主催行事の開催に関すること<br>
    (2) 自治会活動に資する研修会等の開催に関すること<br>
    (3) 自主防災など市民の生活安全に関すること<br>
    (4) 本会機関紙の編集、発行に関すること<br>
    (5) 市行政についての周知及び協力に関すること<br>
    (6) 地域社会の発展育成に関する調査研究<br>
    (7) 同一目的を有する団体との協力連携に関すること<br>
    (8) その他本会目的の達成に必要な事項に関すること</p>
    <p><strong>（会　員）</strong><br>
    第５条　本会の会員は、本会に加入の書面(第１号様式)を会長に届け、常任委員会で受理された自治会をもって会員とする。<br>
    ２　本会を退会するときは、書面(第２号様式)をもって会長に提出し、常任委員会への報告後、退会となる。</p>
    <p><strong>（役　員）</strong><br>
    第６条　本会に次の役員を置く<br>
    (1) 常任委員<br>
    　(イ) 会　長　　１名<br>
    　(ロ) 副会長　　３名<br>
    　(ハ) 会　計　　１名<br>
    　(ニ) 上記以外の常任委員<br>
    (2) 会計監査　　２名<br>
    (3) 顧　問　　若干名</p>
    <p><strong>（役員の選出）</strong><br>
    第７条　役員の選出は次の方法による。<br>
    (1) 会長、副会長、会計は、常任委員から選出し、総会の承認を得る。<br>
    (2) 会長、副会長、会計の選出は、別に定める昭島市自治会連合会活動基準（以下、「活動基準」という。）による役員選挙運営委員会を設置し、その委員会の運営で選出する。<br>
    (3) 常任委員は、別表１に定める各ブロックにおいて選出されたブロック長をもってこれにあてる。<br>
    (4) 会計監査は、三役会で推選し常任委員会に諮り、総会の承認を受ける。</p>
</div>

<div class="about-charter__block">
    <p>(5) 顧問を、本会に置くことができる。三役会で推選し、常任委員会に諮り会長が委嘱し、総会に報告する。資格は、会長、副会長経験者とする。<br>
    (6) 役員に欠員が生じた場合は、後任者を選任する。</p>
    <p><strong>（役員の職務）</strong><br>
    第８条　役員の職務は次のとおりとする。<br>
    (1) 会長は、本会を代表し会務を統括する。<br>
    (2) 副会長は、会長を補佐し会長事故あるときはその職務を代行する。<br>
    (3) 会計は、本会の経理を担当する。<br>
    (4) 常任委員は、ブロックの代表として常任委員会に出席し、ブロック内の自治会と連合会並びに、市との調整役を行なう。<br>
    (5) 会計監査は、本会の経理を監査しその結果を常任委員会並びに総会に報告する。<br>
    (6) 顧問は、三役会及び常任委員会の要請に応じ、会議に出席し、本会の健全な運営のために、意見を述べることができる。</p>
    <p><strong>（役員の任期）</strong><br>
    第９条　役員の任期は次のとおりとする。<br>
    (1) 会長は１期２年とし、２期４年を限度とする。<br>
    (2) 会長以外の役員の任期は１年とする。ただし、再任を妨げない。補充の後任役員は前任者の残任期間とする。</p>
    <p><strong>（会議の種類）</strong><br>
    第１０条　本会に次の会議を置く。<br>
    (1) 総会　　　　(4) 委員会<br>
    (2) 三役会　　　(5) 専門委員会<br>
    (3) 常任委員会</p>
    <p><strong>（総会）</strong><br>
    第１１条　総会は、本会の最高決議機関であって、定期総会および臨時総会とし、ブロック長、自治会長全員をもって構成する。<br>
    ２　定期総会は、毎年１回５月にこれを開き、臨時総会は会長が必要と認めたとき又はブロック長、自治会長の３分の２以上の請求があったとき開催する。<br>
    ３　総会の議長は、当日出席者の中から選出する。</p>
    <p><strong>（総会の審議事項）</strong><br>
    第１２条　総会は次の事項を審議する。<br>
    (1) 規約の改廃に関すること<br>
    (2) 事業および決算報告に関すること<br>
    (3) 新年度の事業計画および予算に関すること<br>
    (4) 役員の承認に関すること<br>
    (5) その他、本会の重要事項に関すること</p>
    <p><strong>（三役会）</strong><br>
    第１３条　三役会は、本会を執行するために、会長が招集し開催する。会長、副会長、会計で組織するが、会の執行上、必要に応じて、会長が指名する常任委員を参加させることができる。</p>
    <p><strong>（常任委員会）</strong><br>
    第１４条　常任委員会は、常任委員全員をもって構成し随時会長が招集する。但し、常任委員の３分の１以上の要求があった時は、速やかに常任委員会を開催しなければならない。</p>
</div>

<div class="about-charter__block">
    <p><strong>（機関の成立と議事の決定）</strong><br>
    第１５条　総会および常任委員会は、委任状を含め構成員の２分の１以上の者が出席しなければ成立しない。<br>
    ２　議事は出席者の過半数で決し、可否同数の場合は議長が決する。</p>
    <p><strong>（委員会）</strong><br>
    第１６条　委員会は、年間を通して本会の事業を推進するために会長が委員会を設けることができる。委員は常任委員が兼ねる。</p>
    <p><strong>（専門委員会）</strong><br>
    第１７条　常任委員会は、本会の事業を審議・執行するための専門委員会を設置することができる。</p>
    <p><strong>（会　計）</strong><br>
    第１８条　本会の経費は、市よりの補助金及び、行事の際の参加費、寄付金及びその他の収入をもってこれにあてる。<br>
    ２　本会の会計年度は、毎年４月１日から翌年３月３１日までとする。</p>
    <p><strong>（規約の改廃）</strong><br>
    第１９条　この規約を改廃しようとするときは、総会において構成員の過半数の賛成を必要とする。<br>
    第２０条　本会の運営に必要な活動の基準や、表彰に関する規定として、昭島市自治会連合会活動基準を定める。この活動基準の改廃は常任委員会で決定する。</p>
    <p><strong>（委任事項）</strong><br>
    第２１条　この会則に定めるもののほか、本会の事業及び運営について必要な事項は、常任委員会において定める。</p>
    <p><strong>附則</strong><br>
    昭和４６年３月３１日　施行<br>
    平成２２年５月２８日　全部改正</p>
    <p class="about-charter__doc-title"><strong>昭島市自治会連合会活動基準</strong></p>
    <p><strong>（目　的）</strong><br>
    第１条　この基準は、昭島市自治会連合会規約第４条の規程に定める事業を円滑に行うために、昭島市自治会連合会の活動について必要な事項を定めるものとする。</p>
    <p><strong>（事務局）</strong><br>
    第２条　事務局は、昭島市市民生活コミュニティ課に担当していただき、円滑な運営をする。</p>
    <p><strong>（自治連加入届と退会届）</strong><br>
    第３条　本会に新たに加入を求める自治会は書面(第１号様式)をもって次に掲げる事項を会長に提出し、常任委員会の承認後、本会の会員となる。<br>
    ２　記載事項は、①自治会の名称、②自治会の区域図、③班数、④加入世帯数、⑤代表者及び役員の氏名、住所、連絡先、⑥自治会規約とする。<br>
    ３　諸事情で本会を退会することになった自治会は、退会届(第２号様式)を会長に提出し、常任委員会への報告後、退会とする。</p>
</div>

<div class="about-charter__block">
    <p><strong>（自治連組織ブロックと地域割り）</strong><br>
    第４条　各単位自治会を区分けしてブロックを組織し、連合会規約の別表１に定め、更に自治連を５分割し行政との協議単位とする。別表２にブロックの地域割りを定める。</p>
    <p><strong>（自治連としての事業の取り組み内容）</strong><br>
    第５条　自治連として、次の事業に取り組み、各委員会で分担し執行する。<br>
    （１）本会主催行事の開催に関すること<br>
    （２）自治会活動に資する研修会等の開催に関すること<br>
    （３）自主防災など市民の生活安全に関すること<br>
    　・自主防災を始め、地域防犯や、交通安全等を関係団体と連携をとり、各種の地域問題を生活安全第一で、地域力の向上に取り組む<br>
    （４）本会機関紙の編集、発行に関すること<br>
    　・原則１月に「自治連広報」と「自治連だより」を随時発行する<br>
    　・自治連ホームページの管理運営と随時、活動内容の投稿<br>
    （５）市行政についての周知及び協力に関すること<br>
    （６）地域社会の発展育成に関する調査研究<br>
    　・地域の伝統文化や、新しい地域発展に関しての各種の発展育成に関することを各自治会と連携をとり取り組む<br>
    （７）同一目的を有する団体との協力連携に関すること<br>
    （８）その他本会目的の達成に必要な事項に関すること<br>
    それ以外に、新規加入自治会の促進に関することや、自治会会員増強に関することにも具体的に取り組む。</p>
    <p><strong>（委員会組織及び事業内容）</strong><br>
    第６条　本会として運営上、年間を通して必要な委員会を常任委員で組織する。<br>
    ２　委員会の設置と委員長案を、会長は三役会に諮り、常任委員会の承認を得る。<br>
    ３　事業内容は、事業計画策定委員会にて協議し、常任委員会の承認を得る。</p>
    <p><strong>（専門委員会の設置）</strong><br>
    第７条　常任委員会は必要に応じて、専門委員会として役員選挙運営委員会、事業計画策定委員会を設置することができる。また、本会の事業を円滑に行うため、常任委員会の議を経て、新たな専門委員会を設置することができる。ただし、その目的の達成をもって解散する。</p>
    <p><strong>（役員選挙運営委員会）</strong><br>
    第８条　役員選挙運営委員会は、事務局の協力を得て総務委員会が担当する。<br>
    ２　会長、副会長、会計、三役の選出に関する事項を処理する。<br>
    ３　選挙権は常任委員が有し、被選挙権は常任委員を２年以上経験し、次年度継続する常任委員が有する。<br>
    ４　選出方法は、常任委員全員で、会長、副会長、会計の順で個別に選挙を行う。副会長は推薦者を２名連記とする。会長が２年目の際は、副会長と会計の選挙を行う。<br>
    ５　会長は１名、副会長は３名、会計は１名選び、同数の場合は該当者のみで決選投票とする。但、当選にあたっては常任委員の３分の１以上の獲得投票数を必要とする。</p>
</div>

<div class="about-charter__block">
    <p>６　役員の年齢制限は、特にこれを設けないものとする。</p>
    <p><strong>（事業計画策定委員会）</strong><br>
    第９条　事業計画策定委員会は、三役と会長が常任委員から若干名、委員に指名し招集することができる。<br>
    ２　委員長は、会長が務めるものとする。<br>
    ３　年度事業計画策定に関する事項と、年度予算策定に関する事項を処理する。</p>
    <p><strong>（表彰規定）</strong><br>
    第１０条　本会の会員で次の条項に該当するものは、常任委員会の決定に基づき表彰する。<br>
    (1) 本会の運営に献身的な努力をしたもの<br>
    (2) その他常任委員会で必要と認めたもの<br>
    (3) 単一自治会で、通算し7年以上となる一般役員のもの<br>
    ２　次の条項に該当するものは、市の表彰規定により表彰されるため、本会として市に対して申請するものとする<br>
    (1) 単一自治会の会長、副会長、会計の職にある期間を通算した場合における職務期間が5年以上となるもの<br>
    (2) 前項は、5年ごとにその対象者となるもの<br>
    第１１条　本規程により表彰が要すると認められる者があるときは、表彰推薦書を推薦者（各常任委員及び事務局）が作成し、会長へ提出しなければならない。</p>
    <p><strong>（慶弔見舞に関する規定）</strong><br>
    第１２条　現職の常任委員が死亡した際、生花を本会の名義で出す。<br>
    ２　その他の場合は、本会としては行わない。</p>
    <p><strong>（団体保険加入とその保険金支払いに関する規定）</strong><br>
    第１３条　本会主催の行事の際、常任委員会に報告後必要に応じ団体保険の加入手続きを行う。<br>
    ２　保険金の支払いについては、事務局で当事者と連携をとり、保険規定に従って手続きをとり保険金の支払いをする。</p>
    <p><strong>（会計に関する規定）</strong><br>
    第１４条　本会の運営費は、銀行口座を作成し預けて運用する。<br>
    ２　口座の印鑑は会長、通帳は会計が保管する。<br>
    ３　会計は、次の任務を負う。<br>
    (1) 現金、預金の管理<br>
    (2) 予算に基づく運用計画と出納業務<br>
    (3) 決算書類の作成<br>
    (4) 各部その他の事務連絡等による会計実務全般<br>
    ４　本会の会計は、自治会連合会の総会で議決された予算にもとづいて行う。<br>
    ５　予算は、次年度の活動方針を基礎として、計上された予算をもとに、常任委員会で審議作成し、総会の議決に付す。<br>
    ６　金銭の出納にはすべて伝票(第３号様式)を用いる。また、研修会参加等で交通費を出金する場合は（第４号様式）を用いる。</p>
</div>

<div class="about-charter__block">
    <p>(1) 伝票は、出金・入金および振替伝票とし、会計、該当委員会の委員長および会長の認印を要する。<br>
    (2) 出納処理事項はすべて帳簿に記録する。<br>
    (3) 支出は、領収書または請求書にもとづき、会計が出金伝票を作成し、該当委員会の委員長および会長の承認を得て支払う。<br>
    (4) 予算出金の際は、事前に常任委員会にて報告をするものとする。また、緊急を要する場合は会長、該当委員会の委員長の承認を得て出金する。<br>
    ７　毎年度末にすべての会計帳簿を締め切り、決算報告書を作成する。<br>
    ８　会計は、会計監査に決算報告書を提出し、監査を受けなければならない。<br>
    ９　監査を受けた決算報告書は、常任委員会で確認し、総会の承認を受けなければならない。<br>
    １０　監査は、一年間に２回（原則１０月、４月）行うものとする。ただし、会計監査の判断で、定期以外に行うことができる。会計監査は、監査に必要な書類の提出や常任委員から意見を求めることができる。<br>
    １１　監査は次の事項について行う。<br>
    (1) 予算執行の適否<br>
    (2) 諸経費の適否<br>
    (3) 帳簿記載・伝票整理の適否<br>
    (4) 現金および預貯金残高の確認<br>
    (5) その他必要と認められた事項<br>
    １２　会計監査は、監査の都度、常任委員会に文書を以て総会前に、会計の決算書と同時にその結果を報告する。</p>
    <p><strong>附則</strong><br>
    平成２２年４月１日　施行<br>
    平成２４年４月１日　一部改正（第８条）</p>
    <p><strong>※別表２（第４条関連）</strong></p>
    <table class="about-charter__table">
        <thead>
            <tr>
                <th scope="col">地域</th>
                <th scope="col">該当ブロック</th>
                <th scope="col">ブロック数</th>
                <th scope="col">自治会数</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>第一地域</td>
                <td>第４、１５、２０ブロック</td>
                <td>３</td>
                <td>１９</td>
            </tr>
            <tr>
                <td>第二地域</td>
                <td>第１２、１７、１８、１９ブロック</td>
                <td>４</td>
                <td>１３</td>
            </tr>
            <tr>
                <td>第三地域</td>
                <td>第１、２、３ブロック</td>
                <td>３</td>
                <td>２２</td>
            </tr>
            <tr>
                <td>第四地域</td>
                <td>第５、６、７、８、９、１３、１６ブロック</td>
                <td>７</td>
                <td>３２</td>
            </tr>
            <tr>
                <td>第五地域</td>
                <td>第１０、１１、１４ブロック</td>
                <td>３</td>
                <td>１３</td>
            </tr>
        </tbody>
    </table>
    <p class="about-charter__table-note">平成２４年１２月現在</p>
</div>

                    </div>
                </div>

            </div><!-- /.about-charter -->

        </div><!-- /.about-chart__container -->

    </div><!-- /.about-chart__inner -->
</section><!-- /.about-chart -->
