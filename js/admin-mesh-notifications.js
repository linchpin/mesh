/**
 * Controls Notification Administration
 *
 * @since 1.2
 */

var mesh = mesh || {};

mesh.notifications = function ( $ ) {

    var $body = $('body'),
        self; // Instance of our controller

    return {

        /**
         * Initialize Notifications
         */
        init : function() {

            self = mesh.notifications;

            $body
                .on('click', '.mesh-update-notice .notice-dismiss', self.dismissNotification )
                .on('click', '.mesh-review-notice .review-dismiss, .mesh-review-notice .notice-dismiss', self.dismissNotification );
        },

        /**
         * Ajax call to dismiss notifications.
         *
         * @since 1.2
         */
        dismissNotification : function( event ) {

            event.preventDefault();

            var $notice = $(this).parents('.mesh-notice');

            $.post( ajaxurl, {
                action                : 'mesh_dismiss_notification',
                mesh_notification_type : $notice.attr('data-type'),
                _wpnonce              : mesh_notifications.dismiss_nonce
            }, function( response ) {
                $notice.fadeTo(100, 0,function() {
                    $notice.slideUp( 100, function(){
                        $notice.remove();
                    });
                });
            });
        }
    };

} ( jQuery );

jQuery(function($) {
  mesh.notifications.init();
} );
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiIiwic291cmNlcyI6WyJhZG1pbi1tZXNoLW5vdGlmaWNhdGlvbnMuanMiXSwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBDb250cm9scyBOb3RpZmljYXRpb24gQWRtaW5pc3RyYXRpb25cbiAqXG4gKiBAc2luY2UgMS4yXG4gKi9cblxudmFyIG1lc2ggPSBtZXNoIHx8IHt9O1xuXG5tZXNoLm5vdGlmaWNhdGlvbnMgPSBmdW5jdGlvbiAoICQgKSB7XG5cbiAgICB2YXIgJGJvZHkgPSAkKCdib2R5JyksXG4gICAgICAgIHNlbGY7IC8vIEluc3RhbmNlIG9mIG91ciBjb250cm9sbGVyXG5cbiAgICByZXR1cm4ge1xuXG4gICAgICAgIC8qKlxuICAgICAgICAgKiBJbml0aWFsaXplIE5vdGlmaWNhdGlvbnNcbiAgICAgICAgICovXG4gICAgICAgIGluaXQgOiBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgc2VsZiA9IG1lc2gubm90aWZpY2F0aW9ucztcblxuICAgICAgICAgICAgJGJvZHlcbiAgICAgICAgICAgICAgICAub24oJ2NsaWNrJywgJy5tZXNoLXVwZGF0ZS1ub3RpY2UgLm5vdGljZS1kaXNtaXNzJywgc2VsZi5kaXNtaXNzTm90aWZpY2F0aW9uIClcbiAgICAgICAgICAgICAgICAub24oJ2NsaWNrJywgJy5tZXNoLXJldmlldy1ub3RpY2UgLnJldmlldy1kaXNtaXNzLCAubWVzaC1yZXZpZXctbm90aWNlIC5ub3RpY2UtZGlzbWlzcycsIHNlbGYuZGlzbWlzc05vdGlmaWNhdGlvbiApO1xuICAgICAgICB9LFxuXG4gICAgICAgIC8qKlxuICAgICAgICAgKiBBamF4IGNhbGwgdG8gZGlzbWlzcyBub3RpZmljYXRpb25zLlxuICAgICAgICAgKlxuICAgICAgICAgKiBAc2luY2UgMS4yXG4gICAgICAgICAqL1xuICAgICAgICBkaXNtaXNzTm90aWZpY2F0aW9uIDogZnVuY3Rpb24oIGV2ZW50ICkge1xuXG4gICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXG4gICAgICAgICAgICB2YXIgJG5vdGljZSA9ICQodGhpcykucGFyZW50cygnLm1lc2gtbm90aWNlJyk7XG5cbiAgICAgICAgICAgICQucG9zdCggYWpheHVybCwge1xuICAgICAgICAgICAgICAgIGFjdGlvbiAgICAgICAgICAgICAgICA6ICdtZXNoX2Rpc21pc3Nfbm90aWZpY2F0aW9uJyxcbiAgICAgICAgICAgICAgICBtZXNoX25vdGlmaWNhdGlvbl90eXBlIDogJG5vdGljZS5hdHRyKCdkYXRhLXR5cGUnKSxcbiAgICAgICAgICAgICAgICBfd3Bub25jZSAgICAgICAgICAgICAgOiBtZXNoX25vdGlmaWNhdGlvbnMuZGlzbWlzc19ub25jZVxuICAgICAgICAgICAgfSwgZnVuY3Rpb24oIHJlc3BvbnNlICkge1xuICAgICAgICAgICAgICAgICRub3RpY2UuZmFkZVRvKDEwMCwgMCxmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgJG5vdGljZS5zbGlkZVVwKCAxMDAsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAgICAgICAkbm90aWNlLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgfTtcblxufSAoIGpRdWVyeSApO1xuXG5qUXVlcnkoZnVuY3Rpb24oJCkge1xuICBtZXNoLm5vdGlmaWNhdGlvbnMuaW5pdCgpO1xufSApOyJdLCJmaWxlIjoiYWRtaW4tbWVzaC1ub3RpZmljYXRpb25zLmpzIn0=
