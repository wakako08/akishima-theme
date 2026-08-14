<?php
/**
 * NEWSページ共通ヒーローセクション
 *
 * @param string $title_en  英語タイトル（例: "NEWS"）
 * @param string $title_ja  日本語タイトル（例: "お知らせ"）
 */
get_template_part(
    'template-parts/shared/page-hero',
    null,
    array(
        'title_en' => isset( $args['title_en'] ) ? $args['title_en'] : 'NEWS',
        'title_ja' => isset( $args['title_ja'] ) ? $args['title_ja'] : 'お知らせ',
        'image'    => 'assets/images/news-page-hero.png',
    )
);
