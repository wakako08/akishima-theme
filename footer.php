    </div><!-- #content .site-content -->

    <footer id="colophon" class="site-footer" role="contentinfo">
        <div class="footer-inner">

            <!-- 上段: ロゴ+住所 / ナビ2列 -->
            <div class="footer-top">

                <!-- 左: ロゴ + 事務局情報 -->
                <div class="footer-org">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo" aria-label="<?php bloginfo( 'name' ); ?> トップへ">
                        <img
                            src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-footer.png"
                            alt="昭島市自治会連合会 Akishima-jichiren"
                            width="336"
                            height="72"
                            loading="lazy"
                        >
                    </a>
                    <address class="footer-address">
                        <p class="footer-address-name">自治連事務局</p>
                        <p>〒196-8511東京都昭島市田中町1-17-1 昭島市役所<br>
                        市民部コミュニティ課内<br>
                        042-544-5111（内線2289）</p>
                    </address>
                </div>

                <!-- 右: ナビゲーション 2列 -->
                <nav class="footer-nav" aria-label="フッターナビゲーション">
                    <div class="footer-nav-col">
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer-menu-col1',
                            'menu_class'     => 'footer-nav-list',
                            'container'      => false,
                            'fallback_cb'    => false,
                            'depth'          => 1,
                        ) );
                        ?>
                    </div>
                    <div class="footer-nav-col">
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer-menu-col2',
                            'menu_class'     => 'footer-nav-list',
                            'container'      => false,
                            'fallback_cb'    => false,
                            'depth'          => 1,
                        ) );
                        ?>
                    </div>
                </nav>

            </div><!-- .footer-top -->

            <!-- 下段: 助成表記 / コピーライト -->
            <div class="footer-bottom">
                <div class="footer-legal">
                    <p class="footer-grant">平成22年度東京都地域底力再生事業助成対象事業</p>
                    <p class="footer-notice">※ 本ページに記載内容の無断転載を禁じます</p>
                </div>
                <p class="footer-copyright">©Akishima-shi Jichikai</p>
            </div><!-- .footer-bottom -->

        </div><!-- .footer-inner -->
    </footer><!-- #colophon -->

</div><!-- #page .site -->

<?php wp_footer(); ?>
</body>
</html>
