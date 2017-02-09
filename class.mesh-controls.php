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
				?>
				<li class="mesh-section-control-<?php esc_attr_e( $key ); ?>">
					<label for="mesh-section[<?php esc_attr_e( $section->ID ); ?>][<?php esc_attr_e( $key ); ?>]">
						<?php esc_html_e( $control['label'] ); ?>
						<?php
						switch( $control['type'] ) {
							case 'checkbox' : ?>
								<input type="checkbox" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][<?php esc_attr_e( $key ); ?>]" class="<?php esc_attr_e( $css_classes ); ?>" value="1" <?php if ( get_post_meta( $section->ID, '_mesh_' . esc_attr( str_replace( '-', '_', $key ) ), true ) ) : ?>checked<?php endif; ?> />
								<?php
								break;
							case 'input' :
							case 'text' :
							default : ?>
								<input type="text" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][<?php esc_attr_e( $key ); ?>]" class="<?php esc_attr_e( $css_classes ); ?>" value="<?php esc_attr( get_post_meta( $section->ID, '_mesh_' . esc_attr( str_replace( '-', '_', $key ) ), true ) ); ?>" />
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

function mesh_section_controls( $section, $blocks ) {

	$mesh_controls = new Mesh_Controls();

	$mesh_controls->mesh_section_controls( $section, $blocks );
}