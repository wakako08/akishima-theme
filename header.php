<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

    <header id="masthead" class="site-header" role="banner">

        <!-- モバイル: ハンバーガー開閉ボタン（ヘッダー外に配置してFVに重ねる） -->
        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="メニューを開く">
            <span class="menu-toggle-bar"></span>
            <span class="menu-toggle-bar"></span>
            <span class="menu-toggle-bar"></span>
        </button>

        <!-- ヘッダー本体バー（右寄せ浮遊型） -->
        <div class="header-wrap">

            <!-- ロゴ -->
            <div class="header-logo">
                <a href="<?php echo esc_url( akishima_header_home_url() ); ?>" aria-label="<?php bloginfo( 'name' ); ?> ホームへ">
                    <img
                        src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png"
                        alt="昭島市自治会連合会 Akishima-Jichiren"
                        width="187"
                        height="40"
                    >
                </a>
            </div>

            <!-- ナビゲーション -->
            <nav id="site-navigation" class="header-nav" aria-label="メインナビゲーション">
                <?php
                akishima_wp_nav_menu_main_site( array(
                    'theme_location' => 'header-menu',
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'nav-menu',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'depth'          => 1,
                ) );
                ?>
            </nav>

            <!-- 問い合わせボタン -->
            <a href="<?php echo esc_url( akishima_header_contact_url() ); ?>" class="header-cta">
                <span>問い合わせ</span>
                <svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                    <rect width="18" height="12" fill="#4AEB38"/>
                    <path d="M2.65017 7.5C2.58453 7.5 2.5177 7.38337 2.51166 7.31701C2.47437 6.90131 2.54107 6.42722 2.51192 6.00517C2.50496 5.94962 2.59227 5.86354 2.63691 5.86354H11.7423L11.7297 5.8157L10.2002 3.84921C10.1112 3.68799 10.1722 3.52324 10.3587 3.5L11.3888 3.50432C11.4283 3.51406 11.5754 3.65988 11.6181 3.70082C12.493 4.54075 13.3526 5.41797 14.2046 6.28208C14.3165 6.39547 14.5848 6.5994 14.4735 6.77576C14.409 6.8778 14.2039 7.04024 14.112 7.13349C13.9916 7.25539 13.8712 7.37769 13.7508 7.49986L2.65017 7.5Z" fill="#002239"/>
                </svg>
            </a>

        </div><!-- .header-wrap -->

        <!-- モバイル: ドロワーメニュー -->
        <div id="primary-menu-mobile" class="mobile-menu" aria-hidden="true">
            <?php
            akishima_wp_nav_menu_main_site( array(
                'theme_location' => 'header-menu',
                'menu_id'        => 'primary-menu-list',
                'menu_class'     => 'mobile-nav-menu',
                'container'      => false,
                'fallback_cb'    => false,
                'depth'          => 1,
            ) );
            ?>
            <a href="<?php echo esc_url( akishima_header_contact_url() ); ?>" class="mobile-cta">
                <span>問い合わせ</span>
                <svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                    <rect width="18" height="12" fill="#4AEB38"/>
                    <path d="M2.65017 7.5C2.58453 7.5 2.5177 7.38337 2.51166 7.31701C2.47437 6.90131 2.54107 6.42722 2.51192 6.00517C2.50496 5.94962 2.59227 5.86354 2.63691 5.86354H11.7423L11.7297 5.8157L10.2002 3.84921C10.1112 3.68799 10.1722 3.52324 10.3587 3.5L11.3888 3.50432C11.4283 3.51406 11.5754 3.65988 11.6181 3.70082C12.493 4.54075 13.3526 5.41797 14.2046 6.28208C14.3165 6.39547 14.5848 6.5994 14.4735 6.77576C14.409 6.8778 14.2039 7.04024 14.112 7.13349C13.9916 7.25539 13.8712 7.37769 13.7508 7.49986L2.65017 7.5Z" fill="#002239"/>
                </svg>
            </a>
        </div><!-- .mobile-menu -->

    </header><!-- #masthead -->

    <div id="content" class="site-content">
