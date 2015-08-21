<?php
/**
 * 2 Editor template
 *
 * @package MultipleContentSection
 * @subpackage AdminTemplates
 * @since 1.2.0
 */

?>

<div class="mcs-left columns-6">
	<?php
	wp_editor( apply_filters( 'content_edit_pre', $blocks[0]->post_content ), 'mcs-section-editor-' . $blocks[0]->ID, array(
		'textarea_name' => 'mcs-sections[' . $section->ID . '][blocks][' . $blocks[0]->ID . ']',
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
			'toolbar2'              => 'undo,redo',
		),
		'quicktags' => array(
			'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,more',
		),
	) );
	?>
</div>

<div class="mcs-right columns-6">
	<?php
	wp_editor( apply_filters( 'content_edit_pre', $blocks[0]->post_content ), 'mcs-section-editor-' . $blocks[1]->ID, array(
		'textarea_name' => 'mcs-sections[' . $section->ID . '][blocks][' . $blocks[0]->ID . ']',
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
			'toolbar2'              => 'undo,redo',
		),
		'quicktags' => array(
			'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,more',
		),
	) );
	?>
</div>
