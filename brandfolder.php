<?php
/*
Plugin Name: Brandfolder
Plugin URI: http://wordpress.org/plugins/brandfolder/
Description: Adds the ability for you to edit your brandfolder inside Wordpress as well as embed it as a popup or in a Page/Post.
Version: 1.2
Author: Brandfolder, Inc.
Author URI: http://brandfolder.com
License: GPLv2
*/


//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//
// START THE BF FOR BRAND OWNERS
//
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

function brandfolder_inline($atts)	{

	$devOptions = get_option("brandfolderWordpressPluginAdminOptions");
	if (!empty($devOptions)) {
		foreach ($devOptions as $key => $option)
			$brandfolderAdminOptions[$key] = $option;
	}
     
  extract( shortcode_atts( array(
    'id' => $brandfolderAdminOptions["brandfolder_url"]
  ), $atts ) );

	$brandfolder_powered_by = $brandfolderAdminOptions["brandfolder_powered_by"];
	$brandfolder_inline_height = $brandfolderAdminOptions["brandfolder_inline_height"];
	$brandfolder_inline_width = $brandfolderAdminOptions["brandfolder_inline_width"];

	if (isset($brandfolder_inline_height)) {
		$bf_inline_height = $brandfolder_inline_height;
	} else {
		$bf_inline_height = "750px;";
	}

	if (isset($brandfolder_inline_width)) {
		$bf_inline_width = $brandfolder_inline_width;
	} else {
		$bf_inline_width = "99%;";
	}	

	$output = '<iframe src="https://brandfolder.com/'.$id.'/embed" style="max-width: '.$bf_inline_width.' !important;width: '.$bf_inline_width.' !important; height: '.$bf_inline_height.' !important; min-height: '.$bf_inline_height.' !important;border:0px;border:2px solid #CCC;" frameborder="0"></iframe>';
	$output .= '<a href="http://brandfolder.com" title="Brand assets by Brandfolder" style="float:right;"><img src="//d2sdf28wg0skh3.cloudfront.net/powered_by.png" style="height:30px;border:0px;"></a>';
	$output .= '<div style="clear:both;"></div>';	

	return $output;

}

function brandfolder_logos($atts, $content=null) {

  $devOptions = get_option("brandfolderWordpressPluginAdminOptions");
  if (!empty($devOptions)) {
    foreach ($devOptions as $key => $option)
      $brandfolderAdminOptions[$key] = $option;
  }

  extract( shortcode_atts( array(
    'id' => $brandfolderAdminOptions["brandfolder_url"]
  ), $atts ) );

  $brandfolder_powered_by = $brandfolderAdminOptions["brandfolder_powered_by"];

  wp_register_script( 'brandfolder_logos_script', '//brandfolder.com/api/beta/widgets.js?brand='.$id.'&widgets=logos');
  wp_enqueue_script( 'brandfolder_logos_script'); 

  $output = '<div data-bf-widget="logos"></div>';
  $output .= '<style type="text/css">'.$content.'</style>';

  return $output;

}

function brandfolder_images($atts, $content=null) {

  $devOptions = get_option("brandfolderWordpressPluginAdminOptions");
  if (!empty($devOptions)) {
    foreach ($devOptions as $key => $option)
      $brandfolderAdminOptions[$key] = $option;
  }

  extract( shortcode_atts( array(
    'id' => $brandfolderAdminOptions["brandfolder_url"]
  ), $atts ) );  

  $brandfolder_powered_by = $brandfolderAdminOptions["brandfolder_powered_by"];

  wp_register_script( 'brandfolder_images_script', '//brandfolder.com/api/beta/widgets.js?brand='.$id.'&widgets=images');
  wp_enqueue_script( 'brandfolder_images_script'); 

  $output = '<div data-bf-widget="images"></div>';
  $output .= '<style type="text/css">'.$content.'</style>';

  return $output;

}

