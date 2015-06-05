/**
 * matrizTree jQuery Plugin - Leonardo Weslei Diniz - http://leonardoweslei.com/
 */
;
(function ($, window, document, undefined) {
    var musicPlayer = new function () {
        var me = this, my = {};

        my.defaults = {
            mainContainer: '#main',
            importLocalTrigger: '#import-local-action',
            importLegacyTrigger: '#import-legacy-action',
            artistListTrigger: '#artist-list-action'
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

        my.genericAction = function (target, actionName, callback) {
            actionName = my.getPath(actionName);
            callback = callback || function (d) {
                $(my.settings.mainContainer).hide().html(d).show();
            };

            var baseCallback = function (data) {
                callback(data);
                me.loadingHide();
            };

            $(target).click(function (e) {
                e.preventDefault();
                me.loadingShow();
                $.get(actionName, baseCallback);
            });
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
            $(my.settings.mainContainer).append(objLoader);
            objLoader.show();
        };


        me.init = function (param) {
            param = param || [];

            my.settings = $.extend(my.defaults, param);
            my.genericAction(my.settings.importLegacyTrigger, "/import/legacy");
            my.genericAction(my.settings.importLocalTrigger, "/import/local");

            my.genericAction(my.settings.artistListTrigger, "/artist/list");
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