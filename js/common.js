/**
 * Created with JetBrains PhpStorm.
 * User: user
 * Date: 13-6-10
 * Time: 上午3:17
 * To change this template use File | Settings | File Templates.
 */

$.fn.extend($.fn, {
    /**
     * @param options
     * elapsedTime
     * totalTime
     * type [active] normal|striped|active
     * color [default] default|info|success|warning|danger  or custom color, or a callback function that accept the
     * percent as the only parameter and return one of the value above
     */
    progressTimer: function(options){
        this.each(function(index, elem){
            var that = $(elem);
            if (!options) options = {};
            $.extend(options, that.data());
            if (!options.totaltime || options.totaltime <= 0)
                return console.warn('Unexpected value for parameter totalTime.');
            if (!options.elapsedtime) options.elapsedtime = 0;
            options.elapsedtime *= 1000; // convert into milliseconds

            var className = 'progress';
            if (typeof options.type == 'string') {
                switch (options.type) {
                    case 'striped': className += ' progress-striped'; break;
                    case 'active': className += ' progress-striped active'; break;
                    default: break;
                }
            } else {
                className += ' progress-striped active';
            }
            that.toggleClass(className, true).html('<div class="bar" style="width: 0%;"></div>');

            if (typeof options.color !== 'function') {
                _modifyByColor(that, options.color);
            }
            var interval = (options.totaltime * 5);
            var args = {
                '$elem': that,
                'startTime' : new Date().getTime(),
                'elapsedTime': options.elapsedtime,
                'totalTime': options.totaltime,
                'inverse': options.inverse==1,
                'interval': interval < 500 ? 500 : interval,
                'colorCallback': typeof options.color == 'function' ? options.color : undefined
            };
            _updateInterval.call(null, args);
        });

        /**
         * @param args object
         * $elem JQuery object
         * startTime int millisecond
         * elapsedTime int millisecond
         * totalTime int second
         * inverse boolean
         * interval int millisecond
         * colorCallback function
         * @private
         */
        function _updateInterval(args){
            var nowTime = new Date().getTime(); // millisecond
            args.elapsedTime += nowTime - args.startTime;
            args.startTime = nowTime;
            var percent = args.elapsedTime / args.totalTime / 10;
            args.$elem.children('div.bar').css('width', (args.inverse ? 100 - percent : percent) + '%');
            if (typeof args.colorCallback == 'function') {
                _modifyByColor(args.$elem, args.colorCallback.apply(null, [percent]));
            }

            if (percent < 100) {
                setTimeout(function(){
                    _updateInterval(args);
                }, args.interval);
            } else {
                $(document).trigger('progress-timer.ring', [args.$elem]);
            }
        }

        /**
         *
         * @param $elem JQuery object
         * @param color string
         * @private
         */
        function _modifyByColor($elem, color){

            var preDefinedColorMap = {
                'info': 'progress-info',
                'danger': 'progress-danger',
                'warning': 'progress-warning',
                'success': 'progress-success'
            };
            for (var key in preDefinedColorMap) {
                $elem.toggleClass(preDefinedColorMap[key], color == key);
            }
            if (/^default|info|danger|warning|success$/.test(color)) {
                $elem.children('.bar').css('background-color', '');
            } else {
                $elem.children('.bar').css('background-color', color);
            }
        }

        return this;
    },

    /**
     *
     * @param options
     * inverse [0] 1|0
     * time
     */
    timer: function(options){
        this.each(function(index, elem){
            var that = $(elem);
            if (!options) options = {};
            $.extend(options, that.data());

            if (typeof options.time != 'number' || options.time < 0) options.time = 0;
            options.inverse = (options.inverse == 1);
            var startTime = new Date().getTime();
            if (options.inverse) {
                _updateInverseInternal(that, startTime, options.time);
            } else {
                updateNormalInterval(that, startTime, options.time);
            }
        });

        /**
         *
         * @param $elem JQuery object
         * @param startTime int millisecond
         * @param time int second
         * @private
         */
        function updateNormalInterval($elem, startTime, time){
            var seconds = time + Math.round((new Date().getTime() - startTime) / 1000);
            $elem.text(_formatSeconds(seconds));
            setTimeout(function(){
                updateNormalInterval($elem, startTime, time);
            }, 1000);
        }

        /**
         *
         * @param $elem JQuery object
         * @param startTime int millisecond
         * @param time int second
         * @private
         */
        function _updateInverseInternal($elem, startTime, time){
            var seconds = time - Math.round((new Date().getTime() - startTime) / 1000);
            $elem.text(_formatSeconds(seconds));
            if (seconds > 0) {
                setTimeout(function(){
                    _updateInverseInternal($elem, startTime, time);
                }, 1000);
            } else {
                $(document).trigger('timer.ring', [$elem]);
            }
        }

        /**
         *
         * @param seconds
         * @returns {string}
         * @private
         */
        function _formatSeconds(seconds){
            var days = Math.floor(seconds / 86400);
            seconds = seconds % 86400;
            var hours = Math.floor(seconds / 3600);
            seconds = seconds % 3600;
            var minutes = Math.floor(seconds / 60);
            seconds = seconds % 60;
            var tmpl = '';
            if (days) {
                tmpl = '{d}d {h}h';
            } else if (hours) {
                tmpl = '{h}h {i}m {s}s';
            } else if (minutes) {
                tmpl = '{i}m {s}s';
            } else {
                tmpl = '{s}s';
            }
            return tmpl.replace('{d}', days).replace('{h}', hours).replace('{i}', minutes).replace('{s}', seconds);
        }

        return this;
    }

});