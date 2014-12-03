
Use of  custom Post Types

==================

Example of custom admin plugin created to run exportable reports. 

Navigation item created and linked to page displaying report options executed via clicking a button. 


================

add_action( 'admin_menu', 'my_plugin_menu' );

function my_plugin_menu() {
	add_menu_page( 'Fin Landing Page Data Export', 'Fin Landing Page Data Export', 'manage_options', 'finlpd', 'my_plugin_options', plugins_url( 'fin-landing-page/fin-icon.png' ),3);
}

function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	echo '<div class="wrap">';
	echo '<p><a class="button-secondary" href="/blog/wp-content/plugins/fin-landing-page/fin-landing-page-export-data.php" target="_blank" title="EXPORT">EXPORT - FREE FIN RECHARGEABLE STARTER KIT</a></p>';
	echo '</div>';
}

====================