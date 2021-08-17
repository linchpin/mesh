<?php
/**
 * Warning to let a user know that the Classic Editor plugin needs to be enabled in order to use Mesh
 *
 * @package Mesh
 *
 * @since 1.4.0
 */ ?>
<div class="notice notice-error is-dismissible">
	<p><strong><?php esc_html_e( 'The Classic Editor plugin is required in order to use Mesh', 'mesh' ); ?></strong></p>
	<button type="button" class="notice-dismiss">
		<span class="screen-reader-text">Dismiss this notice.</span>
	</button>
</div>
