document.addEventListener('DOMContentLoaded', function () {

    // ===================================
    // ABOUT背景動画（常時ループ再生）
    // ===================================
    (function () {
        const video = document.querySelector('[data-about-bg-video]');
        if (!video) return;

        function playVideo() {
            video.muted = true;
            video.defaultMuted = true;
            video.loop = true;
            video.setAttribute('playsinline', '');
            const promise = video.play();
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
            if (!document.hidden) {
                playVideo();
            }
        });

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        playVideo();
                    }
                });
            }, { threshold: 0.1 });
            observer.observe(video);
        }
    })();

    // ===================================
    // FVスライダー
    // ===================================
    (function () {
        const slider = document.querySelector('[data-fv-slider]');
        if (!slider) return;

        const section = slider.closest('.fv-section');
        const frames  = [...slider.querySelectorAll('.fv-frame')];
        const dots    = section
            ? [...section.querySelectorAll('[data-fv-dot]')]
            : [];
        const INTERVAL = 5000; // 自動切替の間隔（ミリ秒）
        let current = 0;
        let timer   = null;
        let isAnimating = false;

        function goTo(index) {
            if (isAnimating || index === current) return;
            isAnimating = true;

            // 現在のフレームを非アクティブに
            frames[current].classList.remove('is-active');
            frames[current].setAttribute('aria-hidden', 'true');
            dots[current].classList.remove('is-active');
            dots[current].setAttribute('aria-selected', 'false');

            current = index;

            // 新しいフレームをアクティブに
            frames[current].classList.add('is-active');
            frames[current].setAttribute('aria-hidden', 'false');
            dots[current].classList.add('is-active');
            dots[current].setAttribute('aria-selected', 'true');

            // CSSトランジション完了後にフラグをリセット
            setTimeout(function () {
                isAnimating = false;
            }, 900);
        }

        function next() {
            goTo((current + 1) % frames.length);
        }

        function startTimer() {
            clearInterval(timer);
            timer = setInterval(next, INTERVAL);
        }

        function stopTimer() {
            clearInterval(timer);
        }

        // ドットクリックで手動操作
        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                const index = parseInt(this.dataset.fvDot, 10);
                goTo(index);
                startTimer(); // タイマーリセット
            });
        });

        // FVエリアにホバー中は自動再生を一時停止
        const hoverTarget = section || slider;
        hoverTarget.addEventListener('mouseenter', stopTimer);
        hoverTarget.addEventListener('mouseleave', startTimer);

        // タッチスワイプ対応
        let touchStartX = 0;
        slider.addEventListener('touchstart', function (e) {
            touchStartX = e.touches[0].clientX;
            stopTimer();
        }, { passive: true });

        slider.addEventListener('touchend', function (e) {
            const diff = touchStartX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) {
                    next();
                } else {
                    goTo((current - 1 + frames.length) % frames.length);
                }
            }
            startTimer();
        }, { passive: true });

        // 自動再生スタート
        startTimer();
    })();

    // ===================================
    // NEWSスライダー
    // ===================================
    (function () {
        const sliderWrap = document.querySelector('[data-news-slider]');
        if (!sliderWrap) return;

        const track    = sliderWrap.querySelector('[data-news-track]');
        const prevBtn  = document.querySelector('[data-news-prev]');
        const nextBtn  = document.querySelector('[data-news-next]');
        const dotsWrap = document.querySelector('[data-news-dots]');
        const dots     = dotsWrap ? [...dotsWrap.querySelectorAll('[data-news-page]')] : [];

        if (!track) return;

        const cards = [...track.querySelectorAll('.news-card')];
        if (cards.length === 0) return;

        // 1ページあたりの表示枚数を画面幅で決定
        function getPerPage() {
            if (window.innerWidth <= 768) return 1;
            if (window.innerWidth <= 1200) return 2;
            return 3;
        }

        let currentPage = 0;
        let perPage     = getPerPage();
        let totalPages  = Math.ceil(cards.length / perPage);

        // カード幅 + gap から移動量を計算
        function getSlideWidth() {
            const cardEl = cards[0];
            const gap    = 8; // CSS gap と一致
            return cardEl.offsetWidth + gap;
        }

        function updateSlider(page, animate) {
            perPage    = getPerPage();
            totalPages = Math.ceil(cards.length / perPage);
            currentPage = Math.max(0, Math.min(page, totalPages - 1));

            const offset = currentPage * perPage * getSlideWidth();

            if (animate === false) {
                track.style.transition = 'none';
            } else {
                track.style.transition = 'transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
            }
            track.style.transform = `translateX(-${offset}px)`;

            // ドット更新
            dots.forEach(function (dot, i) {
                const isActive = i === currentPage;
                dot.classList.toggle('is-active', isActive);
                dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            // ボタン disabled 制御
            if (prevBtn) prevBtn.disabled = currentPage === 0;
            if (nextBtn) nextBtn.disabled = currentPage >= totalPages - 1;
        }

        // 前へ
        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                updateSlider(currentPage - 1, true);
            });
        }

        // 次へ
        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                updateSlider(currentPage + 1, true);
            });
        }

        // ドットクリック
        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                const page = parseInt(this.dataset.newsPage, 10);
                updateSlider(page, true);
            });
        });

        // タッチスワイプ
        let touchStartX = 0;
        track.addEventListener('touchstart', function (e) {
            touchStartX = e.touches[0].clientX;
        }, { passive: true });

        track.addEventListener('touchend', function (e) {
            const diff = touchStartX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) {
                    updateSlider(currentPage + 1, true);
                } else {
                    updateSlider(currentPage - 1, true);
                }
            }
        }, { passive: true });

        // リサイズ対応
        let resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                updateSlider(0, false);
            }, 200);
        });

        // 初期化
        updateSlider(0, false);
    })();

    // ===================================
    // モバイル ハンバーガーメニュー
    // ===================================
    (function () {
        const menuToggle  = document.querySelector('.menu-toggle');
        const mobileMenu  = document.querySelector('.mobile-menu');
        if (!menuToggle || !mobileMenu) return;

        // 背景オーバーレイを動的に作成
        const overlay = document.createElement('div');
        overlay.className = 'mobile-overlay';
        document.body.appendChild(overlay);

        function openMenu() {
            menuToggle.setAttribute('aria-expanded', 'true');
            mobileMenu.classList.add('is-open');
            mobileMenu.setAttribute('aria-hidden', 'false');
            overlay.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            menuToggle.setAttribute('aria-expanded', 'false');
            mobileMenu.classList.remove('is-open');
            mobileMenu.setAttribute('aria-hidden', 'true');
            overlay.classList.remove('is-open');
            document.body.style.overflow = '';
        }

        menuToggle.addEventListener('click', function () {
            const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
            if (isExpanded) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        overlay.addEventListener('click', closeMenu);

        // Esc キーで閉じる
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeMenu();
        });
    })();

    // ===================================
    // スムーズスクロール（アンカーリンク）
    // ===================================
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                const headerHeight = 72; // fixed ヘッダー高さ
                const targetTop = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;

                window.scrollTo({ top: targetTop, behavior: 'smooth' });

                // モバイルメニューを閉じる
                const mobileMenu = document.querySelector('.mobile-menu');
                const menuToggle = document.querySelector('.menu-toggle');
                if (mobileMenu) {
                    mobileMenu.classList.remove('is-open');
                    mobileMenu.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
        }
        if (menuToggle) menuToggle.setAttribute('aria-expanded', 'false');
            }
        });
    });

    // ===================================
    // COMMUNITIESページ: ブロック絞り込みフィルター
    // ===================================
    (function () {
        const select = document.getElementById('block-filter');
        if (!select) return;

        select.addEventListener('change', function () {
            const val = this.value;
            const cards = document.querySelectorAll('.community-block-card');
            cards.forEach(function (card) {
                if (!val || card.id === val) {
                    card.classList.remove('is-hidden');
                } else {
                    card.classList.add('is-hidden');
                }
            });
        });
    })();

    // ===================================
    // LIBRARYページ: アコーディオン
    // ===================================
    (function () {
        const headers = document.querySelectorAll('.library-accordion__header');
        if (!headers.length) return;

        headers.forEach(function (header) {
            header.addEventListener('click', toggleAccordion);
            header.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleAccordion.call(header);
                }
            });
        });

        function toggleAccordion() {
            const item   = this.closest('.library-accordion__item');
            const body   = item.querySelector('.library-accordion__body');
            const isOpen = item.classList.contains('is-open');

            if (isOpen) {
                item.classList.remove('is-open');
                this.setAttribute('aria-expanded', 'false');
                body.setAttribute('hidden', '');
            } else {
                item.classList.add('is-open');
                this.setAttribute('aria-expanded', 'true');
                body.removeAttribute('hidden');
            }
        }
    })();

    // ===================================
    // お問い合わせフォーム: バリデーション
    // ===================================
    (function () {
        const form = document.querySelector('.contact-form');
        if (!form) return;

        const fields = {
            name: {
                input: form.querySelector('#contact-name'),
                error: form.querySelector('#contact-name-error'),
                validate: function (value) {
                    if (!value.trim()) {
                        return 'お名前を入力してください。';
                    }
                    return '';
                },
            },
            email: {
                input: form.querySelector('#contact-email'),
                error: form.querySelector('#contact-email-error'),
                validate: function (value) {
                    const trimmed = value.trim();
                    if (!trimmed) {
                        return 'メールアドレスを入力してください。';
                    }
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(trimmed)) {
                        return '正しいメールアドレスを入力してください。';
                    }
                    return '';
                },
            },
            message: {
                input: form.querySelector('#contact-message'),
                error: form.querySelector('#contact-message-error'),
                validate: function (value) {
                    if (!value.trim()) {
                        return 'お問い合わせ内容を入力してください。';
                    }
                    return '';
                },
            },
            privacy: {
                input: form.querySelector('#contact-privacy'),
                error: form.querySelector('#contact-privacy-error'),
                validate: function (_value, input) {
                    if (!input.checked) {
                        return '個人情報の取扱規程への同意が必要です。';
                    }
                    return '';
                },
            },
        };

        function setFieldError(field, message) {
            const hasError = Boolean(message);
            field.input.classList.toggle('is-error', hasError);
            field.input.setAttribute('aria-invalid', hasError ? 'true' : 'false');
            field.error.textContent = message;
            field.error.hidden = !hasError;
        }

        function clearFieldError(field) {
            setFieldError(field, '');
        }

        Object.keys(fields).forEach(function (key) {
            const field = fields[key];
            const eventName = field.input.type === 'checkbox' ? 'change' : 'input';

            field.input.addEventListener(eventName, function () {
                const message = field.validate(field.input.value, field.input);
                if (!message) {
                    clearFieldError(field);
                }
            });
        });

        form.addEventListener('submit', function (e) {
            let firstInvalid = null;
            let hasError = false;

            Object.keys(fields).forEach(function (key) {
                const field = fields[key];
                const message = field.validate(field.input.value, field.input);
                setFieldError(field, message);

                if (message) {
                    hasError = true;
                    if (!firstInvalid) {
                        firstInvalid = field.input;
                    }
                }
            });

            if (hasError) {
                e.preventDefault();
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
        });
    })();

});
