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