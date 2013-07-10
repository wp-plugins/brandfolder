<?php
/*
Plugin Name: brandfolder
Plugin URI: http://brandfolder.com
Description: Adds the ability for you to edit your brandfolder inside Wordpress as well as embed it as a popup or in a Page/Post.
Version: 1.0
Author: Brandfolder, Inc.
Author URI: http://brandfolder.com
License: GPLv2
*/

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//
// START THE BF FOR BRAND CONSUMERS
//
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

class BrandfolderServe {

	var $imageName;

	function BrandfolderServe(){$this->__construct();}
		
	function __construct(){
		global $wp_version;
		
		if ($wp_version < 3.5) {
			if ( basename($_SERVER['PHP_SELF']) != "media-upload.php" ) return;
		} else {
			if ( basename($_SERVER['PHP_SELF']) != "media-upload.php" && basename($_SERVER['PHP_SELF']) != "post.php" && basename($_SERVER['PHP_SELF']) != "post-new.php") return;
		}
		
		add_filter("media_upload_tabs",array(&$this,"build_tab"));
		add_action("media_upload_brandfolderServe", array(&$this, "menu_handle"));
	}
	
	/*
	 * Merge an array into middle of another array
	 *
	 * @param array $array the array to insert
	 * @param array $insert array to be inserted
	 * @param int $position index of array
	 */
	function array_insert(&$array, $insert, $position) {
		settype($array, "array");
		settype($insert, "array");
		settype($position, "int");

		//if pos is start, just merge them
		if($position==0) {
			$array = array_merge($insert, $array);
		} else {


			//if pos is end just merge them
			if($position >= (count($array)-1)) {
				$array = array_merge($array, $insert);
			} else {
				//split into head and tail, then merge head+inserted bit+tail
				$head = array_slice($array, 0, $position);
				$tail = array_slice($array, $position);
				$array = array_merge($head, $insert, $tail);
			}
		}
		return $array;
	}

	
	function build_tab($tabs) {
		$newtab = array('brandfolderServe' => __('Brandfolder', 'brandfolderServe'));
		return $this->array_insert($tabs, $newtab, 2);
		//return array_merge($tabs,$newtab);
	}
	function menu_handle() {
		return wp_iframe(array($this,"media_process"));
	}
	function fetch_image($url) {
		if ( function_exists("curl_init") ) {
			return $this->curl_fetch_image($url);
		} elseif ( ini_get("allow_url_fopen") ) {
			return $this->fopen_fetch_image($url);
		}
	}
	function curl_fetch_image($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$image = curl_exec($ch);
		curl_close($ch);
		return $image;
	}
	function fopen_fetch_image($url) {
		$image = file_get_contents($url, false, $context);
		return $image;
	}
	
