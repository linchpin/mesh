<?php
/**
 * Rating/Review Notification Template
 * Display how long the user has been using mesh for and ask them
 * if they would like to give it a review. We could really use it!
 *
 * @package Mesh
 * @since 1.2.5
 */

$mesh_settings = get_option( 'mesh_settings' );

if ( isset( $mesh_settings['first_activated_on'] ) ) :
	$install_date = $mesh_settings['first_activated_on'];
?>
<div class="mesh-notice mesh-review-notice notice notice-info is-dismissible" data-type="review-notice">
	<div class="table">
		<div class="table-cell">
			<img src="<?php echo esc_attr( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/mesh-full-logo-full-color@2x.png' ); ?>" >
		</div>
		<div class="table-cell">
			<p class="no-margin">
				<?php
				// translators: %1$s: human readable time %2$s: Link to review tab on wordpress.org.
				printf( wp_kses_post( __( 'Thanks for using Mesh for the past %1$s. <strong>This is a huge compliment</strong> and we could really use your help with a <a href="%2$s">review on wordpress.org</a>', 'mesh' ) ),
					esc_html( human_time_diff( $install_date, time() ) ),
					esc_url( 'https://wordpress.org/support/plugin/mesh/reviews/?rate=5#new-post' )
				);
				?>
			</p>
			<p class="no-margin">
				<?php echo wp_kses_post( __( 'If you like Mesh and want us to continue development (including gutenberg integration) we could use the support! <a href="#" class="review-dismiss">No Thanks</a>', 'mesh' ) ); ?>
			</p>
		</div>
	</div>
</div>
<?php endif;
