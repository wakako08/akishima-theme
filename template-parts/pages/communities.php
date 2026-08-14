<?php
/**
 * 自治会の紹介ページ本文
 * 地図: assets/data/akishima-map.json
 * 一覧マスタ: inc/communities-data.php
 * 入口: page-communities.php
 */

/**
 * 自治会の紹介ページ ヒーローセクション
 */
get_template_part(
    'template-parts/shared/page-hero',
    null,
    array(
        'title_en'       => 'COMMUNITIES',
        'title_ja'       => '自治会の紹介',
        'image'          => 'assets/images/communities/communities-hero.png',
        'image_position' => 'center 40%',
    )
);
// --- template-parts/communities/map-section.php ---
/**
 * 自治会の紹介ページ: エリアマップセクション
 * Leaflet.js を使用した昭島市自治会エリアマップ
 * GeoJSON の url プロパティはマルチサイト子サイトURL（準備中は "#"）
 */
$geojson_path = get_template_directory() . '/assets/data/akishima-map.json';
$geojson_raw  = file_exists( $geojson_path ) ? file_get_contents( $geojson_path ) : '{"type":"FeatureCollection","features":[]}';
$geojson_json = akishima_prepare_map_geojson( $geojson_raw );
$block_urls   = array();
foreach ( akishima_get_blocks_data() as $block ) {
    if ( ! empty( $block['no'] ) && ! empty( $block['guide_url'] ) ) {
        $block_urls[ (int) $block['no'] ] = $block['guide_url'];
    }
}
?>
<section class="communities-map-section">
    <div class="communities-map-section__inner">
        <div id="akishima-map"></div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof L === 'undefined') {
        console.error('Leaflet is not loaded. Map cannot be initialized.');
        return;
    }

    /* ===== GeoJSON データ ===== */
    var mapData = <?php echo $geojson_json; ?>;
    var blockUrls = <?php echo wp_json_encode( $block_urls ); ?>;

    /* ===== ラベルオフセット ===== */
    var blockLabelOffsets = { 14: [0, 40], 18: [0, 50], 21: [0, 50] };

    /* ===== スタイル ===== */
    var blockStyle      = { color: '#cc3333', weight: 3, dashArray: '8, 6', fillColor: '#cc3333', fillOpacity: 0.0 };
    var blockHoverStyle = { color: '#ff0000', weight: 5, dashArray: '8, 6', fillColor: '#ff0000', fillOpacity: 0.2 };
    var areaStyle       = { color: '#0066cc', weight: 2, fillColor: '#0066cc', fillOpacity: 0.2 };
    var areaHoverStyle  = { color: '#0088ff', weight: 3, fillColor: '#0088ff', fillOpacity: 0.4 };

    function isBlock(f) {
        var p = f.properties;
        if (!p) return false;
        return ('block' in p && p.block !== '') && !('name' in p && p.name !== '' && p.name !== null);
    }
    function isAreaWithLabel(f) {
        var p = f.properties;
        if (!p) return false;
        return ('name' in p && p.name !== '' && p.name !== null);
    }
    function isAreaWithoutLabel(f) {
        if (isBlock(f) || isAreaWithLabel(f)) return false;
        if (f.properties && f.properties.skipEmptyLayer) return false;
        return true;
    }

    var map = L.map('akishima-map');
    var areaLayersByName = {};
    var blockOutlineLayersByNum = {};

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, &copy; <a href="https://carto.com/">CARTO</a>',
        maxZoom: 19,
        subdomains: 'abcd'
    }).addTo(map);

    function bindAreaClick(layer, url) {
        layer.on('click', function () {
            if (url && url !== '#') {
                window.open(url, '_blank');
            }
        });
    }

    /* ===== ブロック境界（最下層） ===== */
    var blockLayer = L.geoJSON(mapData, {
        filter: isBlock,
        style: blockStyle,
        interactive: false,
        onEachFeature: function (feature, layer) {
            var blockNum = feature.properties.block;
            var offset   = blockLabelOffsets[blockNum] || [0, 0];
            layer.bindTooltip('第' + blockNum + 'ブロック', {
                permanent: true, direction: 'center', className: 'block-label', interactive: true, offset: offset
            });
            layer.on('add', function () {
                var tt = layer.getTooltip();
                if (tt) {
                    var el = tt.getElement();
                    if (el) {
                        el.style.cursor = 'pointer';
                        el.addEventListener('click', function () {
                            var url = blockUrls[blockNum];
                            if (url) {
                                window.open(url, '_blank');
                            }
                        });
                        el.addEventListener('mouseenter', function () {
                            layer.setStyle(blockHoverStyle);
                            if (blockOutlineLayersByNum[blockNum]) {
                                blockOutlineLayersByNum[blockNum].setStyle(blockHoverStyle);
                            }
                        });
                        el.addEventListener('mouseleave', function () {
                            layer.setStyle(blockStyle);
                            if (blockOutlineLayersByNum[blockNum]) {
                                blockOutlineLayersByNum[blockNum].setStyle(blockStyle);
                            }
                        });
                    }
                }
            });
        }
    }).addTo(map);

    /* ブロック赤枠（破線）を自治会エリアより前面に表示 */
    var blockOutlineLayer = L.geoJSON(mapData, {
        filter: isBlock,
        style: blockStyle,
        interactive: false,
        onEachFeature: function (feature, layer) {
            blockOutlineLayersByNum[Number(feature.properties.block)] = layer;
        }
    }).addTo(map);

    /* ===== ラベルなしエリア（クリック不可・下層） ===== */
    var emptyLayer = L.geoJSON(mapData, {
        filter: isAreaWithoutLabel,
        style: areaStyle,
        interactive: false
    }).addTo(map);

    /* ===== ラベル付きエリア（クリック対象・最上層） ===== */
    var areaLayer = L.geoJSON(mapData, {
        filter: isAreaWithLabel,
        style: areaStyle,
        onEachFeature: function (feature, layer) {
            var areaNum = feature.properties.name;
            var url     = feature.properties.url;
            areaLayersByName[Number(areaNum)] = layer;
            layer.bindTooltip(String(areaNum), { permanent: true, direction: 'center', className: 'area-label' });
            layer.on('mouseover', function () { layer.setStyle(areaHoverStyle); });
            layer.on('mouseout',  function () { layer.setStyle(areaStyle); });
            bindAreaClick(layer, url);
        }
    }).addTo(map);

    /* 重なり順: クリックできない箇所の調整（第4ブロック 28・32 / 第8ブロック全体） */
    var priorityAreaNames = [28, 32, 46, 47, 48, 50];
    priorityAreaNames.forEach(function (name) {
        if (areaLayersByName[name]) {
            areaLayersByName[name].bringToFront();
        }
    });
    areaLayer.bringToFront();
    blockOutlineLayer.bringToFront();

    /* ブロック名ラベルを自治会番号より前面に */
    function raiseBlockLabels() {
        var pane = map.getPane('tooltipPane');
        if (!pane) return;
        blockLayer.eachLayer(function (layer) {
            var tt = layer.getTooltip();
            if (!tt) return;
            var el = tt.getElement();
            if (el) pane.appendChild(el);
        });
    }
    raiseBlockLabels();

    /* ===== 凡例 ===== */
    var legend = L.control({ position: 'bottomright' });
    legend.onAdd = function () {
        var div = L.DomUtil.create('div', 'legend');
        div.innerHTML =
            '<div class="legend-title">昭島市自治会連合会</div>' +
            '<div class="legend-item"><div class="legend-block"></div><span>ブロック</span></div>' +
            '<div class="legend-item"><div class="legend-area"></div><span>自治会（クリックで詳細）</span></div>';
        return div;
    };
    legend.addTo(map);

    /* ===== 初期ビュー ===== */
    var allBounds = L.featureGroup([blockLayer, blockOutlineLayer, areaLayer, emptyLayer]).getBounds();
    map.setView(allBounds.getCenter(), 14);
});
</script>

