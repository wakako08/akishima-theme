<?php
/**
 * 活動紹介ページ本文
 * 画像: assets/images/activities/
 * 入口: page-activities.php
 */

/**
 * 活動紹介ページ ヒーローセクション
 */
get_template_part(
    'template-parts/shared/page-hero',
    null,
    array(
        'title_en'       => 'ACTIVITIES',
        'title_ja'       => '活動紹介',
        'image'          => 'assets/images/activities/activities-hero.png',
        'image_position' => 'center 40%',
    )
);
// --- template-parts/activities/content-section.php ---
/**
 * 活動紹介ページ: 活動コンテンツセクション
 *
 * 各ブロックのテキストは将来的に差し替えてください。
 * レイアウト:
 *   tall-left  : [縦長写真 | 横小2枚縦並び]
 *   tall-right : [横小2枚縦並び | 縦長写真]
 */
$img = get_template_directory_uri() . '/assets/images/activities/';

$blocks = array(
    array(
        'title'  => '自治会役員研修会',
        'text'   => array(
            '当協議会では、役員間の親睦を深めるとともに、地域のリーダーとしての知見を広げることを目的とした「自治会役員研修会」を毎年開催しています。',
            '研修内容は年ごとに趣向を凝らし、様々な地域や施設を訪問しています。過去には、神奈川方面への研修バスツアーを実施し、地域の歴史や文化に触れるとともに、横浜市民防災センターにて地震・火災の疑似体験ツアーや風水害に関する防災講座を受講するなど、災害対策への意識を深める有意義な機会となりました。今後も、楽しく学びながら自治会運営に活かせる充実した研修を企画してまいります。皆様のご参加を心よりお待ちしております。',
        ),
        'layout' => 'tall-left',
        'tall'   => 'act-01.jpg',
        'small'  => array( 'act-02.jpg', 'act-03.jpg' ),
        'alt'    => array( '自治会役員研修会1', '自治会役員研修会2', '自治会役員研修会3' ),
    ),
    array(
        'title'  => '自治会長説明会',
        'text'   => array(
            '地域の安全・安心なまちづくりを推進するため、定期的に「自治会長説明会」を開催しています。本説明会では、毎年地域の防災や安全に関する重要なテーマを設定し、外部から専門の講師をお招きして講演会を実施しています。',
            '過去には、昭島市防災安全課のご協力のもと、「富士山の噴火が地域に及ぼす影響と対策」をテーマとした初の試みとなる講演会を開催し、参加者からも高い関心が寄せられました。いつ起こるかわからない自然災害に対し、昭島市での具体的な影響や日頃の備えについて、今後も継続して学びを深める機会を提供し、地域の防災力向上に努めてまいります。',
        ),
        'layout' => 'tall-right',
        'tall'   => 'act-06.jpg',
        'small'  => array( 'act-04.jpg', 'act-05.jpg' ),
        'alt'    => array( '自治会長説明会1', '自治会長説明会2', '自治会長説明会3' ),
    ),
    array(
        'title'  => 'おいしい防災',
        'text'   => array(
            '毎年12月に開催している「おいしい防災」は、地域の皆様が気軽に参加し、楽しみながら防災への意識を高められる大人気のイベントです。12月の開催にちなんで、クリスマスにぴったりのメニューをみんなで調理して美味しく味わうなど、食を通じた温かい交流を行っています。',
            '本イベントはお子様連れでのご参加も大歓迎で、食事のほかにも親子で楽しめる様々な企画や催しを用意しています。災害時に役立つ食の知恵を学びつつ、世代を超えた地域の「つながり」を育むアットホームな場として、これからも継続して開催してまいります。',
        ),
        'layout' => 'tall-left',
        'tall'   => 'act-07.jpg',
        'small'  => array( 'act-08.jpg', 'act-09.jpg' ),
        'alt'    => array( 'おいしい防災1', 'おいしい防災2', 'おいしい防災3' ),
    ),
    array(
        'title'  => 'ブロック対抗スポーツ大会',
        'text'   => array(
            '地域の絆を深め、健康増進を図ることを目的に、毎年「ブロック対抗スポーツ大会」を開催しています。本大会では、誰もが気軽に参加して楽しめるよう、年ごとに様々な種目を選定してブロック対抗で順位を競い合います。',
            'これまでに「ペタンク大会」などを実施しており、年齢や性別を問わず、毎年白熱した試合が繰り広げられ、会場は大いに盛り上がります。声を掛け合い、心地よい汗を流すことで、自治会やブロックの枠を超えた確かな連帯感が生まれる貴重な機会となっています。皆様の熱いご声援とご参加をお待ちしております。',
        ),
        'layout' => 'tall-right',
        'tall'   => 'act-12.jpg',
        'small'  => array( 'act-10.jpg', 'act-11.jpg' ),
        'alt'    => array( 'ブロック対抗スポーツ大会1', 'ブロック対抗スポーツ大会2', 'ブロック対抗スポーツ大会3' ),
    ),
);
?>
<section class="activities-content">
    <div class="activities-content__inner">
        <?php foreach ( $blocks as $i => $block ) :
            $is_tall_right = ( $block['layout'] === 'tall-right' );
        ?>
        <div class="activity-block activity-block--<?php echo esc_attr( $block['layout'] ); ?>">

            <!-- テキスト -->
            <div class="activity-block__text">
                <h2 class="activity-block__title"><?php echo esc_html( $block['title'] ); ?></h2>
                <div class="activity-block__body">
                    <?php foreach ( $block['text'] as $para ) : ?>
                    <p><?php echo esc_html( $para ); ?></p>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- 写真コラージュ -->
            <div class="activity-block__photos activity-photos activity-photos--<?php echo esc_attr( $block['layout'] ); ?>">

                <?php if ( ! $is_tall_right ) : /* tall-left: 縦長 → 小2枚 */ ?>
                <div class="activity-photos__tall">
                    <img src="<?php echo esc_url( $img . $block['tall'] ); ?>" alt="<?php echo esc_attr( $block['alt'][0] ); ?>" loading="lazy">
                </div>
                <div class="activity-photos__small-col">
                    <?php foreach ( $block['small'] as $j => $sm ) : ?>
                    <div class="activity-photos__small">
                        <img src="<?php echo esc_url( $img . $sm ); ?>" alt="<?php echo esc_attr( $block['alt'][ $j + 1 ] ); ?>" loading="lazy">
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php else : /* tall-right: 小2枚 → 縦長 */ ?>
                <div class="activity-photos__small-col">
                    <?php foreach ( $block['small'] as $j => $sm ) : ?>
                    <div class="activity-photos__small">
                        <img src="<?php echo esc_url( $img . $sm ); ?>" alt="<?php echo esc_attr( $block['alt'][ $j + 1 ] ); ?>" loading="lazy">
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="activity-photos__tall">
                    <img src="<?php echo esc_url( $img . $block['tall'] ); ?>" alt="<?php echo esc_attr( $block['alt'][0] ); ?>" loading="lazy">
                </div>
                <?php endif; ?>

            </div><!-- /.activity-block__photos -->

        </div><!-- /.activity-block -->
        <?php endforeach; ?>
    </div>
</section>
