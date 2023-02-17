<?php
/*
Plugin Name: Handy Links Custom Dashboard Widget
Plugin URI: https://www.karks.com/
Description: Adds a customizable widget to the WordPress dashboard.
Version: 1.0
Author: Eric Karkovack
Author URI: https://www.karks.com/
License: GPL2
*/

// Add custom dashboard widget
function custom_dashboard_widget( $widget_title ) {
    wp_add_dashboard_widget(
        'custom_dashboard_widget', // Widget slug
        $widget_title, // Widget title
        'custom_dashboard_widget_content' // Callback function to display widget content
    );
}
add_action( 'wp_dashboard_setup', function() {
    custom_dashboard_widget( get_option( 'custom_dashboard_widget_title', 'Important Information' ) );
});


// Callback function to display widget content
function custom_dashboard_widget_content() {
    $widget_text = get_option( 'custom_dashboard_widget_text' );
    if ( ! empty( $widget_text ) ) {
		
		echo 
			'<style type="text/css">
				/* Custom Styles */	
				
				#custom_dashboard_widget.postbox .inside, #custom_dashboard_widget .wp-die-message, #custom_dashboard_widget p {
				 font-size: 15px;
				}
				
				#custom_dashboard_widget ul {
					list-style: disc;
					margin-left: 1.5em;
				}
				
				
			</style>';
		
        echo $widget_text;
    }
    else {
        echo '<p>No content.</p>';
    }
}

// Add settings page to customize widget
function custom_dashboard_widget_settings_page() {
    add_options_page(
        'Handy Links Custom Dashboard Widget Settings', // Page title
        'Handy Links Custom Widget', // Menu title
        'delete_pages', // Capability required to access page (Administrators and Editors only by default).
						//See https://wordpress.org/documentation/article/roles-and-capabilities/#roles
        'custom_dashboard_widget_settings', // Page slug
        'custom_dashboard_widget_settings_page_content' // Callback function to display page content
    );
}
add_action( 'admin_menu', 'custom_dashboard_widget_settings_page' );

// Callback function to display settings page content
function custom_dashboard_widget_settings_page_content() {
    $widget_title = get_option( 'custom_dashboard_widget_title', 'Custom Widget Title' );
    $widget_text = get_option( 'custom_dashboard_widget_text' );
    ?>

    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'custom_dashboard_widget_settings' ); ?>
            <?php do_settings_sections( 'custom_dashboard_widget_settings' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e( 'Widget Title' ); ?></th>
                    <td><input type="text" name="custom_dashboard_widget_title" value="<?php echo esc_attr( $widget_title ); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e( 'Widget Text' ); ?></th>
                    <td>
                        <?php
                        $editor_settings = array(
                            'media_buttons' => true,
                            'textarea_rows' => 5,
                            'teeny'         => false,
							'wpautop'		=> false,
                        );
                        wp_editor( $widget_text, 'custom_dashboard_widget_text', $editor_settings );
                        ?>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register settings
function custom_dashboard_widget_register_settings() {
    register_setting(
        'custom_dashboard_widget_settings', // Option group
        'custom_dashboard_widget_text', // Option name
        'sanitize_post' // Sanitization callback
    );
    register_setting(
        'custom_dashboard_widget_settings', // Option group
        'custom_dashboard_widget_title', // Option name
        'sanitize_text_field' // Sanitization callback
    );
}
add_action( 'admin_init', 'custom_dashboard_widget_register_settings' );