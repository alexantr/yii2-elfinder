if (typeof alexantr === 'undefined' || !alexantr) {
    var alexantr = {};
}

alexantr.elFinder = (function ($) {
    'use strict';

    return {
        registerSelectButton: function (buttonId, url) {
            $('#' + buttonId).on('click', function (e) {
                e.preventDefault();
                var w = screen.width / 1.5, h = screen.height / 1.5;
                if (w < 900 && screen.width > 960) w = 900;
                if (h < 600 && screen.height > 660) h = 600;
                var params = 'menubar=no,toolbar=no,location=no,directories=no,status=no,fullscreen=no,width=' + w + ',height=' + h;
                var win = window.open(url, 'elfinder-select-file', params);
                win.focus();
            });
        },
        filePickerCallback: function (settings) {
            return function (callback, value, meta) {
                // append filter query param
                var separator = settings.file.indexOf('?') !== -1 ? '&' : '?';
                if (meta.filetype === 'image') {
                    settings.url = settings.file + separator + 'filter=image';
                } else if (meta.filetype === 'media') {
                    settings.url = settings.file + separator + 'filter=' + encodeURIComponent('audio,video');
                } else {
                    settings.url = settings.file;
                }
                tinymce.activeEditor.windowManager.open(settings, {
                    oninsert: function (file) {
                        var url = file.url, reg = /\/[^/]+?\/\.\.\//;
                        while (url.match(reg)) {
                            url = url.replace(reg, '/');
                        }
                        callback(url);
                    }
                });
                return false;
            }
        }
    }
})(jQuery);