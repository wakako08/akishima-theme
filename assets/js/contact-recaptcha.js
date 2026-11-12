(function () {
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.querySelector('.contact-form');
        if (!form || typeof grecaptcha === 'undefined' || typeof akishimaRecaptcha === 'undefined') {
            return;
        }

        form.addEventListener('submit', function (event) {
            if (form.getAttribute('data-recaptcha-ok') === '1') {
                return;
            }

            event.preventDefault();

            var submit = form.querySelector('.contact-form__submit');
            if (submit) {
                submit.disabled = true;
            }

            grecaptcha.ready(function () {
                grecaptcha.execute(akishimaRecaptcha.siteKey, { action: akishimaRecaptcha.action }).then(function (token) {
                    var input = form.querySelector('input[name="g-recaptcha-response"]');
                    if (!input) {
                        input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'g-recaptcha-response';
                        form.appendChild(input);
                    }
                    input.value = token;
                    form.setAttribute('data-recaptcha-ok', '1');
                    form.submit();
                }).catch(function () {
                    if (submit) {
                        submit.disabled = false;
                    }
                });
            });
        });
    });
})();
