<?php
/**
 * Handle various form inputs within the Mesh UI
 *
 * @package     Mesh
 * @subpackage  Input
 * @since       1.2.5
 */

/**
 * Class Mesh_Inputs
 */
class Mesh_Input {

	/**
	 * Define our standard set of inputs that we use within our controls.
	 * Anything not defined in this list will return an empty string.
	 *
	 * @var array
	 */
	public static $input_types = array(
		'text',
		'hidden',
		'checkbox',
		'multiple',
		'select',
		'dropdown',
		'media',
	);

	/**
	 * Return an input based on the passed type. This is a middle man
	 * method to allow for easier implementation/reuse.
	 * @since 1.2.5
	 *
	 * @param string $type
	 * @param array  $args  The arguments/customizations passed to the input
	 * @param mixed  $input_value The value passed to the input
	 * @param bool   $echo  Echo instead of returning
	 * @param array  $section Current section/block
	 * @param array  $blocks Array of child blocks.
	 *
	 * @return mixed
	 */
	public static function get_input( $type = 'text', $args, $input_value = '', $echo = true, $section = array(), $blocks = array() ) {

		// Make sure our input type is valid before we do anything.
		if ( ! in_array( $type, self::$input_types, true ) ) {
			return '';
		}

		$input_defaults = array(
			'post_parent'       => 0,
			'block_id'          => 0,
			'post_meta_key'     => '',
			'input_id'          => '',
			'input_type'        => 'text',
			'input_css_classes' => array(),
			'input_name_format' => 'mesh-sections[%d][blocks][%d][%s]',
			'input_name'        => '',
			'options_cb'        => '',
			'options'           => array(),
		);

		// Parse incoming $args into an array and merge it with $defaults.
		$args = wp_parse_args( $args, $input_defaults );

		if ( empty( $args['post_meta_key'] ) || empty( $args['block_id'] ) ) {
			return '';
		}

		if ( empty( $args['input_name'] ) ) {
			// Create the format for our input name
			$args['input_name'] = sprintf( $args['input_name_format'],
				esc_attr( $args['post_parent'] ),
				esc_attr( $args['block_id'] ),
				esc_attr( $args['post_meta_key'] )
			);
		}

		$args['input_id'] = '';

		if ( isset( $args['id'] ) ) {
			$args['input_id'] = sprintf( 'id="%s"',
				esc_attr( $args['id'] )
			);
		}

		if ( empty( $input_value ) ) {
			$input_value = get_post_meta( $args['block_id'], '_mesh_' . esc_attr( $args['post_meta_key'] ), true );
		}

		switch ( $type ) {
			case 'checkbox':
				self::get_input_checkbox( $args, $input_value, $echo );
			break;
			case 'select':
			case 'dropdown':
				self::get_input_select( $args, $input_value, $echo, $section, $blocks );
			break;
			case 'media':
				self::get_input_media( $args, $input_value, $echo );
			break;
			case 'hidden':
				self::get_input_hidden( $args, $input_value, $echo );
			break;
			case 'input':
			case 'text':
			default:
				self::get_input_text( $args, $input_value, $echo );
			break;
		}
	}

	/**
	 * Get row or column media.
	 *
	 * @todo this can be extended further for more customization.
	 * @since 1.2.5
	 *
	 * @param array $args
	 * @param mixed $input_value
	 * @param bool  $echo
	 *
	 * @return string
	 */
	public static function get_input_media( $args, $input_value = '', $echo = true ) {
		ob_start();

		$featured_image_id = get_post_thumbnail_id( intval( $args['block_id'] ) );
		$background_class  = 'mesh-section-background';
		$background_class  = ( ! empty( $featured_image_id ) ) ? $background_class . ' has-background-set' : $background_class;
		?>
		<div class="mesh-section-background <?php echo esc_attr( $background_class ); ?> ">
			<div class="choose-image">
				<?php
				if ( empty( $featured_image_id ) ) :
					$featured_image_id = '';
					?>
					<a class="mesh-block-featured-image-choose"><?php esc_attr_e( 'Set Background Image', 'mesh' ); ?></a>
				<?php else : ?>
					<?php $featured_image = wp_get_attachment_image_src( $featured_image_id, array( 160, 60 ) ); ?>
					<a class="mesh-block-featured-image-choose right" data-mesh-block-featured-image="<?php echo esc_attr( $featured_image_id ); ?>">
						<img src="<?php echo esc_attr( $featured_image[0] ); ?>" />
					</a>
					<a class="mesh-block-featured-image-trash dashicons-before dashicons-dismiss" data-mesh-block-featured-image="<?php echo esc_attr( $featured_image_id ); ?>"></a>
				<?php endif; ?>

				<input type="hidden"
				       name="<?php echo esc_attr( $args['input_name'] ); ?>"
				       value="<?php echo esc_attr( $featured_image_id ); ?>"/>
			</div>
		</div>
		<?php

		$output = ob_get_contents();
		ob_end_clean();
		if ( true === $echo ) {
			// Clean whitespace before output to prevent jQuery ajax warnings.
			echo trim( $output ); // WPCS: XSS ok, sanitization ok.
			return '';
		}

		return $output;
	}

