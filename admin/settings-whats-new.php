<?php
/**
 * Created by PhpStorm.
 * User: smalloy
 * Date: 7/27/17
 * Time: 12:22 PM
 */

?>

<div id="whats-new">
    <div id="post-body" class="metabox-holder">
        <div id="postbox-container" class="postbox-container">
            <div class="about-hero negative-bg" style="background-image: url('<?php echo ( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/meshgreenbackground@2x.png'); ?>');">
                <div class="hero-text">
                    <h1><?php esc_html_e('Updates, Bug Fixes & New Releases', 'mesh' ); ?></h1>
                </div>
            </div>
            <div class="gray-bg negative-bg">
                <div class="wrapper">
                    <h2 class="light-weight"><?php printf( 'Mesh Version %s | Released %s', esc_html( get_option('mesh_version') ), __( 'Aug 1, 2017' ) ); ?></h2>
                </div>
            </div>
            <div class="wrapper">
                <div class="new-feature-container">

                    <div class="new-title">
                        <img class="new-img" src="<?php echo ( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/icon-new-feature.svg') ?>" alt="New Features"/>
                        <h3 class="new-tag"><?php esc_html_e('New Features', 'mesh' ); ?></h3>
                    </div>

                    <div class="new new-feature">
	                    <div class="fifty right">
		                    <img src="<?php echo ( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/yoast-readability-scoring.png') ?>" alt="<?php esc_attr_e('Yoast Readability Scoring', 'mesh' ); ?>"/>
	                    </div>
                        <h4><?php esc_html_e( 'Better Yoast Integration', 'mesh' ); ?></h4>
                        <p><?php esc_html_e('We\'ve added support for Yoast SEO page analysis tool. Now any time any new Sections and/or Content Blocks are created using Mesh they will be taken into account within Yoast\'s page Readability analysis tools' , 'mesh' ); ?></p>
                    </div>

                    <div class="new new-feature">
                        <h4><?php esc_html_e( 'Multiple Duplicate Post Plugins are now supported', 'mesh' ); ?></h4>
                        <p><?php esc_html_e('With every release of Mesh we want to make sure it is more of a benefit to our users.','mesh' ); ?>
	                        <?php esc_html_e('Mesh will now duplicate all Sections and Blocks when duplicating a page/post/custom posttypes using the following popular plugins.', 'mesh' );?></p>
	                        <ul>
		                        <li><a href="https://wordpress.org/plugins/duplicate-post/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Duplicate Post', 'mesh' ); ?></a></li>
		                        <li><a href="https://wordpress.org/plugins/post-duplicator/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Post Duplicator', 'mesh' ); ?></a></li>
	                        </ul>
                    </div>

                    <div class="new new-feature">
                        <h4><?php esc_html_e( 'More Customization for Developers', 'mesh' ); ?></h4>
                        <p><?php esc_html_e( 'The fields within Mesh sections and blocks (in the "More Options" and "More" areas, respectively) are now extendible by developers. This means developers can add their own fields easily. This also lays the groundwork for us to be able to add more great features to Mesh!','mesh' ); ?></p>
                        <p><?php esc_html_e('Two new filters were added, "mesh_section_controls" and "mesh_block_controls," which pass the controls as an array of fields. By hooking into this filter, you can add checkboxes, select/dropdowns, and text fields. More field types will be released in the future, allowing you to extend Mesh further.', 'mesh' ); ?></p>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="update-container fifty">

                    <div class="new-title">
                        <img src="<?php echo ( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/icon-updates.svg') ?>" alt="Updates"/>
                        <h3 class="new-tag"><?php esc_html_e( 'Updates', 'mesh' ); ?></h3>
                    </div>

                    <div class="new update">
                        <h4><?php esc_html_e( 'TinyMCE Urls', 'mesh' ); ?></h4>
                        <p><?php esc_html_e( 'We had some reports from users regarding issues using the keywork javascript within tags so we within TinyMCE', 'mesh' ); ?></p>
                    </div>

                </div>

                <div class="clearfix"></div>

                <div class="bug-fix-container">

                    <div class="new-title">
                        <img src="<?php echo ( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/icon-bug.svg') ?>" alt="Bugs"/>
                        <h3 class="new-tag"><?php esc_html_e( 'Bug Fixes', 'mesh' ); ?></h3>
	                    <h5><?php esc_html_e('While features are great, stability and bug fixes are equally important. Below are some of items we tackle this release', 'mesh' ); ?></h5>
                    </div>

                    <div class="new bug-fix">
                        <ul>
	                        <li><?php esc_html_e( 'Fixed a bug where reordering would stop that section from working properly until refresh.', 'mesh' ); ?></li>
	                        <li><?php esc_html_e( 'Fixed a bug where collapsed sections could not be toggled open after a new section was added', 'mesh' ); ?></li>
	                        <li><?php esc_html_e( 'Fixed a bug when excluding Mesh template related taxonomies from the generated sitemap', 'mesh' ); ?></li>
	                        <li><?php esc_html_e( 'Fixed a bug where section and block background images were displayed before "update" / "publish"', 'mesh' ); ?></li>
                        </ul>
                    </div>
                </div>


            <div class="clearfix"></div>

            <div class="grey-box-container" data-equalizer="">
                <div class="grey-box fifty">
                    <div class="padding-box" data-equalizer-watch="">
                        <h3 class="bold no-margin"><?php esc_html_e( 'Visit GitHub' ); ?></h3>
                        <p>The base Mesh Page Builder is fully open sourced on github. The team at <a href="https://linchpin.agency" target="_blank">Linchpin</a> actively manages and maintains the project. Take a look at the code, fork and submit pull requests or provide feedback/feature request right from github.</p>
                        <p class="no-margin"><a class="button no-margin" href="https://github.com/linchpin/mesh" target="_blank"> Help Us Build It Better </a></p>
                    </div>
                </div>

                <div class="grey-box fifty">
                    <div class="padding-box" data-equalizer-watch="">
                        <h3 class="bold no-margin"><?php esc_html_e( 'Tell Us What You Think' ); ?></h3>
                        <p><?php esc_html_e( 'How is Mesh working for you? We are always open to feedback and would love to hear about your experience with Mesh.' ); ?></p>
                        <p class="no-margin"><a class="button no-margin" href="https://wordpress.org/support/plugin/mesh/reviews/" target="_blank"> Review Mesh</a></p>
                    </div>
                </div>
            </div>

                <div class="grey-box">
                    <div class="padding-box">
                        <h3 class="bold inline"><?php esc_html_e( 'Follow Mesh!' ); ?></h3>
                        <h3 class="inline light-weight">Follow along and see what we're up to on <a href="https://twitter.com/linchpin_agency">Twitter</a>.</h3>
                    </div>
                </div>


            <div class="clearfix"></div>

            </div>

        </div>
    </div>
</div>