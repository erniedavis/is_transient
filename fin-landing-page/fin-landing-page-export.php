<?php
/**
 * Plugin Name: Fin Landing Page Export
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: A brief description of the Plugin.
 * Version: The Plugin's Version Number, e.g.: 1.0
 * Author: Name Of The Plugin Author
 * Author URI: http://URI_Of_The_Plugin_Author
 * License: A "Slug" license name e.g. GPL2
 */

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
?>