	/**
	 * Hidden Input.
	 *
	 * @param array $args
	 * @param mixed $input_value
	 * @param bool  $echo
	 *
	 * @return mixed|null
	 */
	public static function get_input_hidden( $args, $input_value = '', $echo = true ) {
		ob_start();
		?>
		<input type="hidden"
			<?php esc_html( $args['input_id'] ); ?> name="<?php echo esc_attr( $args['input_name'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['input_css_classes'] ) ); ?>" value="<?php echo esc_attr( $input_value ); ?>" />
		<?php

		$output = ob_get_contents();
		ob_end_clean();
		if ( true === $echo ) {
			// Clean whitespace before output to prevent jQuery ajax warnings.
			echo trim( $output ); // WPCS: XSS ok, sanitization ok.
			return '';
		}

		return $output;
	}

	/**
	 * @param array $args
	 * @param mixed $input_value
	 * @param bool  $echo
	 *
	 * @return string
	 */
	public static function get_input_text( $args, $input_value = '', $echo = true ) {
		ob_start();
		?>
		<input type="text"
			<?php esc_html( $args['input_id'] ); ?>
			   name="<?php echo esc_attr( $args['input_name'] ); ?>"
			   class="<?php echo esc_attr( implode( ' ', $args['input_css_classes'] ) ); ?>"
			   value="<?php echo esc_attr( $input_value ); ?>" />
		<?php

		$output = ob_get_contents();
		ob_end_clean();
		if ( true === $echo ) {
			// Clean whitespace before output to prevent jQuery ajax warnings.
			echo trim( $output ); // WPCS: XSS ok, sanitization ok.
			return '';
		}

		return $output;
	}

	/**
	 * Create checkboxes.
	 * @since 1.2.5
	 *
	 * @param array $args
	 * @param mixed $input_value
	 * @param bool  $echo
	 *
	 * @return string
	 */
	public static function get_input_checkbox( $args, $input_value = 1, $echo = true ) {

		ob_start();
		?>
		<input type="checkbox"
			<?php esc_html( $args['input_id'] ); ?>
			   name="<?php echo esc_attr( $args['input_name'] ); ?>"
			   class="<?php echo esc_attr( implode( ' ', $args['input_css_classes'] ) ); ?>"
			   value="1"
			<?php checked( $input_value ); ?> />
		<?php

		$output = ob_get_contents();
		ob_end_clean();
		if ( true === $echo ) {
			// Clean whitespace before output to prevent jQuery ajax warnings.
			echo trim( $output ); // WPCS: XSS ok, sanitization ok.
			return '';
		}

		return $output;
	}

	/**
	 * Create select input for block and section controls.
	 *
	 * @since 1.2.5
	 *
	 * @param       $args
	 * @param       $input_value
	 * @param bool  $echo
	 * @param array $section
	 * @param array $blocks
	 *
	 * @return string|mixed
	 */
	public static function get_input_select( $args, $input_value, $echo = true, $section = array(), $blocks = array() ) {

		$multiple = '';

		if ( isset( $args['multiple'] ) && $args['multiple'] ) {
			$multiple = 'multiple';
		}

		ob_start();

		?>
		<select name="<?php echo esc_attr( $args['input_name'] ); ?>"
		        class="<?php echo esc_attr( implode( ' ', $args['input_css_classes'] ) ); ?>"
			<?php echo $multiple; // WPCS xss okay. ?>>
			<?php

			// @todo this needs to be cleaned up to meet wpcs
			$options = ( ! empty( $args['options_cb'] ) && is_callable( $args['options_cb'] ) )
				? call_user_func_array( $args['options_cb'], array( $section, $blocks ) )
				: $args['options'];

			if ( empty( $options ) ) {
				return ''; // Return if we do not have any options passed
			}

			/**
			 * Build out our <option> inputs and set the current select value if we have one.
			 */
			foreach ( $options as $option_key => $option_value ) {
				printf( '<option value="%1$s" %2$s>%3$s</option>',
					esc_attr( $option_key ),
					selected( esc_attr( $input_value ), esc_attr( $option_key ), false ),
					esc_attr( $option_value )
				);
			}
			?>
		</select>
		<?php

		$output = ob_get_contents();
		ob_end_clean();
		if ( true === $echo ) {
			echo trim( $output ); // WPCS: XSS ok, sanitization ok.
			return '';
		}

		return $output;
	}
}
