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
	'a'    => array(
		'href'  => array(),
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
					<h1><?php echo esc_html__( 'Bug Fixes, Maintenance and New Features', 'mesh' ); ?></h1>
				</div>
			</div>
			<div class="gray-bg negative-bg versioninfo">
				<div class="wrapper">
					<h2 class="light-weight">
						<?php printf( 'Mesh Version %s <span class="green-pipe">|</span> Released %s', esc_html( get_option( 'mesh_version' ) ), esc_html__( 'Nov 6th, 2018', 'mesh' ) ); ?></h2>
				</div>
			</div>
			<div class="wrapper">
				<div class="new-feature-container">

					<div class="new-title">
						<img class="new-img" src="<?php echo esc_url( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/icon-new-feature.svg' ); ?>" alt="<?php esc_attr_e( 'New Features', 'mesh' ); ?>"/>
						<h3 class="new-tag"><?php esc_html_e( 'New Features', 'mesh' ); ?></h3>
					</div>

					<div class="new mesh-row">
						<div class="mesh-columns-6">
							<div class="new-feature">
								<h4 class="no-margin"><?php esc_html_e( 'Variable Width & Centered Single Columns', 'mesh' ); ?></h4>
								<p><?php esc_html_e( 'Users now have the ability to define the width of a single column/block. Additionally you can also set if the column will center in it\'s parent (row/container)', 'mesh' ); ?></p>
							</div>
						</div>
						<div class="mesh-columns-6">
							<img src="<?php echo esc_url( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/mesh-screenshot-feature2.png' ); ?>" />
						</div>
					</div>

					<div class="new mesh-row">
						<div class="mesh-columns-6">
							<img src="<?php echo esc_url( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/mesh-screenshot-feature1.png' ); ?>" />
						</div>
						<div class="mesh-columns-6">
							<div class="new-feature">
								<h4 class="no-margin"><?php esc_html_e( 'Foundation: Float, Flex and XY Grid Compatible (Oh My!)', 'mesh' ); ?></h4>
								<p><?php esc_html_e( "We've updated Mesh to support Foundation 6.4.x which includes support for Float Grid (Which foundation 5 and 6 use). Legacy Flex Grid and the newer XY Grid which is based on CSS Grid.", 'mesh' ); ?></p>
							</div>
						</div>
					</div>

					<div class="new mesh-row">
						<div class="mesh-columns-12">
							<div class="new-feature">
								<h4 class="no-margin"><?php esc_html_e( 'Customize Mesh Even Further', 'mesh' ); ?></h4>
								<p><?php esc_html_e( 'We\'ve added a even more hooks for extending mesh.', 'mesh' ); ?></p>
								<h5><?php esc_html_e( 'Filters', 'mesh' ); ?></h5>
								<ul>
									<li><?php esc_html_e( 'You can now define your own max columns using the `mesh_max_columns` filter', 'mesh' ); ?></li>
									<li><?php esc_html_e( 'Filter html attributes for sections, rows and columns, `mesh_element_attributes`', 'mesh' ); ?></li>
									<li><?php esc_html_e( 'Filter html attributes for sections `mesh_section_attributes`', 'mesh' ); ?></li>
									<li><?php esc_html_e( 'Filter html attributes for rows `mesh_row_attributes`', 'mesh' ); ?></li>
									<li><?php esc_html_e( 'Filter html attributes for columns `mesh_column_attributes`', 'mesh' ); ?></li>
									<li><?php esc_html_e( 'Filter css classes for rows `mesh_row_classes`', 'mesh' ); ?></li>
									<li><?php esc_html_e( 'Filter css classes for title `mesh_section_title_css_classes`', 'mesh' ); ?></li>
									<li><?php esc_html_e( 'Filter css classes for columns `mesh_column_classes`', 'mesh' ); ?></li>
									<li><?php esc_html_e( 'Filter available responsive grid systems `mesh_responsive_grid_systems`', 'mesh' ); ?></li>
									<li><?php esc_html_e( 'Filter available to override available foundation versions `mesh_foundation_version`', 'mesh' ); ?></li>
									<li><?php esc_html_e( 'Filter to define the max columns of a given row `mesh_max_columns` Default remains 12', 'mesh' ); ?></li>
								</ul>
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
								<h4 class="no-margin"><?php esc_html_e( 'Updating CSS', 'mesh' ); ?></h4>
								<p><?php esc_html_e( 'Cleaning up some CSS to make things more consistent.', 'mesh' ); ?></p>
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
									<li><?php esc_html_e( 'Quality of life fixes for UI.','mesh' ); ?></li>
									<li><?php esc_html_e( 'Background images can now be removed on blocks!','mesh' ); ?></li>
									<li><?php esc_html_e( 'Tons of refactoring for section and block option customization','mesh' ); ?></li>
									<li><?php esc_html_e( 'Fixed offset selector being shown when it shouldn\'t be','mesh' ); ?></li>
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
									esc_url( 'https://linchpin.com' )
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
							<h3 class="bold no-margin"><?php esc_html_e( 'Tell Us What You Think', 'mesh' ); ?></h3>
							<p><?php esc_html_e( 'How is Mesh working for you? We are always open to feedback and would love to hear about your experience with Mesh.', 'mesh' ); ?></p>
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
							<h3 class="bold inline"><?php esc_html_e( 'Follow Mesh! ', 'mesh' ); ?></h3>
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
