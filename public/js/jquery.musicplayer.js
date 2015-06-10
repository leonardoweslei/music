/**
 * matrizTree jQuery Plugin - Leonardo Weslei Diniz - http://leonardoweslei.com/
 */
;
(function ($, window, document, undefined) {
    var musicPlayer = new function () {
        var me = this, my = {};

        my.defaults = {
            MainContainer: '#main',
            ImportLocalTrigger: '.import-local-action',
            ImportLegacyTrigger: '.import-legacy-action',
            ArtistListTrigger: '.artist-list-action',
            AlbumListTrigger: '.album-list-action',
            TrackListTrigger: '.track-list-action'
        };

        my.settings = my.defaults;

        me.get = function (key) {
            key = key || false;

            if (!key) {
                return my;
            }

            return my.settings[key];
        };

        my.getPath = function (part) {
            part = part || '';
            part = part.trim('/');

            var url = location.href;

            url = url.split('/');

            if (url[url.length - 1] != "") {
                url.pop();
            }

            url = url.join('/') + '/' + part;

            return url;
        };

        my.genericAction = function (trigger, actionName, callback) {
            actionName = my.getPath(actionName);

            callback = callback || function () {
            };

            trigger = trigger || function () {
                me.loadingShow();
                $.get(actionName, function (d) {
                    $(my.settings.MainContainer).hide().html(d).show();
                    callback(d);
                    me.loadingHide();
                });
            };

            trigger();
        };

        me.ImportLegacy = function (e) {
            e = e || false;

            my.genericAction(false, "/import/legacy");

            if (e) {
                e.preventDefault();
            }
        };

        me.ImportLocal = function (e) {
            e = e || false;

            my.genericAction(false, "/import/local");

            if (e) {
                e.preventDefault();
            }
        };

        me.ArtistList = function (e) {
            e = e || false;

            my.genericAction(false, "/artist/list");

            if (e) {
                e.preventDefault();
            }
        };

        me.AlbumsList = function (e) {
            e = e || false;
            var id = $(this).data('id');

            my.genericAction(false, "/album/list/" + id);

            if (e) {
                e.preventDefault();
            }
        };

        me.TrackList = function (e) {
            e = e || false;
            var id = $(this).data('id');

            my.genericAction(false, "/track/list/" + id);

            if (e) {
                e.preventDefault();
            }
        };

        me.loadingHide = function () {
            $('.loader-container').hide().remove();
        };

        me.loadingShow = function () {
            me.loadingHide();
            var htmlLoader = '<div class="modal-backdrop in loader-container">' +
                '<div class="glyphicon glyphicon-refresh loader-wait"></div>' +
                '</div>';
            var objLoader = $(htmlLoader);
            objLoader.hide();
            $(my.settings.MainContainer).append(objLoader);
            objLoader.show();
        };


        me.init = function (param) {
            param = param || [];

            my.settings = $.extend(my.defaults, param);
            $(document).on('click', my.settings.ImportLegacyTrigger, me.ImportLegacy);
            $(document).on('click', my.settings.ImportLocalTrigger, me.ImportLocal);

            $(document).on('click', my.settings.ArtistListTrigger, me.ArtistList);
            $(document).on('click', my.settings.AlbumListTrigger, me.AlbumsList);
            $(document).on('click', my.settings.TrackListTrigger, me.TrackList);
        };
    };


    $.musicPlayer = function (params) {
        params = params || undefined;
        var method = false;

        var retval = false;

        if (typeof params === 'string' && typeof musicPlayer[params] === 'function') {
            method = params;
            params = undefined;
        } else {
            method = (params || [false])[0];

            if (typeof method === 'string' && typeof musicPlayer[method] === 'function') {
                params = params.slice(1)[0];
            }
        }

        var obj = $(window).data("musicPlayer");

        if (!obj) {
            obj = musicPlayer;

            if (method) {
                obj.init();
            } else {
                obj.init(params);
            }

            $(window).data("musicPlayer", musicPlayer);
        } else if (method) {
            retval = obj[method](params);
        }


        return retval || obj;
    };

})(window.jQuery || window.Zepto, window, document);