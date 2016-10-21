<?php
    $classes = 'welcome-panel';

    $option = get_user_meta( get_current_user_id(), 'show_mesh_template_panel', true );

    // 1 = show, 0 = hide.
    $show = ( '' === $option ) ? 1 : (int) $option;

    if ( ! $show ) {
	    $classes .= ' hidden';
    }

?>
<div id="mesh-template-welcome-panel" class="<?php echo esc_attr( $classes ); ?>">
    <?php wp_nonce_field( 'meshtemplatepanelnonce', 'mesh-templates-welcome-panel-nonce', false ); ?>
    <a class="mesh-template-welcome-panel-close" href="<?php echo esc_url( admin_url( 'edit.php?post_type=mesh_template&mesh_template_welcome=0' ) ); ?>" aria-label="<?php esc_attr_e( 'Dismiss the Mesh templates welcome panel', 'mesh' ); ?>"><?php esc_html_e( 'Dismiss', 'mesh' ); ?></a>
    <div class="mesh welcome-panel-content">
        <h2><?php esc_html_e( 'Welcome to Mesh templates', 'mesh' ); ?></h2>
        <p class="about-description"><?php esc_html_e( 'Templates are reusable layouts that can help you build pages even faster while using Mesh.', 'mesh' ); ?></p>
        <p class="about-description"><?php esc_html_e( 'Templates are currently best used as a starting point but there are plans for more robust template usage in future releases.', 'mesh' ); ?></p>
        <p class="about-description"><?php esc_html_e( 'We&#8217;ve assembled some links to get you started:', 'mesh' ); ?></p>
        <div class="welcome-panel-column-container">
            <div class="welcome-panel-column">
                <h3><?php esc_html_e( 'Get Started', 'mesh' ); ?></h3>
                <a class="mesh-add-section button button-primary button-hero" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=mesh_template' ) ); ?>"><?php esc_html_e( 'Create New Mesh Template', 'mesh' ); ?></a>
            </div>
            <div class="welcome-panel-column">
                <h3><?php esc_html_e( 'More Actions', 'mesh' ); ?></h3>
                <ul>
                    <li><?php printf( '<a href="%s" class="welcome-icon welcome-learn-more" target="_blank">' . __( 'Learn more about Mesh', 'mesh' ) . '</a>', __( 'https://github.com/linchpinagency/mesh' ) ); ?></li>
                    <li><?php printf( '<a href="%s" class="welcome-icon welcome-view-linchin icon-linchpin-logo" target="_blank">' . __( 'About Linchpin', 'mesh' ) . '</a>', __( 'https://linchpin.agency' ) ); ?></li>
                    <li><?php printf( '<a href="%s" class="welcome-icon welcome-view-github icon-github" target="_blank">' . __( 'View Features Requests', 'mesh' ) . '</a>', __( 'https://github.com/linchpinagency/mesh/issues' ) ); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>