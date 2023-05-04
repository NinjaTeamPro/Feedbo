(function ($) {
    'use strict';
    var feeboGoogleTab = function () {
        var self = this;
        self.init = function () {
            
            window.onGoogleLibraryLoad = function () {

                // Initializing the Google One Tap 2.0
                google.accounts.id.initialize({
                    // Replace it with your Google Client Id
                    client_id: '1077705283804-sp1410spf9rm5t5tvrlfvf1o0sitoo26.apps.googleusercontent.com',
                    // Function to be called with credentials after the one of the listed accounts have been selected by the user
                    callback: self.handleCredentialResponse,
                    // cookie domain that is used for the Google One Tap
                    state_cookie_domain: feedboData.domain,
                    // Context for the UI Message of the Google One Tap
                    context: 'use',
                    // Rednder mode for the UI style of the Google One Tap (NOT MENTIONED IN THE OFFICIAL DOCS)
                    ui_mode: self.isMobile() ? 'bottom_sheet': 'card',
                });
                // This function listens to every action that occurs automatically or if the user acts on it.
                google.accounts.id.prompt((notification) => {
                    
                    switch(notification.getMomentType()) {
                        case 'display':
                            console.log("display action");
                        break;
                        case 'dismissed':
                            console.log("dismissed action");
                            break;
                        case 'skipped':
                            console.log("skipped action");
                            break;
                        default:
                            break;
                    }
                });
            };
            
        };
        
        self.decodeJwtResponse = function (token) {
            var base64Url = token.split(".")[1];
            var base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
            var jsonPayload = decodeURIComponent(
            atob(base64)
                .split("")
                .map(function (c) {
                return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
                })
                .join("")
            );

            return JSON.parse(jsonPayload);

        };

        self.handleCredentialResponse = function ( response ) {
            const responsePayload = self.decodeJwtResponse(response.credential);
            $.ajax({
                type: 'POST',
                url: feedboData.ajaxurl,
                data: {
                  nonce: feedboData.nonce,
                  action: 'feebo_connect_google_on_tab',
                  payload: responsePayload,
                  redirect_uri: feedboData.domain
                },
                success: function (response) {
                    console.log({response})
                    window.location.href = response.data.redirect_uri; 
                },
                error: function (error) {
                  console.log({error})
                },
            })
        };

        
        self.isMobile = function () {
            return window.parent.innerWidth <= 840;
        }
        
    };
  
    $(document).ready(function () {
        var feebo_gogle_tab = new feeboGoogleTab();
        feebo_gogle_tab.init();
    });
})(jQuery);
  