function brandfolder_documents($atts, $content=null) {

  $devOptions = get_option("brandfolderWordpressPluginAdminOptions");
  if (!empty($devOptions)) {
    foreach ($devOptions as $key => $option)
      $brandfolderAdminOptions[$key] = $option;
  }

  extract( shortcode_atts( array(
    'id' => $brandfolderAdminOptions["brandfolder_url"]
  ), $atts ) );

  $brandfolder_powered_by = $brandfolderAdminOptions["brandfolder_powered_by"];

  wp_register_script( 'brandfolder_documents_script', '//brandfolder.com/api/beta/widgets.js?brand='.$id.'&widgets=documents');
  wp_enqueue_script( 'brandfolder_documents_script'); 

  $output = '<div data-bf-widget="documents"></div>';
  $output .= '<style type="text/css">'.$content.'</style>';

  return $output;

}

function brandfolder_people($atts, $content=null) {

  $devOptions = get_option("brandfolderWordpressPluginAdminOptions");
  if (!empty($devOptions)) {
    foreach ($devOptions as $key => $option)
      $brandfolderAdminOptions[$key] = $option;
  }

  extract( shortcode_atts( array(
    'id' => $brandfolderAdminOptions["brandfolder_url"]
  ), $atts ) );

  $brandfolder_powered_by = $brandfolderAdminOptions["brandfolder_powered_by"];

  wp_register_script( 'brandfolder_people_script', '//brandfolder.com/api/beta/widgets.js?brand='.$id.'&widgets=people');
  wp_enqueue_script( 'brandfolder_people_script'); 

  $output = '<div data-bf-widget="people"></div>';
  $output .= '<style type="text/css">'.$content.'</style>';


  return $output;

}

function brandfolder_press($atts, $content=null) {

  $devOptions = get_option("brandfolderWordpressPluginAdminOptions");
  if (!empty($devOptions)) {
    foreach ($devOptions as $key => $option)
      $brandfolderAdminOptions[$key] = $option;
  }

  extract( shortcode_atts( array(
    'id' => $brandfolderAdminOptions["brandfolder_url"]
  ), $atts ) );

  $brandfolder_powered_by = $brandfolderAdminOptions["brandfolder_powered_by"];

  wp_register_script( 'brandfolder_press_script', '//brandfolder.com/api/beta/widgets.js?brand='.$id.'&widgets=press');
  wp_enqueue_script( 'brandfolder_press_script'); 

  $output = '<div data-bf-widget="press"></div>';
  $output .= '<style type="text/css">'.$content.'</style>';

  return $output;

}

add_shortcode('brandfolder', 'brandfolder_inline');
add_shortcode('brandfolder-logos', 'brandfolder_logos');
add_shortcode('brandfolder-images', 'brandfolder_images');
add_shortcode('brandfolder-documents', 'brandfolder_documents');
add_shortcode('brandfolder-people', 'brandfolder_people');
add_shortcode('brandfolder-press', 'brandfolder_press');

