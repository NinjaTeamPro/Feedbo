(function ($) {
    'use strict';
    var feeboFrontend = function () {
        var self = this;
        self.init = function () {
            
            if( 'yes' === feedboData.login_page ) {
                var previous_url = document.referrer;
                if( !previous_url ) {
                    self.eraseCookie('feedbo_previous_url'); // deletecookie
                }
            }
        };
        
        self.setCookie = function ( key, value, expiry ) {
            var expires = new Date();
            expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
            document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
        };

        self.getCookie = function (key) {
            var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
            return keyValue ? keyValue[2] : null;
        };

        self.eraseCookie = function (key) {
            var keyValue = self.getCookie(key);
            self.setCookie(key, keyValue, '-1');
        };
    };
  
    $(document).ready(function () {
        var feeboFr = new feeboFrontend();
        feeboFr.init();
    });
  })(jQuery);
  