<?php
/**
 * Welcome / About
 *
 * @since      1.2.0
 *
 * @package    Mesh
 * @subpackage Admin
 */
?>
<div id="about-mesh">
    <div id="post-body" class="metabox-holder">
        <div id="postbox-container" class="postbox-container">
		    <div class="about-hero negative-bg" style="background-image: url('<?php echo ( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/meshbackground@2x.png'); ?>');">
                <div class="hero-text">
                    <h1><?php esc_html_e( 'Thank you for installing Mesh!', 'mesh' ); ?></h1>
                    <h3><?php esc_html_e( 'Mesh is the most flexible way to add content to your WordPress site. It is a simple, responsive solution for adding multiple sections of content within WordPress pages, posts and custom post types.', 'mesh' ); ?></h3>
                </div>
            </div>
            <div class="gray-bg negative-bg">
                <div class="wrapper">
                    <h2 class="color-darkpurple light-weight">
	                    <?php printf('<span class="bold">%s</span> %s',
											esc_html( 'Getting Started:' ),
											esc_html( 'Using Mesh with your Content' ) ); ?>
                    </h2>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="wrapper">
                <div class="fifty">
                    <h3><?php esc_html_e('A quick 2 minute primer', 'mesh' ); ?></h3>
                    <p class="steps"><strong><?php esc_html_e('Enable:', 'mesh' ) ;?></strong>
                        <?php printf( __( 'By default Mesh is only enabled Pages. If you you would like to enable mesh for other post types (including blog posts) head over to the <a href="%s">%s</a>.', 'mesh'),
	                       admin_url( '/options-general.php?page=mesh&tab=settings' ),
	                       esc_html__( 'settings', 'mesh' ) ); ?>
                    </p>

                    <p><?php esc_html_e('Visit an existing page, post, custom post type or create a new one where Mesh has been enabled.', 'mesh' ); ?></p>

                    <p><?php esc_html_e( 'You will now see a new area below "The Editor".', 'Mesh' ); ?></p>

                    <img src="<?php echo ( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/add-new-section.png'); ?>">
                    <div class="clearfix"></div>

                    <h3 class="steps"><?php esc_html_e( 'Follow the tool tips', 'mesh' ); ?></h3>
                    <p><?php esc_html_e( 'The first time you create sections and blocks in mesh you will be presented with useful tool tips that will guide you along the way.', 'mesh' ); ?></p>
                </div>

                <div class="fifty right">
                    <img src="<?php echo ( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/mesh-admin-comp2.gif'); ?>" alt="Enable Mesh" width="90%"/>
                </div>

                <div class="clearfix"></div>
            </div>

            <div class="gray-bg negative-bg">
                <div class="wrapper">
                    <h2 class="color-darkpurple light-weight"><?php esc_html_e( 'More Quick Tips', 'mesh' ); ?></h2>
	                <div class="grey-box-container" data-equalizer="">
	                    <div class="grey-box fifty">
	                        <div class="padding-box" data-equalizer-watch="">
	                            <div class="about-box-icon">
	                                <img src="https://meshplugin.com/wp-content/uploads/2017/03/feature-easy-familiar-2.svg" />
	                            </div>
	                            <div class="about-box-copy">
	                                <h4><?php esc_html_e( 'Familiar &amp;amp; Easy to Use', 'mesh' ); ?></h4>
	                                <p><?php esc_html_e( 'Create content using an interface similar to default pages and posts in WordPress.', 'mesh' ); ?></p>
	                            </div>
	                            <div class="clearfix"></div>
	                        </div>
	                    </div>

                        <div class="grey-box fifty" >
                            <div class="padding-box" data-equalizer-watch="">
                                <div class="about-box-icon">
                                    <img src="https://meshplugin.com/wp-content/uploads/2017/03/feature-visualize.svg" />
                                </div>
                                <div class="about-box-copy">
                                    <h4><?php esc_html_e( 'Layout Your Content', 'mesh' ); ?></h4>
                                    <p><?php esc_html_e('Easily build rows and columns of content without writing a line of code or editing templates.', 'mesh' ); ?></p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <div class="grey-box fifty" >
                            <div class="padding-box" data-equalizer-watch="">
                                <div class="about-box-icon">
                                    <img src="https://meshplugin.com/wp-content/uploads/2017/03/feature-responsive.svg" />
                                </div>
                                <div class="about-box-copy">
                                    <h4><?php esc_html_e( 'Fully Responsive', 'mesh' ); ?></h4>
                                    <p><?php esc_html_e( 'Rest easy knowing your content will display seamlessly across browsers and devices.', 'mesh' ); ?></p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <div class="grey-box fifty" >
                            <div class="padding-box" data-equalizer-watch="">
                                <div class="about-box-icon">
                                    <img src="https://meshplugin.com/wp-content/uploads/2017/03/feature-plays-well.svg" />
                                </div>
                                <div class="about-box-copy">
                                    <h4><?php esc_html_e( 'Plays Well with Others', 'mesh' ); ?></h4>
                                    <p>
										<?php printf( __( 'Continually updated with hooks and filters to extend functionality. For a full list, check out <a href="%s" target="_blank" rel="noopener">meshplugin.com</a>.', 'mesh'),
											esc_attr( 'https://meshplugin.com/knowledge-base' ) ); ?>
									</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="wrapper">
                <h2 class="color-darkpurple light-weight"><?php esc_html_e('About the team behind Mesh', 'mesh' ); ?></h2>
                <div class="about-devs-container grey-box">
                    <div class="padding-box">
                        <p><a href="https://linchpin.agency">Linchpin</a> is a Digital Agency that specializes in WordPress. Committed to contributing to the WordPress community, Linchpin has released several <a href="https://profiles.wordpress.org/linchpin_agency/">plugins</a> on WordPress.org. Linchpin is also an active member in their local WordPress communities, not only leading the <a href="https://www.meetup.com/WordPressRI/">WordPress Rhode Island Meetup</a> group for several years, but also organizing, volunteering, speaking at or sponsoring local WordCamp conferences in the greater New England area.</p>
                        <p> Check our our <a href="http://linchpin.agency">site</a>, connect with us or come say hi at a local event.</p>
                        <p class="no-margin"><a href="http://linchpin.agency">Linchpin.Agency</a>   |   <a href="https://jetpack.pro/profile/linchpin/">Jetpack.pro</a>   |   <a href="https://facebook.com/linchpinagency">Facebook</a>   |   <a href="https://twitter.com/linchpin_agency">Twitter</a>   |   <a href="https://www.instagram.com/linchpinagency/">Instagram</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>