<?php
// --- template-parts/communities/intro-section.php ---
/**
 * 自治会の紹介ページ: ブロックとは / 自治会とは セクション
 *
 * VIEW MORE のリンク先は、スラッグ 'jichikai-about' の固定ページに設定しています。
 * （'jichikai' はカスタム投稿タイプのアーカイブURLと競合するため 'jichikai-about' を使用）
 */
$jichikai_page = get_page_by_path( 'jichikai-about' );
$view_more_url = $jichikai_page ? get_permalink( $jichikai_page ) : home_url( '/jichikai-about/' );
?>
<section class="communities-intro">
    <div class="communities-intro__inner">

        <!-- ブロックとは -->
        <div class="communities-intro__card">
            <h2 class="communities-intro__title">ブロックとは</h2>
            <hr class="communities-intro__divider">
            <p class="communities-intro__body">自治連では、市内の自治会を複数のブロックに分けて活動しています。ブロックごとに情報共有や課題の整理を行い、地域の特性に応じた取り組みを進めています。これにより、よりきめ細かな対応が可能となり、自治会同士の連携も深まります。ブロック活動は、地域全体の活性化と課題解決を支える重要な役割を担っています。また、災害時の避難所運営を共に行っております。</p>
        </div>

        <!-- 自治会とは -->
        <div class="communities-intro__card">
            <h2 class="communities-intro__title">自治会とは</h2>
            <hr class="communities-intro__divider">
            <div class="communities-intro__card-body">
                <p class="communities-intro__body">自治会は、地域に暮らす住民が自主的に運営する団体で、防災や防犯、環境美化、地域交流などの活動を行っています。日常の見守りや助け合いを通じて、安全で安心して暮らせる地域づくりを支えています。参加は任意ですが、多くの方が関わることで、より住みやすく温かい地域社会が実現します。</p>
                <a href="<?php echo esc_url( $view_more_url ); ?>" class="communities-intro__btn">
                    VIEW MORE
                    <?php get_template_part( 'template-parts/shared/action-btn-arrow' ); ?>
                </a>
            </div>
        </div>

    </div>
