<?php
/*
Plugin Name: brandfolder
Plugin URI: http://brandfolder.com
Description: Adds the ability for you to edit your brandfolder inside Wordpress as well as embed it as a popup or in a Page/Post.
Version: 0.2
Author: Brandfolder, Inc.
Author URI: http://brandfolder.com
License: GPLv2
*/

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//
// START THE BF FOR BRAND CONSUMERS
//
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


//Assign a name to your tab
function bf_media_menu($tabs) {
	$tabs['bf']='Brandfolder';
	return $tabs;
}

//Adds your scripts to your plugin
function bf_scripts() {	
	//Adds css
	wp_deregister_style( 'brandfolder-style', plugins_url('upload-media.css', __FILE__) );
  wp_register_style( 'brandfolder-style', plugins_url('upload-media.css', __FILE__) );
  wp_enqueue_style( 'brandfolder-style' );	

	//Adds JQuery 
	wp_deregister_script('jquery');
	wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js');
	wp_enqueue_script( 'jquery' );

	//Adds the custom brandfolder script
	wp_deregister_script( 'upload-media-script' );
	wp_register_script( 'upload-media-script', plugins_url( 'upload-media.js' , __FILE__ ));
	wp_enqueue_script( 'upload-media-script' );

}

//This is our form for the plugin
function bf_upload_form () {
	//echos the tabs at the top of the media window
	media_upload_header();

	//Adds your javascript
	bf_scripts();
	?>

		<div id="s-bfTopBar">
			<div class="s-left-links">
				<a href="http://brandfolder.com" target="bfIframe">Home</a>
			</div>
			<div class="s-input-form">
				<input type='text' id='js-bfLogoName' placeholder="Right click on an image's download link, choose 'Copy Link Address' and paste it here" />
				<input id='js-bfInsertShortCode' type='button' class='button' value='Insert Image'>
			</div> 	
		</div>
		<div id="s-bfMainContent">

<?php
	$devOptions = get_option("brandfolderWordpressPluginAdminOptions");
		if (!empty($devOptions)) {
			foreach ($devOptions as $key => $option)
				$brandfolderAdminOptions[$key] = $option;
		}
		$brandfolder_url = $brandfolderAdminOptions["brandfolder_url"];
		$output = '<iframe id="bfIframe" name="bfIframe" src="https://brandfolder.com/'.$brandfolder_url.'/embed" style="background-color:white;background-image:url(\'https://d2cw52ytgc6llc.cloudfront.net/loading_embed.gif\');background-repeat:no-repeat;background-attachment:fixed;background-position:center;width: 99%; height:85%; margin-top:60px; min-height: 600px;border:0px;border:2px solid #CCC;margin:0 auto;" frameborder="0"></iframe>';	

		echo $output;
		echo "</div>";
 }

//Returns the iframe that your plugin will be returned in
function bf_menu_handle() {
	return wp_iframe('bf_upload_form');
}

//Needed script to make sure wordpresses media upload scripts are inplace
wp_enqueue_script('media-upload');

//Adds your tab to the media upload button
add_filter('media_upload_tabs', 'bf_media_menu');

//Adds your menu handle to when the media upload action occurs
add_action('media_upload_bf', 'bf_menu_handle');

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//
// START THE BF FOR BRAND OWNERS
//
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

function brandfolder_shortcode()	{

	$devOptions = get_option("brandfolderWordpressPluginAdminOptions");
	if (!empty($devOptions)) {
		foreach ($devOptions as $key => $option)
			$brandfolderAdminOptions[$key] = $option;
	}		

	$brandfolder_url = $brandfolderAdminOptions["brandfolder_url"];
	$output = '<iframe src="https://brandfolder.com/'.$brandfolder_url.'/embed" style="width: 100%; height: 100%; min-height: 600px;border:0px;border:2px solid #CCC;" frameborder="0"></iframe>';

	/*
		if( !class_exists( 'WP_Http' ) ) include_once( ABSPATH . WPINC. '/class-http.php' );

		$request = new WP_Http;
		$result = $request->request( htmlspecialchars_decode("https://brandfolder.com/".$brandfolder_url."/embed") );

		if (isset($result->errors)) {
			// display error message of some sort
			$output = "Error occured!";
			$output .= "<!-- brandfolder 1.0 URL: ".htmlspecialchars_decode("https://brandfolder.com/".$brandfolder_url."/embed")." DEBUG: ".$result->errors." -->";
		} else {

			$output = $result['body'];
			$output = str_replace("span11 offset1","",$output);
			$output = str_replace("span10 offset2","",$output);
			$output = str_replace("container","",$output);
			$output .= "<!-- brandfolder 1.0 URL: ".htmlspecialchars_decode("https://brandfolder.com/".$brandfolder_url."/embed")." -->";

		}
	*/
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
						<h2>brandfolder setup</h2>
						<h3>brandfolder url <span style="font-size:70%;">(get yours <a href="https://brandfolder.com/brands/" target="_blank">here</a>)</span></h3>
						https://brandfolder.com/<input type="text" name="brandfolder_url" size="20" value="<?php _e(apply_filters('format_to_edit',$devOptions['brandfolder_url']), 'brandfolderWordpressPlugin') ?>">
						<div class="submit">
						<input type="submit" name="update_brandfolderWordpressPluginSettings" value="<?php _e('Update Settings', 'brandfolderWordpressPlugin') ?>" /></div>
						</form>
						<h3>Security Settings</h3>
						<p>Restrict embedding to certain domains by clicking 'Embed' for your brand at <a href="https://brandfolder.com/brands" target="_blank">https://brandfolder.com/brands</a> and fill out the "Embed Restrictions" box.</p>
						 </div>
					<?php
				}//End function printAdminPage()


		function Main() {
			    if (!current_user_can('manage_options'))  {
			        wp_die( __('You do not have sufficient permissions to access this page.') );
			    }
			  	echo '<iframe src="https://brandfolder.com" style="width: 98%; height: 90%; min-height: 600px;margin-top:10px;"></iframe>';
			}

		function ConfigureMenu() {
			add_menu_page("brandfolder", "brandfolder", 6, basename(__FILE__), array(&$dl_pluginSeries,'Main'));
			add_submenu_page( "brandfolder-menu", "plugin setup", "plugin setup", 6, basename(__FILE__),  array(&$dl_pluginSeries,'printAdminPage') );
		}			

		function add_settings_link($links, $file) {
		static $this_plugin;
		if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
		 
		if ($file == $this_plugin){
			$settings_link = '<a href="admin.php?page=brandfolder-sub-menu">'.__("setup", "brandfolder-wordpress-plugin").'</a>';
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
		add_submenu_page( "brandfolder-menu", "plugin setup", "plugin setup", 6, "brandfolder-sub-menu",  array(&$dl_pluginSeries,'printAdminPage') );

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