	function media_process() {
		
    wp_deregister_style( 'brandfolder-style', plugins_url('upload-media.css', __FILE__) );
    wp_register_style( 'brandfolder-style', plugins_url('upload-media.css', __FILE__) );
    wp_enqueue_style( 'brandfolder-style' );  

		if ( $_POST['imageurl'] ) {
			$imageurl = $_POST['imageurl'];
			$imageurl = stripslashes($imageurl);
			$uploads = wp_upload_dir();
			$post_id = isset($_GET['post_id'])? (int) $_GET['post_id'] : 0;
			$ext = pathinfo( basename($imageurl) , PATHINFO_EXTENSION);

			if (strpos($imageurl,'brandfolder.com') !== false) {
			   $ext = end(explode('/', $imageurl));
			   $newfilename = $_POST['newfilename'] ? $_POST['newfilename'] . "." . $ext : "brandfolder.".$ext;
			} else {
				$newfilename = $_POST['newfilename'] ? $_POST['newfilename'] . "." . $ext : basename($imageurl);
			}

			$filename = wp_unique_filename( $uploads['path'], $newfilename, $unique_filename_callback = null );
			$wp_filetype = wp_check_filetype($filename, null );
			$fullpathfilename = $uploads['path'] . "/" . $filename;
			
			try {

				/* if ( !substr_count($wp_filetype['type'], "image") ) {
					throw new Exception( basename($newfilename) . ' is not a valid image. ' . $wp_filetype['type']  . '' );
				}
				*/

				$image_string = $this->fetch_image($imageurl);
				$fileSaved = file_put_contents($uploads['path'] . "/" . $filename, $image_string);
				if ( !$fileSaved ) {
					throw new Exception("The file cannot be saved.");
				}
				
				$attachment = array(
					 'post_mime_type' => $wp_filetype['type'],
					 'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
					 'post_content' => '',
					 'post_status' => 'inherit',
					 'guid' => $uploads['url'] . "/" . $filename
				);
				$attach_id = wp_insert_attachment( $attachment, $fullpathfilename, $post_id );
				if ( !$attach_id ) {
					throw new Exception("Failed to save record into database.");
				}
				require_once(ABSPATH . "wp-admin" . '/includes/image.php');
				$attach_data = wp_generate_attachment_metadata( $attach_id, $fullpathfilename );
				wp_update_attachment_metadata( $attach_id,  $attach_data );
			
			} catch (Exception $e) {
				$error = '<div id="message" class="error"><p>' . $e->getMessage() . '</p></div>';
			}

		}
		media_upload_header();
		if ( !function_exists("curl_init") && !ini_get("allow_url_fopen") ) {
			echo '<div id="message" class="error"><p><b>cURL</b> or <b>allow_url_fopen</b> needs to be enabled. Please consult your server Administrator.</p></div>';
		} elseif ( $error ) {
			echo $error;
		} else {
			if ( $fileSaved && $attach_id ) {
				echo '<div id="message" class="updated"><p>File saved.</p></div>';
			}
		}
		?>

		<div id="s-bf">
      <div id="s-bfTopBar">
        <!--<input type='text' id='js-bfLogoName' placeholder="Right click on an image's download link, choose 'Copy Link Address' and paste it here" />
        <input id='js-bfInsertShortCode' type='button' class='button' value='Insert Image'>-->
		    <div class="s-left-links">
		    	<div style="float:left;">
			      <a href="http://brandfolder.com" target="bfIframe">Home</a>
			      &nbsp;&nbsp;
			      <a href="http://brandfolder.com/search" target="bfIframe">Search</a>
		    	</div>
		    	<div style="float:left;margin-left: 10px;margin-top: -5px;">
			    	<form action="" method="post" id="image-form" class="media-upload-form type-form" style="display:inline-block;margin:0px;">
								<input id="src" type="text" name="imageurl" style="width:450px;" placeholder="Image URL">
								<!--Save as (optional) <input type="text" name="newfilename" style="width:100px" placeholder="Save as (optional)">-->
								<input type="submit" class="button" value="Grab">
								<br><span style="color:#CCC;font-size:80%;">Right click on an image's download link, choose 'Copy Link Address' and paste it here</span>
						</form>
					</div>
					<div style="clear:both;"></div>
				</div>
		  </div>  

		<?php
		
		if ( $attach_id )  {
			$this->media_upload_type_form("image", $errors, $attach_id);
		}
		?>

      <div id="s-bfMainContent">

	  <?php

	      $output = '<iframe id="bfIframe" name="bfIframe" src="https://brandfolder.com/search" style="background-color:white;background-image:url(\'https://d2sdf28wg0skh3.cloudfront.net/loading_embed.gif\');background-repeat:no-repeat;background-attachment:fixed;background-position:center;width: 99%; height:85%; min-height: 600px;border:0px;border:2px solid #CCC;margin:0 auto;" frameborder="0"></iframe>';  

	      echo $output;
	      echo "</div>";

		echo "</div>";			
		}
	
	
	/*
	 * modification from media.php function
	 *
	 * @param unknown_type $type
	 * @param unknown_type $errors
	 * @param unknown_type $id
	 */
	function media_upload_type_form($type = 'file', $errors = null, $id = null) {

		$post_id = isset( $_REQUEST['post_id'] )? intval( $_REQUEST['post_id'] ) : 0;

		$form_action_url = admin_url("media-upload.php?type=$type&tab=type&post_id=$post_id");
		$form_action_url = apply_filters('media_upload_form_url', $form_action_url, $type);
		?>

		<form enctype="multipart/form-data" method="post" action="<?php echo esc_attr($form_action_url); ?>" class="media-upload-form type-form validate" id="<?php echo $type; ?>-form">
		<input type="submit" class="hidden" name="save" value="" />
		<input type="hidden" name="post_id" id="post_id" value="<?php echo (int) $post_id; ?>" />
		<?php wp_nonce_field('media-form'); ?>

		<script type="text/javascript">
		//<![CDATA[
		jQuery(function($){
			var preloaded = $(".media-item.preloaded");
			if ( preloaded.length > 0 ) {
				preloaded.each(function(){prepareMediaItem({id:this.id.replace(/[^0-9]/g, '')},'');});
			}
			updateMediaForm();
		});
		//]]>
		</script>
		<div id="media-items">
		<?php
		if ( $id ) {
			if ( !is_wp_error($id) ) {
				add_filter('attachment_fields_to_edit', 'media_post_single_attachment_fields_to_edit', 10, 2);
				echo get_media_items( $id, $errors );
				echo "<style type='text/css'>.s-bfTopBar { display:none !important; }</style>";
			} else {
				echo '<div id="media-upload-error">'.esc_html($id->get_error_message()).'</div>';
				exit;
			}
		}
		?>
		</div>
		<p class="savebutton ml-submit">
		<input type="submit" class="button" name="save" value="<?php esc_attr_e( 'Save all changes' ); ?>" />
		</p>
		</form>
		
		<?php
	}
}

new BrandfolderServe();

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


//add_action('init', 'add_brandfolder_button');

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

		add_menu_page("brandfolder", "brandfolder", 6, "brandfolder-menu", array(&$dl_pluginSeries,'Main'), plugin_dir_url(__FILE__)."favicon.png");
		add_submenu_page( "brandfolder-menu", "plugin setup", "plugin setup", 6, "brandfolder-sub-menu",  array(&$dl_pluginSeries,'printAdminPage') );

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
	  var brandfolderLoadUrl = '<?php echo $brandfolder_url ?>';
	  var brandfoldeOnLoad=function(){if("#brand"==window.location.hash)return Brandfolder.showEmbed({brandfolder_id:brandfolderLoadUrl})};
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