<?php
/**
 * Editor template
 *
 * @package    Mesh
 * @subpackage AdminTemplates
 * @since      0.2.0
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}
?>
<div class="mesh-row">

	<?php

	global $post;

	$reference_template = has_term( 'reference', 'mesh_template_types', $post );

	if ( ! $reference_template ) {
		include LINCHPIN_MESH___PLUGIN_DIR . 'admin/section-column-resize.php';
	}

	// If the template doesn't have any blocks make sure it has 1.
	if ( ! $section_blocks = (int) $templates[ $selected_template ]['blocks'] ) {
		$section_blocks = 1;
	}

	$offsets_available = 9;

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
		$block_offset = (int) get_post_meta( $blocks[ $block_increment ]->ID, '_mesh_offset', true );

		?>
		<div class="mesh-section-block mesh-columns-<?php esc_attr_e( $block_columns ); ?> columns" data-mesh-block-id="<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>">
			<div class="drop-target">
				<div class="block" id="mesh-block-editor-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>" data-mesh-block-id="<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>">
					<div class="block-header">
						<div class="mesh-row mesh-row-title mesh-block-title-row">
							<?php if ( 1 === $section_blocks ) : ?>
								<div class="mesh-columns-6">
									<div class="mesh-clean-edit">
										<input id="<?php esc_attr_e( 'mesh-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-title]' ); ?>" type="text" class="mesh-column-title mesh-clean-edit-element widefat left" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][post_title]" value="<?php esc_attr_e( $blocks[ $block_increment ]->post_title ); ?>"/>
										<span class="close-title-edit left"><?php esc_html_e( 'Done', 'mesh' ); ?></span>
										<span class="handle-title mesh-section-title-text mesh-column-title-text"><?php esc_attr_e( $blocks[ $block_increment ]->post_title ); ?></span>
									</div>
								</div>

								<div class="mesh-columns-6 text-right">
									<?php if ( ! $reference_template ) : ?>
									<label for="<?php esc_attr_e( 'mesh-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-offset' ); ?>"><?php esc_html_e( 'Offset:', 'mesh' ); ?></label>
									<select id="<?php esc_attr_e( 'mesh-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-offset' ); ?>" class="mesh-column-offset mesh-clean-edit-element" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][offset]">
										<?php for ( $i = 0; $i <= $offsets_available; $i++ ) : ?>
											<option value="<?php esc_attr_e( $i ); ?>"<?php if ( $i === $block_offset ) : ?> selected<?php endif; ?>><?php esc_attr_e( $i ); ?></option>
										<?php endfor; ?>
									</select>
									<?php endif; ?>
									<label for="mesh-sections-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>-css-class" class="nowrap">
										<?php esc_html_e( 'CSS Class', 'mesh' ); ?> <input type="text" id="mesh-sections-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>-css-class" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][css_class]" class="mesh-block-css-class" value="<?php esc_attr_e( $block_css_class ); ?>" />
									</label>
								</div>
							<?php else : ?>
								<?php
								$offsets_available = $block_columns - 3;

								if ( $block_offset > $offsets_available ) {
									$block_offset = 0;
								}
								?>

								<div class="mesh-columns-12 mesh-block-options-toggle-container">
									<span class="the-mover hndle ui-sortable-handle left mesh-hide-for-small"><span></span></span>
									<div class="mesh-clean-edit left mesh-column-title-container">
										<input id="<?php esc_attr_e( 'mesh-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-title' ); ?>" type="text" class="mesh-column-title mesh-clean-edit-element widefat left" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][post_title]" value="<?php esc_attr_e( $blocks[ $block_increment ]->post_title ); ?>"/>
										<span class="close-title-edit left"><?php esc_html_e( 'Done', 'mesh' ); ?></span>
										<span class="handle-title mesh-section-title-text mesh-column-title-text"><?php esc_attr_e( $blocks[ $block_increment ]->post_title ); ?></span>
									</div>

									<a href="#" class="slide-toggle-element mesh-more-section-options right slide-toggle-meta-dropdown mesh-hide-for-small" data-toggle=".mesh-block-meta-dropdown-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>"><?php esc_html_e( 'More', 'mesh' ); ?></a>
								</div>

								<div class="mesh-columns-12 mesh-block-meta-dropdown mesh-block-meta-dropdown-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?> hide">
									<div class="left mesh-columns-8">
										<?php if ( ! $reference_template ) : ?>
                                            <?php if ( 4 !== $section_blocks ) : ?>
                                                <label for="<?php esc_attr_e( 'mesh-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-offset' ); ?>"><?php esc_html_e( 'Offset:', 'mesh' ); ?></label>
                                                <select id="<?php esc_attr_e( 'mesh-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-offset' ); ?>" class="mesh-column-offset" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][offset]">
                                                    <?php for ( $i = 0; $i <= $offsets_available; $i++ ) : ?>
                                                        <option value="<?php esc_attr_e( $i ); ?>"<?php if ( (int) $i === (int) $block_offset ) { esc_attr_e( ' selected' ); } ?>><?php esc_html_e( $i ); ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            <?php endif; ?>
										<?php endif; ?>

										<label for="mesh-sections-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>-css-class" class="nowrap">
											<?php esc_html_e( 'CSS Class', 'mesh' ); ?> <input type="text" id="mesh-sections-<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>-css-class" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][css_class]" value="<?php esc_attr_e( $block_css_class ); ?>" />
										</label>
									</div>

									<div class="block-background-container right text-right mesh-columns-4 mesh-section-background">
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
								</div>
							<?php endif; ?>
						</div>
					</div>

					<div class="block-content<?php if ( 4 !== $section_blocks && $block_offset ) { esc_attr_e( ' mesh-has-offset mesh-offset-' . $block_offset ); } ?>">
						<?php

						$tiny_mce_options = array(
							'resize'                => false,
							'wordpress_adv_hidden'  => true,
							'add_unload_trigger'    => false,
							'statusbar'             => true,
							'autoresize_min_height' => 150,
							'wp_autoresize_on'      => false,
							'wpautop'               => true,
							'plugins'               => 'lists,media,paste,tabfocus,wordpress,textcolor,wpautoresize,wpeditimage,wpgallery,wplink,wptextpattern,wpview',
							'toolbar1'              => 'bold,italic,bullist,numlist,hr,alignleft,aligncenter,alignright,alignjustify,link,wp_adv',
							'toolbar2'              => 'formatselect,underline,strikethrough,forecolor,pastetext,removeformat',
						);

						$tiny_mce_options = apply_filters( 'mesh_tiny_mce_options', $tiny_mce_options );

						wp_editor( apply_filters( 'content_edit_pre', $blocks[ $block_increment ]->post_content ), 'mesh-section-editor-' . $blocks[ $block_increment ]->ID, array(
							'textarea_name' => 'mesh-sections[' . $section->ID . '][blocks][' . $blocks[ $block_increment ]->ID . '][post_content]',
							'teeny' => true,
							'tinymce' => $tiny_mce_options,
							'quicktags' => array(
								'buttons' => 'strong,em,link,block,img,ul,ol,li',
							),
						) );
						?>
					</div>

					<?php

					$revisions = wp_get_post_revisions( $blocks[ $block_increment ]->ID );

					if ( ! empty( $revisions ) ) : ?>
						<div class="misc-pub-section misc-pub-revisions">
							<?php esc_attr_e( 'Revisions: ', 'mesh' ); ?>
							<a class="hide-if-no-js" href="<?php echo esc_url( get_edit_post_link( reset( $revisions )->ID ) ); ?>"><b><?php echo number_format_i18n( count( $revisions ) ); ?></b> <span class="screen-reader-text"><?php esc_attr_e( 'Browse revisions' ); ?></span></a>
						</div>
					<?php endif; ?>

					<input type="hidden" class="column-width" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][columns]" value="<?php esc_attr_e( $block_columns ); ?>"/>

					<input type="hidden" class="block-menu-order" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[ $block_increment ]->ID ); ?>][menu_order]" value="<?php esc_attr_e( $blocks[ $block_increment ]->menu_order ); ?>"/>
				</div>
			</div>
		</div>
	<?php $block_increment++;
	endwhile; ?>
</div>