function add_brandfolder_button() {
   // Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
 
   // Add only in Rich Editor mode
   
   if ( get_user_option('rich_editing') == 'true') {
     add_filter("mce_external_plugins", "add_brandfolder_tinymce_plugin");
     add_filter('mce_buttons_2', 'register_brandfolder_button');
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
							$devOptions['brandfolder_powered_by'] = apply_filters('brandfolder_powered_by', $_POST['brandfolder_powered_by']);
							$devOptions['brandfolder_inline_height'] = apply_filters('brandfolder_inline_height', $_POST['brandfolder_inline_height']);
							$devOptions['brandfolder_inline_width'] = apply_filters('brandfolder_inline_width', $_POST['brandfolder_inline_width']);
						}
						update_option($this->adminOptionsName, $devOptions);
						
						?>
						<div class="updated"><p><strong><?php _e("Settings Updated.", "brandfolderWordpressPlugin");?></strong></p></div>
											<?php
											} ?>
						<div class=wrap>
						<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
						<h2>Brandfolder setup</h2>
						<h3>Brandfolder url <span style="font-size:70%;">(get yours <a href="https://brandfolder.com/brands/" target="_blank">here</a>)</span></h3>
						https://brandfolder.com/<input type="text" name="brandfolder_url" size="20" value="<?php _e(apply_filters('format_to_edit',$devOptions['brandfolder_url']), 'brandfolderWordpressPlugin') ?>">
						<br>
						<hr>
						<h3>Settings for inline-embed option <span style="font-size:70%;">(<a href="http://help.brandfolder.com/knowledgebase/articles/237579" target="_blank">what's this?</a>)</span></h3>
						<div class="">
							<?php
								if(isset($devOptions['brandfolder_inline_height'])) {
									$brandfolder_inline_height = $devOptions['brandfolder_inline_height'];
								} else {
									$brandfolder_inline_height = "750px";
								}
								if(isset($devOptions['brandfolder_inline_width'])) {
									$brandfolder_inline_width = $devOptions['brandfolder_inline_width'];
								} else {
									$brandfolder_inline_width = "99%";
								}								
							?>
							IFrame Height: <input type="text" name="brandfolder_inline_height" size="20" value="<?php echo $brandfolder_inline_height ?>"><span style="font-size:90%;margin-left:15px;">Ex) 750px or 100%</span>
							<br>
							IFrame Width: <input type="text" name="brandfolder_inline_width" size="20" value="<?php echo $brandfolder_inline_width ?>"><span style="font-size:90%;margin-left:15px;">Ex) 750px or 100%</span>
						</div>
						<br>
						<hr>
						<h3 style="color:#CCC;"><input name="brandfolder_powered_by" disabled="disabled" type="checkbox" value="1" <?php checked( '1', $devOptions['brandfolder_powered_by'] ); ?>> Remove "Powered by Brandfolder" link <span style="font-size:70%;">(future paid feature)</span></h3>
						
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
					
					$devOptions = get_option("brandfolderWordpressPluginAdminOptions");
					if (!empty($devOptions)) {
						foreach ($devOptions as $key => $option)
							$brandfolderAdminOptions[$key] = $option;
					}		

					$brandfolder_url = $brandfolderAdminOptions["brandfolder_url"];

			    if ($brandfolder_url == "") {
						echo '<iframe src="https://brandfolder.com" style="width: 98%; height: 95%; min-height: 730px;margin-top:10px;"></iframe>';
			    } else {
			    	echo '<iframe src="https://brandfolder.com/'.$brandfolder_url.'/edit" style="width: 98%; height: 95%; min-height: 730px;margin-top:10px;"></iframe>';	
			    } 	
			}

		function ConfigureMenu() {
			add_menu_page("Edit Brandfolder", "Edit Brandfolder", 6, basename(__FILE__), array(&$dl_pluginSeries,'Main'));
			add_submenu_page( "brandfolder-menu", "Plugin setup", "Plugin setup", 6, basename(__FILE__),  array(&$dl_pluginSeries,'printAdminPage') );
		}			

		function add_settings_link($links, $file) {
		static $this_plugin;
		if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
		 
		if ($file == $this_plugin){
			$settings_link = '<a href="admin.php?page=brandfolder-sub-menu">'.__("setup", "brandfolder").'</a>';
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

		add_menu_page("Brandfolder", "Brandfolder", 6, "brandfolder-menu", array(&$dl_pluginSeries,'Main'), plugin_dir_url(__FILE__)."favicon.png");
		add_submenu_page( "brandfolder-menu", "Plugin setup", "Plugin setup", 6, "brandfolder-sub-menu",  array(&$dl_pluginSeries,'printAdminPage') );

	}	
}

function load_into_head() { 
	$devOptions = get_option("brandfolderWordpressPluginAdminOptions");
	if (!empty($devOptions)) {
		foreach ($devOptions as $key => $option)
			$brandfolderAdminOptions[$key] = $option;
	}		

	$brandfolder_url = $brandfolderAdminOptions["brandfolder_url"];

	?> 

	<script type="text/javascript">
	  var bf_url = '<?php echo $brandfolder_url ?>';
	  var brandfoldeOnLoad=function(){if("#brand"==window.location.hash)return Brandfolder.showEmbed({brandfolder_id:bf_url})};
	</script> 

<?php 
} 

//Actions and Filters	
if (isset($dl_pluginSeries)) {
	//Actions
	add_action('admin_menu', 'brandfolderWordpressPlugin_ap');
	add_action('brandfolder/brandfolder.php',  array(&$dl_pluginSeries, 'init'));

  wp_register_script( 'brandfolder', '//d2sdf28wg0skh3.cloudfront.net/bf.min.js');
  wp_enqueue_script( 'brandfolder'); 
	
	add_action( 'wp_head', 'load_into_head' );

}


?>