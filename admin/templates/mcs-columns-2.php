<?php
/**
 * 2 Editor template
 *
 * @package MultipleContentSection
 * @subpackage AdminTemplates
 * @since 1.2.0
 */

?>
<?php if ( ! $block_columns = get_post_meta( $blocks[0]->ID, '_mcs_column_width', true ) ) {
	$block_columns = 6;
}
?>
<div class="mcs-row">
	<div class="mcs-columns-<?php esc_attr_e( $block_columns ); ?> columns">
		<div class="drop-target">
			<div class="block" id="mcs-block-editor-<?php esc_attr_e( $blocks[0]->ID ); ?>"  data-mcs-block-id="<?php esc_attr_e( $blocks[0]->ID ); ?>">
				<div class="block-header"><?php esc_html_e( $blocks[0]->post_title ); ?></div>
				<div class="block-content">
				<?php
				wp_editor( apply_filters( 'content_edit_pre', $blocks[0]->post_content ), 'mcs-section-editor-' . $blocks[0]->ID, array(
					'textarea_name' => 'mcs-sections[' . $section->ID . '][blocks][' . $blocks[0]->ID . '][post_content]',
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
						'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,more',
					),
				) );
				?>
				</div>

				<input type="hidden" class="column-width" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[0]->ID ); ?>][columns]" value="<?php esc_attr_e( $block_columns ); ?>"/>
			</div>
		</div>
	</div>
	<?php if ( ! $block_column_2 = get_post_meta( $blocks[1]->ID, '_mcs_column_width', true ) ) {
		$block_column_2 = 6;
	} ?>
	<div class="mcs-columns-<?php esc_attr_e( $block_column_2 ); ?> columns">
		<div class="drop-target">
			<div class="block" id="mcs-block-editor-<?php esc_attr_e( $blocks[1]->ID ); ?>" data-mcs-block-id="<?php esc_attr_e( $blocks[1]->ID ); ?>">
				<div class="block-header"><?php esc_html_e( $blocks[1]->post_title ); ?></div>
				<div class="block-content">
				<?php
				wp_editor( apply_filters( 'content_edit_pre', $blocks[1]->post_content ), 'mcs-section-editor-' . $blocks[1]->ID, array(
					'textarea_name' => 'mcs-sections[' . $section->ID . '][blocks][' . $blocks[1]->ID . '][post_content]',
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
						'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,more',
					),
				) );
				?>
				</div>
				<input type="hidden" class="column-width" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][blocks][<?php esc_attr_e( $blocks[1]->ID ); ?>][columns]" value="<?php esc_attr_e( $block_column_2 ); ?>" />
			</div>
		</div>
	</div>
</div>
