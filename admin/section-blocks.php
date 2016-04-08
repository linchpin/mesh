<?php
/**
 * Editor template
 *
 * @package    Mesh
 * @subpackage AdminTemplates
 * @since      0.2.0
 */

?>
<div class="mesh-row">
	<?php include LINCHPIN_MESH___PLUGIN_DIR . 'admin/section-column-resize.php'; ?>

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

		$block_columns = get_post_meta( $blocks[ $block_increment ]->ID, '_mesh_column_width', true );

		/**
		 * Get how wide our column is. If no width is defined fall back to the default for that template.
		 * If no blocks are defined fall back to a 12 column.
		 */
		if ( empty( $block_columns ) || 1 === $templates[ $selected_template ]['blocks'] ) {
			$block_columns = $default_block_columns;
		}
		$block_css_class = get_post_meta( $blocks[ $block_increment ]->ID, '_mesh_css_class', true );
		$block_offset = get_post_meta( $blocks[ $block_increment ]->ID, '_mesh_offset', true );

		?>

		<div class="mesh-section-block mesh-columns-<?php esc_attr_e( $block_columns ); ?> columns" data-mesh-block-id="<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>">
			<div class="drop-target">
				<div class="block" id="mesh-block-editor-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>" data-mesh-block-id="<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>">
					<div class="block-header">
						<div class="mesh-row mesh-row-title mesh-block-title-row">
							<?php if ( 1 == $section_blocks ) : ?>
								<div class="mesh-columns-6">
									<div class="msc-clean-edit">
										<input id="<?php esc_attr_e( 'mesh-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-title]' ); ?>" type="text" class="mesh-column-title msc-clean-edit-element widefat left" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][post_title]" value="<?php esc_attr_e( $blocks[ $block_increment ]->post_title ); ?>"/>
										<span class="close-title-edit left"><?php esc_html_e( 'Done', 'linchpin-mesh' ); ?></span>
										<span class="handle-title"><?php esc_attr_e( $blocks[ $block_increment ]->post_title ); ?></span>
									</div>
								</div>

								<div class="mesh-columns-6 text-right">
									<label for="<?php esc_attr_e( 'mesh-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-offset]' ); ?>"><?php esc_html_e( 'Offset:', 'linchpin-mesh' ); ?></label>
									<select id="<?php esc_attr_e( 'mesh-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-offset]' ); ?>" class="mesh-column-offset msc-clean-edit-element" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][offset]">
										<?php for ( $i = 0; $i <= $offsets_available; $i++ ) : ?>
											<option value="<?php echo $i; ?>"<?php if ( $i == $block_offset ) { echo ' selected'; } ?>><?php echo $i; ?></option>
										<?php endfor; ?>
									</select>

									<label for="mesh-sections-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>-css-class">
										<?php esc_html_e( 'CSS Class', 'linchpin-mesh' ); ?> <input type="text" id="mesh-sections-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>-css-class" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][css_class]" class="mesh-block-css-class" value="<?php esc_attr_e( $block_css_class ); ?>" />
									</label>
								</div>
							<?php else : ?>
								<div class="mesh-columns-12">
									<span class="the-mover hndle ui-sortable-handle left"><span></span></span>
									<div class="msc-clean-edit left">
										<input id="<?php esc_attr_e( 'mesh-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-title]' ); ?>" type="text" class="mesh-column-title msc-clean-edit-element widefat left" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][post_title]" value="<?php esc_attr_e( $blocks[ $block_increment ]->post_title ); ?>"/>
										<span class="close-title-edit left"><?php esc_html_e( 'Done', 'linchpin-mesh' ); ?></span>
										<span class="handle-title"><?php esc_attr_e( $blocks[ $block_increment ]->post_title ); ?></span>
									</div>
								</div>

								<div class="mesh-columns-12">
									<?php if ( 4 !== $section_blocks ) : ?>
										<label for="<?php esc_attr_e( 'mesh-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-offset]' ); ?>"><?php esc_html_e( 'Offset:', 'linchpin-mesh' ); ?></label>
										<select id="<?php esc_attr_e( 'mesh-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-offset]' ); ?>" class="mesh-column-offset" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][offset]">
											<?php for ( $i = 0; $i <= $offsets_available; $i++ ) : ?>
												<option value="<?php esc_attr_e( $i ); ?>"<?php if ( $i === $block_offset ) { esc_attr_e( ' selected' ); } ?>><?php esc_html_e( $i ); ?></option>
											<?php endfor; ?>
										</select>
									<?php endif; ?>

									<label for="mesh-sections-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>-css-class">
										<?php esc_html_e( 'CSS Class', 'linchpin-mesh' ); ?> <input type="text" id="mesh-sections-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>-css-class" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][css_class]" value="<?php esc_attr_e( $block_css_class ); ?>" />
									</label>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<div class="block-content<?php if ( 4 !== $section_blocks && $block_offset ) { esc_attr_e( ' mesh-has-offset mesh-offset-' . $block_offset ); } ?>">
						<?php
						wp_editor( apply_filters( 'content_edit_pre', $blocks[ $block_increment ]->post_content ), 'mesh-section-editor-' . $blocks[ $block_increment ]->ID, array(
							'textarea_name' => 'mesh-sections[' . $section->ID . '][blocks][' . $blocks[ $block_increment ]->ID . '][post_content]',
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

					<div class="block-background-container text-right mesh-columns-12 mesh-section-background">
						<div class="choose-image">
							<?php $featured_image_id = get_post_thumbnail_id( $blocks[ $block_increment ]->ID );

							if ( empty( $featured_image_id ) ) : ?>
								<a class="mesh-block-featured-image-choose"><?php esc_attr_e( 'Set Background Image', 'linchpin-mce' ); ?></a>
							<?php else : ?>
								<?php $featured_image = wp_get_attachment_image_src( $featured_image_id, array( 160, 60 ) ); ?>

								<a class="mesh-block-featured-image-choose right" data-mesh-block-featured-image="<?php esc_attr_e( $featured_image_id ); ?>"><img src="<?php esc_attr_e( $featured_image[0] ); ?>" /></a>
								<a class="mesh-block-featured-image-trash dashicons-before dashicons-dismiss" data-mesh-block-featured-image="<?php esc_attr_e( $featured_image_id ); ?>"></a>
							<?php endif; ?>
						</div>
					</div>
					<input type="hidden" class="column-width" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][columns]" value="<?php esc_attr_e( $block_columns ); ?>"/>
				</div>
			</div>
		</div>
	<?php $block_increment++;
	endwhile; ?>
</div>
