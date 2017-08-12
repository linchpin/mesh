<?php
/**
 * Updates, Bug Fixes and News Template
 *
 * @since      1.2
 *
 * @package    Mesh
 * @subpackage MeshAdmin
 */

$safe_content = array(
	'a' => array(
		'href' => array(),
		'class' => array(),
	),
	'span' => array(
		'class' => array(),
	),
);

?>

<div id="whats-new">
	<div id="post-body" class="metabox-holder">
		<div id="postbox-container" class="postbox-container">
			<div class="whatsnew hero negative-bg">
				<div class="hero-text">
					<h1><?php echo esc_html__( 'Updates, Bug Fixes & New Releases', 'mesh' ); ?></h1>
				</div>
			</div>
			<div class="gray-bg negative-bg versioninfo">
				<div class="wrapper">
					<h2 class="light-weight">
						<?php printf( 'Mesh Version %s <span class="green-pipe">|</span> Released %s', esc_html( get_option( 'mesh_version' ) ), esc_html__( 'Aug 12, 2017' ) ); ?></h2>
				</div>
			</div>
			<div class="wrapper">
				<div class="new-feature-container">

					<div class="new-title">
						<img class="new-img" src="<?php echo esc_url( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/icon-new-feature.svg' ); ?>" alt="<?php esc_attr_e( 'New Features', 'mesh' ); ?>"/>
						<h3 class="new-tag"><?php esc_html_e( 'New Features', 'mesh' ); ?></h3>
					</div>

					<div class="new mesh-row">

						<div class="mesh-columns-6 right">
							<img class="right" src="<?php echo esc_url( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/yoast-readability-scoring.png' ); ?>" alt="<?php esc_attr_e( 'Yoast Readability Scoring', 'mesh' ); ?>"/>
							<p class="caption italic no-margin"><?php esc_html_e( 'Yoast now takes into account the copy in your Mesh sections.', 'mesh' ); ?></p>
						</div>

						<div class="mesh-columns-6">
							<div class="new-feature">
								<h4 class="no-margin"><?php esc_html_e( 'Better Yoast Integration', 'mesh' ); ?></h4>
								<p><?php esc_html_e( 'We\'ve added support for the Yoast SEO page analysis tool. Now, any time any new sections and/or Content blocks are created using Mesh they will be taken into account within Yoast\'s page Readability analysis tools.', 'mesh' ); ?></p>
							</div>

							<div class="new-feature">
								<h4 class="no-margin"><?php esc_html_e( 'Multiple Duplicate Post Plugins are Now Supported', 'mesh' ); ?></h4>
								<p><?php esc_html_e( 'With every release of Mesh we want to make sure it is more of a benefit to our users.', 'mesh' ); ?>
									<?php esc_html_e( 'Mesh will now duplicate all sections and blocks when duplicating a page/post/custom post type using the following popular plugins:', 'mesh' ); ?></p>
								<ul>
									<li><a href="https://wordpress.org/plugins/duplicate-post/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Duplicate Post', 'mesh' ); ?></a></li>
									<li><a href="https://wordpress.org/plugins/post-duplicator/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Post Duplicator', 'mesh' ); ?></a></li>
								</ul>
							</div>

							<div class="new-feature">
								<h4 class="no-margin"><?php esc_html_e( 'More Customization for Developers', 'mesh' ); ?></h4>
								<p><?php esc_html_e( 'The fields within Mesh sections and blocks (in the "More Options" and "More" areas, respectively) are now extendible by developers. This means developers can add their own fields easily. This also lays the groundwork for us to be able to add more great features to Mesh!', 'mesh' ); ?></p>
								<p><?php esc_html_e( 'Two new filters were added, "mesh_section_controls" and "mesh_block_controls," which pass the controls as an array of fields. By hooking into this filter, you can add checkboxes, select/dropdowns, and text fields. More field types will be released in the future, allowing you to extend Mesh further.', 'mesh' ); ?></p>
							</div>
						</div>
					</div>

				</div>

				<div class="update-container ">
					<div class="new-title">
						<img src="<?php echo esc_url( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/icon-updates.svg' ); ?>" alt="<?php esc_attr( 'Updates', 'mesh' ); ?>"/>
						<h3 class="new-tag"><?php esc_html_e( 'Updates', 'mesh' ); ?></h3>
					</div>
					<div class="new mesh-row no-margin">
						<div class="mesh-columns-12">
							<div class="update">
								<h4 class="no-margin"><?php esc_html_e( 'Better Onboarding Process', 'mesh' ); ?></h4>
								<p><?php esc_html_e( 'Added in a better starting point for first time users.', 'mesh' ); ?></p>
							</div>
							<div class="update">
								<h4 class="no-margin"><?php esc_html_e( 'Improved TinyMCE Support', 'mesh' ); ?></h4>
								<p><?php esc_html_e( 'We had some reports from users regarding issues using the keyword "javascript" within html tags so now our filters match default WordPress TinyMCE filters.', 'mesh' ); ?></p>
							</div>
						</div>
					</div>

				</div>

				<div class="bug-fix-container mesh-row">

					<div class="new-title">
						<img src="<?php echo esc_url( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/icon-bug.svg' ); ?>" alt="<?php esc_attr_e( 'Bugs', 'mesh' ); ?>"/>
						<h3 class="new-tag"><?php esc_html_e( 'Bug Fixes', 'mesh' ); ?></h3>
					</div>

					<div class="new mesh-row no-margin">
						<div class="mesh-columns-12">
							<div class="bug-fix">
								<h5 class="no-margin"><?php esc_html_e( 'While features are great, stability and bug fixes are equally important. Below are some of the items we tackled this release:', 'mesh' ); ?></h5>
								<ul>
									<li><?php esc_html_e( 'Fixed a bug where reordering would stop that section from working properly until refresh', 'mesh' ); ?></li>
									<li><?php esc_html_e( 'Fixed a bug where collapsed sections could not be toggled open after a new section was added', 'mesh' ); ?></li>
									<li><?php esc_html_e( 'Fixed a bug when excluding Mesh template related taxonomies from the generated sitemap', 'mesh' ); ?></li>
									<li><?php esc_html_e( 'Fixed a bug where section and block background images were displayed before "update" / "publish"', 'mesh' ); ?></li>
								</ul>
							</div>
						</div>
					</div>
				</div>

				<div class="grey-box-container mesh-row" data-equalizer="">
					<div class="mesh-columns-6">
						<div class="grey-box" data-equalizer-watch="">
							<h3 class="bold no-margin"><?php esc_html_e( 'Visit GitHub', 'mesh' ); ?></h3>
							<p>
								<?php
								// translators: %s Linchpin URL.
								printf( wp_kses( __( 'The base Mesh Page Builder is fully open sourced on GitHub. The team at 
                                    <a href="%s" target="_blank">Linchpin</a> actively manages and maintains 
                                    the project. Take a look at the code, fork and submit pull requests or 
                                    provide feedback/feature requests right from GitHub.', 'mesh' ), $safe_content ),
									esc_url( 'https://linchpin.agency' )
								);
								?>
							</p>
							<p class="no-margin">
								<?php
								printf( '<a class="button no-margin" href="%s" target="_blank"> Help Us Build It Better </a>', esc_url( 'https://github.com/linchpin/mesh' ) );
								?>
							</p>
						</div>
					</div>

					<div class="mesh-columns-6">
						<div class="grey-box" data-equalizer-watch="">
							<h3 class="bold no-margin"><?php esc_html_e( 'Tell Us What You Think' ); ?></h3>
							<p><?php esc_html_e( 'How is Mesh working for you? We are always open to feedback and would love to hear about your experience with Mesh.' ); ?></p>
							<p class="no-margin">
								<?php
								// translators: %s review url.
								printf( wp_kses( __( '<a class="button no-margin" href="%s" target="_blank"> Review Mesh</a>', 'mesh' ), $safe_content ),
									esc_url( 'https://wordpress.org/support/plugin/mesh/reviews/' )
								);
								?>
							</p>
						</div>
					</div>
				</div>

				<div class="grey-box-container mesh-row">
					<div class="mesh-columns-12">
						<div class="grey-box">
							<h3 class="bold inline"><?php esc_html_e( 'Follow Mesh! ' ); ?></h3>
							<h3 class="inline light-weight">
								<?php
								// translators: %s Mesh Twitter.
								printf( wp_kses( __( 'Stay up to date with Mesh and see what we\'re up to on <a href="%s" target="_blank">Twitter</a>.', 'mesh' ), $safe_content ),
									esc_url( 'https://twitter.com/meshplugin' )
								);
								?>
							</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
