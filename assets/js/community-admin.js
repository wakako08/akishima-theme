(function ($) {
    'use strict';

    function getIds(galleryKey) {
        var raw = $('#' + galleryKey + '_ids').val();
        if (!raw) {
            return [];
        }
        return raw.split(',').map(function (id) {
            return parseInt(id, 10);
        }).filter(function (id) {
            return id > 0;
        });
    }

    function setIds(galleryKey, ids) {
        $('#' + galleryKey + '_ids').val(ids.join(','));
    }

    function renderPreview(galleryKey, ids) {
        var $preview = $('#' + galleryKey + '_preview');
        $preview.empty();

        ids.forEach(function (id) {
            var attachment = wp.media.attachment(id);
            attachment.fetch().then(function () {
                var url = attachment.get('sizes') && attachment.get('sizes').thumbnail
                    ? attachment.get('sizes').thumbnail.url
                    : attachment.get('url');

                if (!url) {
                    return;
                }

                var $item = $('<li></li>')
                    .attr('data-id', id)
                    .css({ position: 'relative' });

                $('<img>', {
                    src: url,
                    alt: '',
                    css: { width: '96px', height: '96px', objectFit: 'cover', borderRadius: '4px' }
                }).appendTo($item);

                $('<button>', {
                    type: 'button',
                    class: 'button-link akishima-gallery-remove',
                    'data-gallery': galleryKey,
                    'data-id': id,
                    text: '×',
                    'aria-label': '削除',
                    css: {
                        position: 'absolute',
                        top: '2px',
                        right: '2px',
                        background: '#fff',
                        borderRadius: '50%',
                        width: '22px',
                        height: '22px',
                        lineHeight: '20px',
                        textAlign: 'center'
                    }
                }).appendTo($item);

                $preview.append($item);
            });
        });
    }

    $(document).on('click', '.akishima-gallery-add', function (e) {
        e.preventDefault();

        var galleryKey = $(this).data('gallery');
        var frame = wp.media({
            title: '画像を選択',
            button: { text: '選択' },
            multiple: true,
            library: { type: 'image' }
        });

        frame.on('select', function () {
            var selection = frame.state().get('selection');
            var current = getIds(galleryKey);

            selection.each(function (attachment) {
                var id = attachment.get('id');
                if (current.indexOf(id) === -1) {
                    current.push(id);
                }
            });

            setIds(galleryKey, current);
            renderPreview(galleryKey, current);
        });

        frame.open();
    });

    $(document).on('click', '.akishima-gallery-remove', function (e) {
        e.preventDefault();

        var galleryKey = $(this).data('gallery');
        var removeId = parseInt($(this).data('id'), 10);
        var ids = getIds(galleryKey).filter(function (id) {
            return id !== removeId;
        });

        setIds(galleryKey, ids);
        $(this).closest('li').remove();
    });
})(jQuery);
