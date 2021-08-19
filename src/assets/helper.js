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
            function removeDots(url) {
                var reg = /\/[^/]+?\/\.\.\//;
                while (url.match(reg)) {
                    url = url.replace(reg, '/');
                }
                return url;
            }
            return function (callback, value, meta) {
                var separator = settings.url.indexOf('?') !== -1 ? '&' : '?';
                if (meta.filetype === 'image') {
                    settings.url += separator + 'filter=image';
                } else if (meta.filetype === 'media') {
                    settings.url += separator + 'filter=audio%2Cvideo';
                }
                if (tinymce.majorVersion === '4') {
                    tinymce.activeEditor.windowManager.open(settings, {
                        oninsert: function (file) {
                            var url = removeDots(file.url);
                            callback(url);
                        }
                    });
                } else {
                    settings.onMessage = function (api, data) {
                        if (data.mceAction === 'customAction') {
                            var url = removeDots(data.file.url);
                            callback(url, {});
                            api.close();
                        }
                    }
                    tinymce.activeEditor.windowManager.openUrl(settings);
                }
            }
        }
    }
})(jQuery);