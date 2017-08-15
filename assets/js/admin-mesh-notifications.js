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
                .on('click', '.mesh-update-notice .notice-dismiss', self.dismissNotification );
        },

        /**
         * Ajax call to dismiss notifications.
         *
         * @since 1.2
         */
        dismissNotification : function() {
            $.post( ajaxurl, {
                action                : 'mesh_dismiss_notification',
                mesh_notification_type : $(this).parents('.mesh-update-notice').attr('data-type'),
                _wpnonce              : mesh_notifications.dismiss_nonce
            }, function( response ) {

            });
        }
    };

} ( jQuery );

jQuery(function($) {
  mesh.notifications.init();
} );