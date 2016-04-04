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

	$offsets_available = 6;

	if ( 2 == $section_blocks ) {
		$offsets_available = 3;
	}

	if ( 3 == $section_blocks ) {
		$offsets_available = 2;
	}

	$default_block_columns = 12 / $section_blocks;

	// Loop through the blocks needed for this template.
	$block_increment = 0;

	while ( $block_increment < $section_blocks ) :

		if ( empty( $blocks[ $block_increment ] ) ) {
			continue;
		}

		$block_columns = get_post_meta( $blocks[ $block_increment ]->ID, '_mcs_column_width', true );

		/**
		 * Get how wide our column is. If no width is defined fall back to the default for that template.
		 * If no blocks are defined fall back to a 12 column.
		 */
		if ( empty( $block_columns ) || 1 === $templates[ $selected_template ]['blocks'] ) {
			$block_columns = $default_block_columns;
		}
		$block_css_class = get_post_meta( $blocks[ $block_increment ]->ID, '_mcs_css_class', true );
		$block_offset = get_post_meta( $blocks[ $block_increment ]->ID, '_mcs_offset', true );

		?>

		<div class="mcs-columns-<?php esc_attr_e( $block_columns ); ?> columns">
			<div class="drop-target">
				<div class="block" id="mcs-block-editor-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>" data-mcs-block-id="<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>">
					<div class="block-header">
						<div class="mcs-row mcs-row-title mcs-block-title-row">
							<?php if ( 1 == $section_blocks ) : ?>
								<div class="mcs-columns-6">
									<div class="msc-clean-edit">
										<input id="<?php esc_attr_e( 'mcs-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-title]' ); ?>" type="text" class="mcs-column-title msc-clean-edit-element widefat left" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][post_title]" value="<?php esc_attr_e( $blocks[ $block_increment ]->post_title ); ?>"/>
										<span class="close-title-edit left"><?php _e( 'Done', 'linchpin-mcs' ); ?></span>
										<span class="handle-title"><?php esc_attr_e( $blocks[ $block_increment ]->post_title ); ?></span>
									</div>
								</div>

								<div class="mcs-columns-6 text-right">
									<label for="<?php esc_attr_e( 'mcs-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-offset]' ); ?>"><?php esc_html_e( 'Offset:', 'linchpin-mcs' ); ?></label>
									<select id="<?php esc_attr_e( 'mcs-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-offset]' ); ?>" class="mcs-column-offset msc-clean-edit-element" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][offset]">
										<?php for ( $i = 0; $i <= $offsets_available; $i++ ) : ?>
											<option value="<?php echo $i; ?>"<?php if ( $i == $block_offset ) { echo ' selected'; } ?>><?php echo $i; ?></option>
										<?php endfor; ?>
									</select>

									<label for="mcs-sections-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>-css-class">
										<?php esc_html_e( 'CSS Class', 'linchpin-mcs' ); ?> <input type="text" id="mcs-sections-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>-css-class" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][css_class]" value="<?php esc_attr_e( $block_css_class ); ?>" />
									</label>
								</div>
							<?php else : ?>
								<div class="mcs-columns-12">
									<span class="the-mover hndle ui-sortable-handle left"><span></span></span>
									<div class="msc-clean-edit left">
										<input id="<?php esc_attr_e( 'mcs-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-title]' ); ?>" type="text" class="mcs-column-title msc-clean-edit-element widefat left" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][post_title]" value="<?php esc_attr_e( $blocks[ $block_increment ]->post_title ); ?>"/>
										<span class="close-title-edit left"><?php _e( 'Done', 'linchpin-mcs' ); ?></span>
										<span class="handle-title"><?php esc_attr_e( $blocks[ $block_increment ]->post_title ); ?></span>
									</div>
								</div>

								<div class="mcs-columns-12">
									<?php if ( 4 != $section_blocks ) : ?>
										<label for="<?php esc_attr_e( 'mcs-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-offset]' ); ?>"><?php esc_html_e( 'Offset:', 'linchpin-mcs' ); ?></label>
										<select id="<?php esc_attr_e( 'mcs-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-offset]' ); ?>" class="mcs-column-offset" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][offset]">
											<?php for ( $i = 0; $i <= $offsets_available; $i++ ) : ?>
												<option value="<?php echo $i; ?>"<?php if ( $i == $block_offset ) { echo ' selected'; } ?>><?php echo $i; ?></option>
											<?php endfor; ?>
										</select>
									<?php endif; ?>

									<label for="mcs-sections-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>-css-class">
										<?php esc_html_e( 'CSS Class', 'linchpin-mcs' ); ?> <input type="text" id="mcs-sections-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>-css-class" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][css_class]" value="<?php esc_attr_e( $block_css_class ); ?>" />
									</label>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<div class="block-content<?php if ( 4 != $section_blocks && $block_offset ) { echo " mcs-has-offset mcs-offset-" . $block_offset; } ?>">
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

					<div class="block-background-container text-right mcs-columns-12 mcs-section-background">
						<div class="choose-image">
							<?php $featured_image_id = get_post_thumbnail_id( $blocks[ $block_increment ]->ID );

							if ( empty( $featured_image_id ) ) : ?>
								<a class="mcs-block-featured-image-choose"><?php esc_attr_e( 'Set Background Image', 'linchpin-mce' ); ?></a>
							<?php else : ?>
								<?php $featured_image = wp_get_attachment_image_src( $featured_image_id, array( 160, 60 ) ); ?>

								<a class="mcs-block-featured-image-choose right" data-mcs-block-featured-image="<?php esc_attr_e( $featured_image_id ); ?>"><img src="<?php echo $featured_image[0]; ?>" /></a>
								<a class="mcs-block-featured-image-trash dashicons-before dashicons-dismiss" data-mcs-block-featured-image="<?php esc_attr_e( $featured_image_id ); ?>"></a>
							<?php endif; ?>
						</div>
					</div>

					<input type="hidden" class="column-width" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][columns]" value="<?php esc_attr_e( $block_columns ); ?>"/>

				</div>
			</div>
		</div>
	<?php $block_increment++;
	endwhile; ?>
</div>
