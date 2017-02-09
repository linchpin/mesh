<?php
/**
 * Handle displaying controls within sections and blocks
 *
 * @since 1.2
 */

/**
 * Class Mesh_Controls
 */
class Mesh_Controls {

	/**
	 * Only show equalize when the count of our blocks is greater than 1
	 *
	 * @param $section
	 * @param $blocks
	 *
	 * @return bool
	 */
	function show_equalize( $section, $blocks ) {
		return ( 1 === ( count( $blocks ) ) );
	}

	/**
	 * Only show push pull controls when we have 2 items.
	 *
	 * @param $section
	 * @param $blocks
	 *
	 * @since 1.2
	 *
	 * @return bool
	 */
	function show_push_pull( $section, $blocks ) {
		return ( 2 === count( $blocks ) );
	}

	/**
	 * Display all of our Mesh Section Controls
	 *
	 * @param object  $section Current Section
	 * @param array   $blocks Our Sections Current Block
	 *
	 * @since 1.2
	 */
	function mesh_section_controls( $section, $blocks ) {

		$controls = array(
			'css-class' => array(
				'label'	         => __( 'CSS Class(es)', 'mesh' ),
				'type'           => 'text',
				'css_classes'    => array( 'mesh-section-class' ),
				'show_on_cb'     => false,
				'validation_cb'  => false,
			),
			'collapse' => array(
				'label'          => __( 'Collapse Padding', 'mesh' ),
				'type'           => 'checkbox',
				'css_classes'    => array( 'mesh-section-collapse-input' ),
				'show_on_cb'     => false,
				'validation_cb'  => false,
			),
			'push-pull' => array(
				'label'          => __( 'Push Pull', 'mesh' ),
				'type'           => 'checkbox',
				'css_classes'    => array( 'mesh-section-push' ),
				'show_on_cb'     => array( $this, 'show_push_pull' ),
				'validation_cb'  => false,
			),
			'lp-equal' => array(
				'label'          => __( 'Equalize', 'mesh' ),
				'type'           => 'checkbox',
				'css_classes'    => 'mesh-section-equalize',
				'show_on_cb'     => array( $this, 'show_equalize' ),
				'validate_cb'    => false,
			),
		);

		$controls = apply_filters( 'mesh_section_controls', $controls );

		foreach( $controls as $key => $control ) {

			$display_control = true;

			if ( ! empty( $controls['show_on_cb'] ) && is_callable( $controls['show_on_cb'] ) ) {
				$display_control = call_user_func_array( $controls['show_on_cb'], array( $section, $blocks ) );
			}

			$css_classes = array();

			if ( $display_control ) : ?>
				<?php
				if( ! empty( $control['css_classes'] ) && is_array( $control['css_classes'] ) ) {
					$css_classes = array_map( 'sanitize_html_class', $control['css_classes'] );
				} else {
					$css_classes = array( sanitize_html_class( $control['css_classes'] ) );
				}

				$css_classes = implode( ' ', $css_classes );

				$underscore_key = str_replace( '-', '_', $key );
				?>
				<li class="mesh-section-control-<?php esc_attr_e( $key ); ?>">
					<label for="mesh-section[<?php esc_attr_e( $section->ID ); ?>][<?php esc_attr_e( $underscore_key ); ?>]">
						<?php esc_html_e( $control['label'] ); ?>
						<?php
						switch( $control['type'] ) {
							case 'checkbox' : ?>
								<input type="checkbox" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][<?php esc_attr_e( $underscore_key ); ?>]" class="<?php esc_attr_e( $css_classes ); ?>" value="1" <?php if ( get_post_meta( $section->ID, '_mesh_' . esc_attr( $underscore_key ), true ) ) : ?>checked<?php endif; ?> />
								<?php
								break;
							case 'input' :
							case 'text' :
							default : ?>
								<input type="text" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][<?php esc_attr_e( $underscore_key ); ?>]" class="<?php esc_attr_e( $css_classes ); ?>" value="<?php esc_attr_e( get_post_meta( $section->ID, '_mesh_' . esc_attr( $underscore_key ), true ) ); ?>" />
								<?php
								break;
						}
						?>
					</label>
				</li>
				<?php
			endif;
		}
	}
}

/**
 * @param int  $post_id
 * @param bool $echo
 * @since 1.2
 *
 * @return string
 */
function mesh_section_attributes( $post_id = 0, $echo = true ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id  = $post->ID;
	}

	/**
	 * Process Section Meta
	 */

	$default_section_meta = array(
		'_mesh_css_class',
		'_mesh_lp_equal',
		'_mesh_title_display',
		'_mesh_push_pull',
		'_mesh_collapse',
		'_mesh_blocks',
		'post_title',
		'post_status',
		'_mesh_template',
		'template_original',
		'menu_order'
	);

	/**
	 * This filter is used to remove or add elements to the default section meta
	 * @todo "meta" related to a section
	 */
	$default_section_meta = apply_filters( 'mesh_default_section_meta_fields', $default_section_meta );

	$section_data = get_post_meta( $post_id, '' );

	$attributes = array();

	// Process our custom meta
	foreach( $section_data as $data_key => $data_field ) {
		// Do not process default keys
		if( in_array( $data_key, $default_section_meta ) ) {
			continue;
		}

		$lowercase_data_key = str_replace( '_mesh_', '', $data_key );

		if( ! empty( $data_field ) ) {
			$attributes[ 'data-' . $lowercase_data_key ] = $data_field[0];
		}
	}

	if ( empty( $attributes ) ) {
		return '';
	} else {
		if ( false === $echo ) {
			return $attributes;
		} else {

			$attributes = join(' ', array_map( function( $data_key ) use ( $attributes )
				{
					if( is_bool( $attributes[ $data_key ] ) ) {
						return $attributes[ $data_key ] ? $data_key : '';
					}
					return $data_key . '="' . $attributes[ $data_key ] . '"';
				}, array_keys( $attributes ) ) );

			echo $attributes; // WPCS: XSS ok.
		}
	}

	return $attributes;
}

/**
 * Public functions to call classes
 *
 * @param $section
 * @param $blocks
 */
function mesh_section_controls( $section, $blocks ) {

	$mesh_controls = new Mesh_Controls();

	$mesh_controls->mesh_section_controls( $section, $blocks );
}

function mesh_block_controls( $block ) {
	$mesh_controls = new Mesh_Controls();
	$mesh_controls->mesh_block_controls( $block );
}