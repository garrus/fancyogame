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
     * color [default] default|info|success|warning|danger  or custom color
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
            if (typeof options.color == 'string') {
                if (/^info|success|warning|danger$/.test(options.color)) {
                    className += ' progress-' + options.color;
                    options.color = '';
                }
            } else {
                options.color = '';
            }
            that.toggleClass(className, true)
                .html('<div class="bar" style="width: 0%;'+
                    (options.color?'background-color: '+options.color+';':'')+'"></div>');

            var interval = (options.totaltime * 5);
            if (interval < 500) interval = 500;
            _updateInterval(that, new Date().getTime(), options.elapsedtime, options.totaltime, options.inverse==1, interval);
        });

        /**
         * @param $elem JQuery object
         * @param startTime int millisecond
         * @param elapsedTime int millisecond
         * @param totalTime int second
         * @param inverse boolean
         * @param interval int millisecond
         * @private
         */
        function _updateInterval($elem, startTime, elapsedTime, totalTime, inverse, interval){
            var nowTime = new Date().getTime(); // millisecond
            elapsedTime += nowTime - startTime;
            var percent = elapsedTime / totalTime / 10;
            $elem.children('div.bar').css('width', (inverse ? 100 - percent : percent) + '%');
            if (percent < 100) {
                setTimeout(function(){
                    _updateInterval($elem, nowTime, elapsedTime, totalTime, inverse, interval);
                }, interval);
            } else {
                $(document).trigger('progress-timer.ring', [$elem]);
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