<div class="welcome-panel">
<div class="mesh welcome-panel-content">
	<h2><?php _e( 'Welcome to Mesh templates' ); ?></h2>
	<p class="about-description"><?php _e( 'Templates are reusable layouts that can help you build pages even faster while using Mesh.', 'mesh' ); ?></p>
	<p class="about-description"><?php _e( 'We&#8217;ve assembled some links to get you started:', 'mesh' ); ?></p>
	<div class="welcome-panel-column-container">
		<div class="welcome-panel-column">
			<h3><?php _e( 'Get Started' ); ?></h3>
			<a class="mesh-add-section button button-primary button-hero" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=mesh_template' ) ); ?>"><?php esc_html_e( 'Create New Mesh Template', 'mesh'  ); ?></a>
		</div>
		<div class="welcome-panel-column">
			<h3><?php _e( 'More Actions', 'mesh' ); ?></h3>
			<ul>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-learn-more">' . __( 'Learn more about Mesh', 'mesh' ) . '</a>', __( 'https://github.com/linchpinagency/mesh' ) ); ?></li>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-view-linchin">' . __( 'About Linchpin', 'mesh' ) . '</a>', __( 'https://linchpin.agency' ) ); ?></li>
			</ul>
		</div>
	</div>
</div>
</div>