</section>

<?php
// --- template-parts/communities/community-list.php ---
/**
 * 自治会の紹介ページ: 自治会一覧セクション
 * ブロック別3カラムリスト + ブロック絞り込みフィルター
 *
 * データ・URLは inc/communities-data.php を参照。
 * 子サイト URL: https://akishimajichiren.sakura.ne.jp/wp3/{ブロック2桁}-{番号2桁}/
 */
$blocks_data = akishima_get_blocks_data();
$img_base    = get_template_directory_uri() . '/assets/images/communities';
?>
<section class="community-list-section" id="community-list">

    <div class="community-list-section__bg" aria-hidden="true">
        <img
            src="<?php echo esc_url( $img_base . '/community-list-bg.png' ); ?>"
            alt=""
            class="community-list-section__bg-img"
            loading="lazy"
        >
    </div>

    <div class="community-list-section__inner">

        <!-- ヘッダー: タイトル + 絞り込みフィルター -->
        <div class="community-list-header">
            <h2 class="community-list-header__title">自治会一覧</h2>
            <div class="community-list-header__filter">
                <svg class="community-list-header__filter-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M3 5h14M6 10h8M9 15h2" stroke="#002239" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <select class="community-list-header__select" id="block-filter" aria-label="ブロックで絞り込み">
                    <option value="">ブロックで絞り込み</option>
                    <?php foreach ( $blocks_data as $block ) : ?>
                    <option value="block-<?php echo esc_attr( $block['no'] ); ?>">
                        <?php echo esc_html( $block['name'] ); ?>（<?php echo count( $block['members'] ); ?>件）
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- ブロック一覧グリッド -->
        <div class="community-block-grid" id="community-block-grid">
            <?php foreach ( $blocks_data as $block ) : ?>
            <div class="community-block-card" id="block-<?php echo esc_attr( $block['no'] ); ?>">

                <!-- ブロックタイトル -->
                <a href="<?php echo esc_url( $block['guide_url'] ); ?>" class="community-block-card__header" aria-label="<?php echo esc_attr( $block['name'] ); ?> のページへ">
                    <span class="community-block-card__number"><?php echo esc_html( $block['no'] ); ?></span>
                    <span class="community-block-card__name"><?php echo esc_html( $block['name'] ); ?></span>
                    <span class="community-block-card__count">(<?php echo count( $block['members'] ); ?>)</span>
                </a>

                <!-- 自治会リスト -->
                <ul class="community-block-card__list">
                    <?php foreach ( $block['members'] as $member ) :
                        $url         = akishima_community_url( $member );
                        $has_url     = ( '#' !== $url && ! empty( $url ) );
                        $is_external = $has_url && akishima_community_is_external( $member );
                    ?>
                    <li class="community-block-card__item">
                        <?php if ( $has_url ) : ?>
                        <a href="<?php echo esc_url( $url ); ?>" class="community-block-card__link"<?php echo $is_external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
                            <span class="community-block-card__item-inner">
                                <span class="community-block-card__item-no"><?php echo esc_html( $member['no'] ); ?></span>
                                <span class="community-block-card__item-name"><?php echo esc_html( $member['name'] ); ?></span>
                            </span>
                            <svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg" class="community-block-card__arrow" aria-hidden="true">
                                <path d="M1 1L6 6L1 11" stroke="#486284" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                        <?php else : ?>
                        <span class="community-block-card__link community-block-card__link--pending">
                            <span class="community-block-card__item-inner">
                                <span class="community-block-card__item-no"><?php echo esc_html( $member['no'] ); ?></span>
                                <span class="community-block-card__item-name"><?php echo esc_html( $member['name'] ); ?></span>
                            </span>
                            <svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg" class="community-block-card__arrow" aria-hidden="true">
                                <path d="M1 1L6 6L1 11" stroke="#c8d0da" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>

            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
