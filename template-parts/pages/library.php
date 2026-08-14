<?php
/**
 * 資料室ページ本文
 * リンク一覧: 本ファイル内 $accordion_items
 * 入口: page-library.php
 */

/**
 * LIBRARYページ ヒーローセクション
 */
get_template_part(
    'template-parts/shared/page-hero',
    null,
    array(
        'title_en'       => 'LIBRARY',
        'title_ja'       => '自治連広報・その他資料',
        'image'          => 'assets/images/library/library-hero.png',
        'image_position' => 'center 40%',
    )
);
// --- template-parts/library/accordion.php ---
/**
 * LIBRARYページ: 資料アコーディオンセクション
 * 原稿: https://akishima-jichiren.jp/活動紹介/public/
 */

$material_base = 'https://www.akishima-jichiren.jp/material/';
$dbook_base    = 'https://www.akishima-jichiren.jp/dbook/';

$accordion_items = array(
    array(
        'title' => '加入促進他参考チラシ',
        'open'  => true,
        'items' => array(
            array( 'label' => '入会勧誘（Word）',                      'url' => $material_base . 'InfoEnt.doc' ),
            array( 'label' => '自治会加入届（Word）',                   'url' => $material_base . 'EntRegsl.doc' ),
            array( 'label' => '自治会総会開催のお知らせ①＆②（Word）', 'url' => $material_base . 'GenMeeting.doc' ),
            array( 'label' => '応急救護訓練の実施ポスター',              'url' => $material_base . 'EmrgTrn.docx' ),
            array( 'label' => '30夏祭り（Word）',                       'url' => $material_base . 'SumFes30.doc' ),
            array( 'label' => 'ブロック運動会（Excel）',                 'url' => $material_base . 'AthlMeet.xlsx' ),
            array( 'label' => '案内 歳末防犯パトロール（Excel）',        'url' => $material_base . 'CrimPrvn.xlsx' ),
            array( 'label' => '依頼 年末パトロール（Word）',             'url' => $material_base . 'ReqPatlPrsn.docx' ),
            array( 'label' => '年末パトロール参加お願い（Word）',        'url' => $material_base . 'PtrlPtcp.doc' ),
            array( 'label' => '年末餅つき案内（Word）',                  'url' => $material_base . 'MochiYrEnd.doc' ),
            array( 'label' => '案内 お正月遊び（Word）',                 'url' => $material_base . 'NYearPlay.docx' ),
        ),
    ),
    array(
        'title' => '提出書類様式',
        'open'  => false,
        'items' => array(
            array( 'label' => '会員特典サービスの依頼文 （PDF ）',            'url' => $material_base . 'associate_message2.pdf' ),
            array( 'label' => '会員特典サービス申込書（Word）',               'url' => $material_base . 'kai_toku.doc' ),
            array( 'label' => 'バナー広告協賛の依頼文（PDF ）',               'url' => $material_base . 'kokoku_irai.pdf' ),
            array( 'label' => 'バナー広告掲載申込書（Word）',                 'url' => $material_base . 'baner_mousshi.doc' ),
        ),
    ),
    array(
        'title' => '定時総会議案書',
        'open'  => false,
        'items' => array(
            array( 'label' => '第62回定時総会 2020年度(令和 2年)', 'url' => $material_base . '2020giansho.pdf' ),
            array( 'label' => '第61回定時総会 2019年度(平成31年)', 'url' => $material_base . '2019giansho.pdf' ),
            array( 'label' => '第60回定時総会 2018年度(平成30年)', 'url' => $material_base . '2018giansho.pdf' ),
            array( 'label' => '第56回定時総会 2014年度(平成26年)', 'url' => $material_base . '2014giansho.pdf' ),
            array( 'label' => '第55回定時総会 2013年度(平成25年)', 'url' => $material_base . 'guiansyo2013.pdf' ),
            array( 'label' => '第54回定時総会 2012年度(平成24年)', 'url' => $material_base . '2012_bill.pdf' ),
        ),
    ),
    array(
        'title' => '自治会運営マニュアル',
        'open'  => false,
        'items' => array(
            array( 'label' => '自治会運営マニュアルvｅｒ.４（2018年12月改訂）', 'url' => $material_base . 'mgmt_manu.pdf' ),
        ),
    ),
    array(
        'title' => '自治連会報',
        'open'  => false,
        'items' => array(
            array( 'label' => '2021年 1月号', 'url' => $material_base . '20210101.pdf' ),
            array( 'label' => '2020年 1月号', 'url' => $material_base . '20200101.pdf' ),
            array( 'label' => '2019年 1月号', 'url' => $material_base . '20190101.pdf' ),
            array( 'label' => '2018年 1月号', 'url' => $material_base . '20180101.pdf' ),
            array( 'label' => '2017年 1月号', 'url' => $material_base . '20170101.pdf' ),
            array( 'label' => '2016年 1月号', 'url' => $material_base . '20160101.pdf' ),
            array( 'label' => '2015年 1月号', 'url' => $material_base . 'kaiho1412.pdf' ),
            array( 'label' => '2014年 1月号', 'url' => $material_base . 'jichiren131225.pdf' ),
            array( 'label' => '2013年 1月号', 'url' => $material_base . 'kaiho1301.pdf' ),
            array( 'label' => '2012年 1月号', 'url' => $material_base . 'report_201201.pdf' ),
            array( 'label' => '2011年 1月号', 'url' => $dbook_base . 'h23/' ),
            array( 'label' => '2010年 1月号・9月号', 'url' => $dbook_base . 'h22/' ),
            array( 'label' => '2009年 1月号・9月号', 'url' => $dbook_base . 'h21/' ),
            array( 'label' => '2008年 1月号・9月号', 'url' => $dbook_base . 'h20/' ),
            array( 'label' => '2007年 1月号・9月号', 'url' => $dbook_base . 'h19/' ),
            array( 'label' => '2006年 1月号・9月号', 'url' => $dbook_base . 'h18/' ),
            array( 'label' => '2005年 1月号・9月号', 'url' => $dbook_base . 'h17/' ),
            array( 'label' => '2004年 1月号・9月号', 'url' => $dbook_base . 'h16/' ),
            array( 'label' => '2003年 1月号・9月号', 'url' => $dbook_base . 'h15/' ),
            array( 'label' => '2002年 1月号・9月号', 'url' => $dbook_base . 'h14/' ),
            array( 'label' => '2001年 9月号', 'url' => $dbook_base . 'h13/' ),
        ),
    ),
    array(
        'title' => '自治連だより',
        'open'  => false,
        'items' => array(
            array( 'label' => '2020年 9月 〔19号〕', 'url' => $material_base . 'news_19.pdf' ),
            array( 'label' => '2020年 8月 〔18号〕', 'url' => $material_base . 'news_18.pdf' ),
            array( 'label' => '2020年 3月 〔17号〕', 'url' => $material_base . 'news_17.pdf' ),
            array( 'label' => '2019年 8月 〔16号〕', 'url' => $material_base . 'news_16.pdf' ),
            array( 'label' => '2019年 3月 〔15号〕', 'url' => $material_base . 'news_15.pdf' ),
            array( 'label' => '2018年 8月 〔14号〕', 'url' => $material_base . 'news_14.pdf' ),
            array( 'label' => '2018年 3月 〔13号〕', 'url' => $material_base . 'news_13.pdf' ),
            array( 'label' => '2017年 7月 〔12号〕', 'url' => $material_base . 'news_12.pdf' ),
            array( 'label' => '2017年 3月 〔11号〕', 'url' => $material_base . 'news_11.pdf' ),
            array( 'label' => '2016年 7月 〔10号〕', 'url' => $material_base . 'news_10.pdf' ),
            array( 'label' => '2016年 3月 〔9号〕', 'url' => $material_base . 'news_09.pdf' ),
            array( 'label' => '2015年 8月 〔8号〕', 'url' => $material_base . 'news_08.pdf' ),
            array( 'label' => '2014年10月 〔7号〕', 'url' => $material_base . 'news_07.pdf' ),
            array( 'label' => '2014年 8月 〔6号〕', 'url' => $material_base . 'news_06.pdf' ),
            array( 'label' => '2012年11月 〔5号〕', 'url' => $material_base . 'news_05.pdf' ),
            array( 'label' => '2012年 ８月 〔4号〕', 'url' => $material_base . 'news_04.pdf' ),
            array( 'label' => '2012年 ３月 〔3号〕', 'url' => $material_base . 'news_03.pdf' ),
            array( 'label' => '2011年11月 〔2号〕', 'url' => $material_base . 'news_02.pdf' ),
            array( 'label' => '2011年 ８月 〔1号〕', 'url' => $material_base . 'news_01.pdf' ),
        ),
    ),
    array(
        'title' => '防災資料',
        'open'  => false,
        'items' => array(
            array( 'label' => '昭島市洪水・土砂災害ハザードマップ', 'url' => 'https://www.city.akishima.lg.jp/kurashi/bosai/1008247/1008248/1002305.html' ),
        ),
    ),
);
?>
<section class="library-accordion">
    <div class="library-accordion__inner">
        <dl class="library-accordion__list">
            <?php foreach ( $accordion_items as $item ) :
                $is_open = ! empty( $item['open'] );
            ?>
            <div class="library-accordion__item<?php echo $is_open ? ' is-open' : ''; ?>">
                <dt class="library-accordion__header" role="button" tabindex="0" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>">
                    <span class="library-accordion__title"><?php echo esc_html( $item['title'] ); ?></span>
                    <span class="library-accordion__icon" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L12 15L18 9" stroke="#002239" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </dt>
                <dd class="library-accordion__body" <?php echo $is_open ? '' : 'hidden'; ?>>
                    <?php if ( ! empty( $item['items'] ) ) : ?>
                    <ul class="library-accordion__files">
                        <?php foreach ( $item['items'] as $file ) : ?>
                        <li>
                            <?php if ( ! empty( $file['url'] ) ) : ?>
                            <a href="<?php echo esc_url( $file['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                                <?php echo esc_html( $file['label'] ); ?>
                            </a>
                            <?php else : ?>
                            <?php echo esc_html( $file['label'] ); ?>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else : ?>
                    <p class="library-accordion__empty">準備中です。</p>
                    <?php endif; ?>
                </dd>
            </div>
            <?php endforeach; ?>
        </dl>
    </div>
