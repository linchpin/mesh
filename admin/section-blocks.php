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

$mesh_controls  = new Mesh_Controls();
$block_settings = $mesh_controls->get_block_settings();

global $post;

$reference_template = has_term( 'reference', 'mesh_template_types', $post );

// If the template doesn't have any blocks make sure it has 1.
if ( ! $section_blocks = (int) $templates[ $selected_template ]['blocks'] ) {
	$section_blocks              = 1;
	$multiple_child_blocks_class = '';
} else {
	$multiple_child_blocks_class = ' multiple-blocks';
}

if ( 1 === $section_blocks ) {
	$multiple_child_blocks_class = '';
} else {
	$multiple_child_blocks_class = ' multiple-blocks';
}

$offsets_available     = 9;
$default_block_columns = $block_settings['max_columns'] / $section_blocks;
$block_increment       = 0; // Loop through the blocks needed for this template.
$remaining_columns     = $block_settings['max_columns'];

?>
<div class="mesh-row<?php echo esc_attr( $multiple_child_blocks_class ); ?>" data-section-blocks="<?php echo esc_attr( $section_blocks ); ?>">

	<?php

	if ( ! $reference_template ) {
		include LINCHPIN_MESH___PLUGIN_DIR . 'admin/section-column-resize.php';
	}

	while ( $block_increment < $section_blocks ) :

		if ( empty( $blocks[ $block_increment ] ) ) {
			continue;
		}

		$block_columns = get_post_meta( $blocks[ $block_increment ]->ID, '_mesh_column_width', true );

		/**
		 * Get how wide our column is. If no width is defined fall back to the default for that template.
		 * If no blocks are defined fall back to a 12 column.
		 */
		if ( empty( $block_columns ) ) {
			$block_columns = $default_block_columns;
		}

		if ( $block_columns > $remaining_columns ) {
			$block_columns = $remaining_columns;
		}

		$block_classes = array(
			'mesh-section-block',
			'columns',
			'mesh-columns-' . intval( $block_columns ),
		);

		$block_css_class = get_post_meta( $blocks[ $block_increment ]->ID, '_mesh_css_class', true );
		$block_offset    = (int) get_post_meta( $blocks[ $block_increment ]->ID, '_mesh_offset', true );
		$block_centered  = (bool) get_post_meta( $blocks[ $block_increment ]->ID, '_mesh_centered', true );

		if ( $block_centered && 1 === $section_blocks ) {
			$block_classes[] = 'mesh-block-centered';
		}

		if ( intval( $block_columns ) <= 4 ) {
			$block_classes[] = 'mesh-small-block';
		}

		?>
		<div class="<?php echo esc_attr( implode( ' ', $block_classes ) ); ?>" data-mesh-block-id="<?php echo esc_attr( $blocks[ $block_increment ]->ID ); ?>">
			<div class="drop-target">
				<div class="block" id="mesh-block-editor-<?php echo esc_attr( $blocks[ $block_increment ]->ID ); ?>" data-mesh-block-id="<?php echo esc_attr( $blocks[ $block_increment ]->ID ); ?>">
					<div class="block-header">
						<div class="mesh-row mesh-row-title mesh-block-title-row">
							<?php
							$offsets_available = $block_columns - 3;

							if ( $block_offset > $offsets_available ) {
								$block_offset = 0;
							}
							?>
							<div class="mesh-columns-12 mesh-block-options-toggle-container">
								<span class="the-mover hndle ui-sortable-handle left mesh-hide-for-small">
									<span></span>
								</span>
								<div class="mesh-clean-edit left mesh-column-title-container">
									<input id="<?php echo esc_attr( 'mesh-sections-' . $section->ID . '-' . $blocks[ $block_increment ]->ID . '-title' ); ?>" type="text" class="mesh-column-title mesh-clean-edit-element widefat left" name="mesh-sections[<?php echo esc_attr( $section->ID ); ?>][blocks][<?php echo esc_attr( $blocks[ $block_increment ]->ID ); ?>][post_title]" value="<?php echo esc_attr( Mesh_Common::get_section_title( $blocks[ $block_increment ] ) ); ?>"/>
									<span class="close-title-edit left"><?php esc_html_e( 'Done', 'mesh' ); ?></span>
									<span class="handle-title mesh-section-title-text mesh-column-title-text"><?php echo esc_attr( Mesh_Common::get_section_title( $blocks[ $block_increment ] ) ); ?></span>
								</div>

								<a href="#" class="slide-toggle-element mesh-more-section-options right slide-toggle-meta-dropdown mesh-hide-for-small" data-toggle=".mesh-block-meta-dropdown-<?php echo esc_attr( $blocks[ $block_increment ]->ID ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'More', 'mesh' ); ?></span></a>
							</div>

							<div class="mesh-columns-12 mesh-block-meta-dropdown mesh-block-meta-dropdown-<?php echo esc_attr( $blocks[ $block_increment ]->ID ); ?> hide">
								<div class="left mesh-columns-9">
									<?php mesh_block_controls( $blocks[ $block_increment ], $section_blocks ); ?>
								</div>
								<div class="mesh-columns-3 mesh-table mesh-background">
									<div class="mesh-row mesh-table-footer">
										<?php
										if ( ! has_term( 'reference', 'mesh_template_types', $post ) ) :

											$featured_image_id        = get_post_thumbnail_id( $blocks[ $block_increment ]->ID );
											$section_background_class = 'mesh-section-background';
											$section_background_class = ( ! empty( $featured_image_id ) ) ? $section_background_class . ' has-background-set' : $section_background_class;
											?>

											<div class="<?php echo esc_attr( $section_background_class ); ?>">
												<div class="choose-image">
													<?php if ( empty( $featured_image_id ) ) : ?>
														<a class="mesh-block-featured-image-choose"><?php esc_attr_e( 'Set Background Image', 'mesh' ); ?></a>
													<?php else : ?>
														<?php
														$featured_image = wp_get_attachment_image_src( $featured_image_id, array( 160, 60 ) );
														?>
														<a class="mesh-block-featured-image-choose right" data-mesh-featured-image="<?php echo esc_attr( $featured_image_id ); ?>">
															<img src="<?php echo esc_attr( $featured_image[0] ); ?>"/></a>
														<a class="mesh-block-featured-image-trash dashicons-before dashicons-dismiss" data-mesh-featured-image="<?php echo esc_attr( $featured_image_id ); ?>"></a>
													<?php endif; ?>
													<input type="hidden" class="mesh-block-background-input" name="mesh-sections[<?php echo esc_attr( $section->ID ); ?>][blocks][<?php echo esc_attr( $blocks[ $block_increment ]->ID ); ?>][featured_image]" value="<?php echo esc_attr( $featured_image_id ); ?>"/>
												</div>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>

					<?php
					$block_content_classes = array(
						'block-content',
					);

					if ( 4 !== $section_blocks && $block_offset ) {
						$block_content_classes[] = 'mesh-has-offset';
						$block_content_classes[] = 'mesh-offset-' . $block_offset;
					}
					?>
					<div class="<?php echo esc_attr( implode( ' ', $block_content_classes ) ); ?>">
						<?php

						$tiny_mce_options = mesh_get_tinymce_defaults();

						if ( intval( $block_columns ) <= 4 ) {
							$tiny_mce_options['toolbar1'] = $tiny_mce_options['small_toolbar1'];
							$tiny_mce_options['toolbar2'] = $tiny_mce_options['small_toolbar2'];
						}

						wp_editor( apply_filters( 'content_edit_pre', $blocks[ $block_increment ]->post_content ), 'mesh-section-editor-' . $blocks[ $block_increment ]->ID, array(
							'textarea_name' => 'mesh-sections[' . $section->ID . '][blocks][' . $blocks[ $block_increment ]->ID . '][post_content]',
							'teeny'         => true,
							'tinymce'       => $tiny_mce_options,
							'editor_class'  => 'mesh-wp-editor-area',
							'quicktags'     => array(
								'buttons' => 'strong,em,link,block,img,ul,ol,li',
							),
						) );
						?>
					</div>

					<?php

					$revisions = wp_get_post_revisions( $blocks[ $block_increment ]->ID );

					if ( ! empty( $revisions ) ) :
					?>
						<div class="misc-pub-section misc-pub-revisions">
							<?php esc_attr_e( 'Revisions: ', 'mesh' ); ?>
							<a class="hide-if-no-js" href="<?php echo esc_url( get_edit_post_link( reset( $revisions )->ID ) ); ?>"><b><?php echo esc_html( number_format_i18n( count( $revisions ) ) ); ?></b> <span class="screen-reader-text"><?php esc_html_e( 'Browse revisions', 'mesh' ); ?></span></a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php
		$block_increment++;
	endwhile;
	?>
</div>
