<?php
/*
Plugin Name: brandfolder
Plugin URI: http://brandfolder.com
Description: Adds the necessary javascript for you to link to your brandfolder as a popup on your own site.
Version: 0.1
Author: Brandfolder, Inc.
Author URI: http://brandfolder.com
License: GPLv2
*/

function brandfolder_shortcode()	{

	$devOptions = get_option("brandfolderWordpressPluginAdminOptions");
	if (!empty($devOptions)) {
		foreach ($devOptions as $key => $option)
			$brandfolderAdminOptions[$key] = $option;
	}		

	$brandfolder_url = $brandfolderAdminOptions["brandfolder_url"];
	$output = '<iframe src="http://brandfolder/'.$brandfolder_url.'/embed" style="width: 100%; height: 80%; min-height: 600px;"></iframe>';
	
	return $output;

 
}

add_shortcode('brandfolder', 'brandfolder_shortcode');

function add_brandfolder_button() {
   // Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
 
   // Add only in Rich Editor mode
   
   if ( get_user_option('rich_editing') == 'true') {
     add_filter("mce_external_plugins", "add_brandfolder_tinymce_plugin");
     add_filter('mce_buttons', 'register_brandfolder_button');
   }
   
}
 
function register_brandfolder_button($buttons) {
   array_push($buttons, "|", "yourbrandfolder");
   return $buttons;
}
 
// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
function add_brandfolder_tinymce_plugin($plugin_array) {   
   $plugin_array['yourbrandfolder'] = plugins_url( 'editor_plugin.js' , __FILE__ );
   return $plugin_array;
}


function my_refresh_mce($ver) {
  $ver += 3;
  return $ver;
}

// init process for button control - NOT USING FOR BRANDFOLDER
add_filter( 'tiny_mce_version', 'my_refresh_mce');
add_action('init', 'add_brandfolder_button');


if (!class_exists("brandfolderWordpressPlugin")) {
	class brandfolderWordpressPlugin {
		var $adminOptionsName = "brandfolderWordpressPluginAdminOptions";
		function brandfolderWordpressPlugin() { //constructor
			
		}
		function init() {
			$this->getAdminOptions();
		}
		//Returns an array of admin options
		function getAdminOptions() {
			$devloungeAdminOptions = array('brandfolder_url' => '');
			$devOptions = get_option($this->adminOptionsName);
			if (!empty($devOptions)) {
				foreach ($devOptions as $key => $option)
					$devloungeAdminOptions[$key] = $option;
			}				
			update_option($this->adminOptionsName, $devloungeAdminOptions);
			return $devloungeAdminOptions;
		}
		

		//Prints out the admin page
		function printAdminPage() {
					$devOptions = $this->getAdminOptions();
										
					if (isset($_POST['update_brandfolderWordpressPluginSettings'])) { 
						if (isset($_POST['brandfolder_url'])) {
							$devOptions['brandfolder_url'] = apply_filters('brandfolder_url', $_POST['brandfolder_url']);
						}
						update_option($this->adminOptionsName, $devOptions);
						
						?>
						<div class="updated"><p><strong><?php _e("Settings Updated.", "brandfolderWordpressPlugin");?></strong></p></div>
											<?php
											} ?>
						<div class=wrap>
						<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
						<h2>brandfolder Setup</h2>
						<h3>brandfolder url <span style="font-size:70%;">(get yours <a href="http://brandfolder.com/brands/" target="_blank">here</a>)</span></h3>
						http://brandfolder.com/<input type="text" name="brandfolder_url" size="20" value="<?php _e(apply_filters('format_to_edit',$devOptions['brandfolder_url']), 'brandfolderWordpressPlugin') ?>">
						<div class="submit">
						<input type="submit" name="update_brandfolderWordpressPluginSettings" value="<?php _e('Update Settings', 'brandfolderWordpressPlugin') ?>" /></div>
						</form>
						 </div>
					<?php
				}//End function printAdminPage()


		function Main() {
			    if (!current_user_can('manage_options'))  {
			        wp_die( __('You do not have sufficient permissions to access this page.') );
			    }
			  	echo '<iframe src="https://brandfolder.com" style="width: 100%; height: 80%; min-height: 600px;"></iframe>';
			}

		function ConfigureMenu() {
			add_menu_page("brandfolder", "brandfolder", 6, basename(__FILE__), array(&$dl_pluginSeries,'Main'));
			add_submenu_page( "brandfolder-menu", "Setup", "Setup", 6, basename(__FILE__),  array(&$dl_pluginSeries,'printAdminPage') );
		}			

		function add_settings_link($links, $file) {
		static $this_plugin;
		if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
		 
		if ($file == $this_plugin){
			$settings_link = '<a href="admin.php?page=brandfolder-sub-menu">'.__("Setup", "brandfolder-wordpress-plugin").'</a>';
			 array_unshift($links, $settings_link);
		}
			return $links;
		 }
	
	}

} //End Class brandfolderWordpressPlugin

if (class_exists("brandfolderWordpressPlugin")) {
	$dl_pluginSeries = new brandfolderWordpressPlugin();
}

//Initialize the admin panel
if (!function_exists("brandfolderWordpressPlugin_ap")) {
	function brandfolderWordpressPlugin_ap() {
		global $dl_pluginSeries;
		if (!isset($dl_pluginSeries)) {
			return;
		}

		add_menu_page("brandfolder", "brandfolder", 6, "brandfolder-menu", array(&$dl_pluginSeries,'Main'), plugin_dir_url(__FILE__)."favicon.png");
		add_submenu_page( "brandfolder-menu", "Setup", "Setup", 6, "brandfolder-sub-menu",  array(&$dl_pluginSeries,'printAdminPage') );

		if (function_exists('add_options_page')) {
			add_options_page('brandfolder Setup', 'brandfolder Setup', 9, basename(__FILE__), array(&$dl_pluginSeries, 'printAdminPage'));
		}		

	}	
}

//Actions and Filters	
if (isset($dl_pluginSeries)) {
	//Actions
	add_action('admin_menu', 'brandfolderWordpressPlugin_ap');
	add_action('brandfolder-wordpress-plugin/brandfolder.php',  array(&$dl_pluginSeries, 'init'));
	
//	add_filter('plugin_action_links', array(&$dl_pluginSeries, 'add_settings_link'), 10, 2 );

    wp_register_script( 'brandfolder', 'https://brandfolder.com/js');
    wp_enqueue_script( 'brandfolder'); 

}


?>