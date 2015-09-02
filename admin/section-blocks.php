<?php
/**
 * Editor template
 *
 * @package MultipleContentSection
 * @subpackage AdminTemplates
 * @since 1.2.0
 */

?>
<div class="mcs-row">
	<?php
	// If the template doesn't have any blocks make sure it has 1.
	if ( ! $section_blocks = (int) $templates[ $selected_template ]['blocks'] ) {
		$section_blocks = 1;
	}

	$default_block_columns = 12 / $section_blocks;

	// Loop through the blocks needed for this template.
	$block_increment = 0;

	while ( $block_increment < $section_blocks ) :

		$block_columns = get_post_meta( $blocks[ $block_increment ]->ID, '_mcs_column_width', true );

		// Get how wide our column is. If no width is defined fall back to the default for that template. If no blocks are defined fall back to a 12 column
		if ( empty( $block_columns ) || 1 === $templates[ $selected_template ]['blocks'] ) {
			$block_columns = $default_block_columns;
		}

		$block_css_class = get_post_meta( $blocks[ $block_increment ]->ID, '_mcs_css_class', true );

		?>

		<div class="mcs-columns-<?php esc_attr_e( $block_columns ); ?> columns">
			<div class="drop-target">
				<div class="block" id="mcs-block-editor-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>"  data-mcs-block-id="<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>">
					<div class="block-header">
						<div class="mcs-row">
							<div class="mcs-columns-6">
								<?php esc_html_e( $blocks[ $block_increment ]->post_title ); ?> (<?php esc_html_e( $blocks[ $block_increment ]->ID ); ?>)
							</div>
							<div class="mcs-columns-6 text-right">
								<label for="mcs-sections-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>-css-class">
									<?php esc_html_e( 'Block CSS Class', 'linchpin-mcs' ); ?><input type="text" id="mcs-sections-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>-css-class" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][css_class]" value="<?php esc_attr_e( $block_css_class ); ?>" />
								</label>
							</div>
						</div>
					</div>
					<div class="block-content">
						<?php
						wp_editor( apply_filters( 'content_edit_pre', $blocks[ $block_increment ]->post_content ), 'mcs-section-editor-' . $blocks[ $block_increment ]->ID, array(
							'textarea_name' => 'mcs-sections[' . $section->ID . '][blocks][' . $blocks[ $block_increment ]->ID . '][post_content]',
							'teeny' => true,
							'tinymce'          => array(
								'resize'                => false,
								'wordpress_adv_hidden'  => false,
								'add_unload_trigger'    => false,
								'statusbar'             => false,
								'autoresize_min_height' => 150,
								'wp_autoresize_on'      => false,
								'plugins'               => 'lists,media,paste,tabfocus,fullscreen,wordpress,wpautoresize,wpeditimage,wpgallery,wplink,wptextpattern,wpview',
								'toolbar1'              => 'bold,italic,bullist,numlist,blockquote,link,unlink',
							),
							'quicktags' => array(
								'buttons' => 'strong,em,link,block,img,ul,ol,li',
							),
						) );
						?>
					</div>

					<input type="hidden" class="column-width" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][columns]" value="<?php esc_attr_e( $block_columns ); ?>"/>

				</div>
			</div>
		</div>
	<?php $block_increment++;
	endwhile; ?>
</div>