</section>

<?php
// --- template-parts/library/homepage-section.php ---
/**
 * LIBRARYページ: 自治連ホームページ紹介セクション
 */
?>
<section class="library-homepage">
    <div class="library-homepage__inner">

        <!-- タイトル ＋ ボタン -->
        <div class="library-homepage__lead">
            <p class="library-homepage__title">自治連のホームページも活用ください！</p>
            <a href="https://akishima-jichiren.jp/" class="library-homepage__btn" target="_blank" rel="noopener noreferrer">
                <span class="library-homepage__btn-text">HOME PAGE</span>
                <?php get_template_part( 'template-parts/shared/action-btn-arrow' ); ?>
            </a>
        </div>

        <!-- 本文テキスト -->
        <div class="library-homepage__body">
            <p>2011年3月にスタートした、自治連のホームページは、東京都の地域の底地から再生事業助成金制度を利用し、自治連の中に専門委員会を設定し、委員メンバーを中心に、常任委員全員で協議しながら作成しました。特に単一自治会のページも用意させていただきましたので、大いにご活用ください。</p>
            <p>
                自治連アドレスは、<a href="https://akishima-jichiren.jp/" target="_blank" rel="noopener noreferrer">https://akishima-jichiren.jp/</a>です。<br>
                まず、ご覧ください。<br>
                内容は、昭島市の紹介や、自治連合会としても2009年に50周年を迎えた際の記念誌や映像、これまでの開放も掲載しましたのでご覧ください。
            </p>
            <p>
                このホームページの特徴は、単一時司会のページを持ち、投稿は、ブログ方式となっております。99自治会のうち準備できた自治会から随時投稿を開始しております。各自治会のブログ担当者と自治会長を中心に役員と連携し、書き込みをしていただいております。<br>
                自治会別のページの投稿用にパスワードを、ブログ担当者には個別にお渡しして、ページを管理・運営していただいています。<br>
                担当者別が困ったときの対応は、サポート用の広場（サイト）を用意しメールのやり取りで、即答できる体制を取っていますから、ご利用ください。
            </p>
            <p>自治連のトップページは、自治連の広報委員会と事務局で担当し書き込みをしております。もちろん、このホームページが今後も継続するために専門の業者に委託して、今後ともさらに、内容を改善していきたいと思います。<br>
                また、ページの中に、バナー広告と一押し店の広告を用意し、市内の各種業界の皆様に協賛をいただき、今後の運営に有効活用させていただきます。また、本冊子の今後の更新や増刷にも使い、広告の掲載料金は今後の運営に有効活用させていただきます。
            </p>
        </div>

    </div>
</section>
