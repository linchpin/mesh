<?php
/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the plugin settings and faq pages
 *
 * @since      1.0.0
 *
 * @package    Mesh
 * @subpackage Admin
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/**
 * Options Page
 *
 * Renders the settings page contents.
 *
 * @since       1.0.0
 */

/*
 * Including PHP Markdown Library
 *
 * PHP Markdown Lib
 * Copyright (c) 2004-2015 Michel Fortin
 * <https://michelf.ca/>
 * All rights reserved.
 *
 * Based on Markdown
 * Copyright (c) 2003-2006 John Gruber
 * <https://daringfireball.net/>
 * All rights reserved.
 */
require_once LINCHPIN_MESH___PLUGIN_DIR . '/lib/Michelf/MarkdownInterface.php';
require_once LINCHPIN_MESH___PLUGIN_DIR . '/lib/Michelf/Markdown.php';
require_once LINCHPIN_MESH___PLUGIN_DIR . '/lib/Michelf/MarkdownExtra.php';

use \Michelf\MarkdownExtra;
?>
<div class="wrap" id="mesh-settings">
    <h2><?php esc_html_e( get_admin_page_title() ); ?> </h2>
	<?php settings_errors( self::$plugin_name . '-notices' ); ?>
    <h2 class="nav-tab-wrapper">
		<?php
		foreach ( $tabs as $tab_slug => $tab_name ) :

			$tab_url = add_query_arg( array(
				'settings-updated' => false,
				'tab'              => $tab_slug,
			) );

			$active = ( $active_tab === $tab_slug ) ? ' nav-tab-active' : '';

			?>
            <a href="<?php echo esc_url( $tab_url ); ?>" title="<?php esc_attr_e( $tab_name ); ?>" class="nav-tab <?php esc_attr_e( $active ); ?>">
				<?php esc_html_e( $tab_name ); ?>
            </a>
		<?php endforeach; ?>
    </h2>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div id="postbox-container" class="postbox-container">
				<?php if ( 'settings' === $active_tab ) : ?>
					<?php include_once( LINCHPIN_MESH___PLUGIN_DIR . '/admin/settings-meta-box-display.php' ); ?>
				<?php elseif ( 'changelog' === $active_tab ) : ?>
					<?php
					$changelog_path = LINCHPIN_MESH___PLUGIN_DIR . '/CHANGELOG.md';

					if ( file_exists( $changelog_path ) ) {
						$changelog = file_get_contents( $changelog_path, true );
						echo MarkdownExtra::defaultTransform( $changelog ); // WPCS: ok.
					}
					?>
				<?php elseif ( 'faq' === $active_tab ) : ?>
					<?php
					$readme_path = LINCHPIN_MESH___PLUGIN_DIR . '/README.md';

					if ( file_exists( $readme_path ) ) {
						$readme = file_get_contents( $readme_path, true );
						echo MarkdownExtra::defaultTransform( $readme ); // WPCS: ok.
					}
					?>
					<?php elseif ( 'linchpin' === $active_tab ) : ?>
                    <h2><?php esc_html_e( ' Linchpin is a Digital Agency that specializes in WordPress' ); ?></h2>
                    <p>We loving giving back to the WordPress community through Plugins, Tools/Utilities and through Organzing <a href="http://meetup.com/WordPressRI/">WordPress Rhode Island</a> and WordCamp Rhode Island</p>
                    <p>Check our our <a href="https://linchpin.agency" target="_blank">site</a>. or visit our various profiles below or come say hi at a local event.</p>
                    <dl>
                        <dd><a href="https://jetpack.pro/profile/linchpin/" target="_blank">https://jetpack.pro/profile/linchpin/</a></dd>
                        <dd><a href="https://twitter.com/linchpin_agency" target="_blank">https://twitter.com/linchpin_agency</a></dd>
                        <dd><a href="https://www.facebook.com/linchpinagency" target="_blank">https://www.facebook.com/linchpinagency</a></dd>
                        <dd><a href="https://www.instagram.com/linchpinagency/" target="_blank">https://www.instagram.com/linchpinagency</a></dd>
                    </dl>
				<?php endif; ?>
            </div>
        </div>
    </div>
</div>
