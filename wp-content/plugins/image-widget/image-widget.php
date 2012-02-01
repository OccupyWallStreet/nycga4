<?php
/*
Plugin Name: Image Widget
Plugin URI: http://wordpress.org/extend/plugins/image-widget/
Description: Simple image widget that uses native Wordpress upload thickbox to add image widgets to your site.
Author: Modern Tribe, Inc.
Version: 3.2.11
Author URI: http://tri.be/
*/

// Load the widget on widgets_init
function tribe_load_image_widget() {
	register_widget('Tribe_Image_Widget');
}
add_action('widgets_init', 'tribe_load_image_widget');

/**
 * SP Image Widget class
 *
 * @author Shane & Peter, Inc. (Peter Chester)
 **/
class Tribe_Image_Widget extends WP_Widget {
	
	var $pluginDomain = 'sp_image_widget';

	/**
	 * SP Image Widget constructor
	 *
	 * @return void
	 * @author Shane & Peter, Inc. (Peter Chester)
	 */
	function Tribe_Image_Widget() {
		$this->loadPluginTextDomain();
		$widget_ops = array( 'classname' => 'widget_sp_image', 'description' => __( 'Showcase a single image with a Title, URL, and a Description', $this->pluginDomain ) );
		$control_ops = array( 'id_base' => 'widget_sp_image' );
		$this->WP_Widget('widget_sp_image', __('Image Widget', $this->pluginDomain), $widget_ops, $control_ops);

		global $pagenow;
		if (defined("WP_ADMIN") && WP_ADMIN) {
    		add_action( 'admin_init', array( $this, 'fix_async_upload_image' ) );
			if ( 'widgets.php' == $pagenow ) {
				wp_enqueue_style( 'thickbox' );
				wp_enqueue_script( $control_ops['id_base'], WP_PLUGIN_URL.'/image-widget/image-widget.js',array('thickbox'), false, true );
				add_action( 'admin_head-widgets.php', array( $this, 'admin_head' ) );
			} elseif ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
				add_filter( 'image_send_to_editor', array( $this,'image_send_to_editor'), 1, 8 );
				add_filter( 'gettext', array( $this, 'replace_text_in_thickbox' ), 1, 3 );
				add_filter( 'media_upload_tabs', array( $this, 'media_upload_tabs' ) );
			}
		}
		
	}
	
	function fix_async_upload_image() {
		if(isset($_REQUEST['attachment_id'])) {
			$GLOBALS['post'] = get_post($_REQUEST['attachment_id']);
		}
	}
	
	function loadPluginTextDomain() {
		load_plugin_textdomain( $this->pluginDomain, false, trailingslashit(basename(dirname(__FILE__))) . 'lang/');
	}
	
	/**
	 * Retrieve resized image URL
	 *
	 * @param int $id Post ID or Attachment ID
	 * @param int $width desired width of image (optional)
	 * @param int $height desired height of image (optional)
	 * @return string URL
	 * @author Shane & Peter, Inc. (Peter Chester)
	 */
	function get_image_url( $id, $width=false, $height=false ) {
		
		/**/
		// Get attachment and resize but return attachment path (needs to return url)
		$attachment = wp_get_attachment_metadata( $id );
		$attachment_url = wp_get_attachment_url( $id );
		if (isset($attachment_url)) {
			if ($width && $height) {
				$uploads = wp_upload_dir();
				$imgpath = $uploads['basedir'].'/'.$attachment['file'];
				error_log($imgpath);
				$image = image_resize( $imgpath, $width, $height );
				if ( $image && !is_wp_error( $image ) ) {
					error_log( is_wp_error($image) );
					$image = path_join( dirname($attachment_url), basename($image) );
				} else {
					$image = $attachment_url;
				}
			} else {
				$image = $attachment_url;
			}
			if (isset($image)) {
				return $image;
			}
		}
	}

	/**
	 * Test context to see if the uploader is being used for the image widget or for other regular uploads
	 *
	 * @return void
	 * @author Shane & Peter, Inc. (Peter Chester)
	 */
	function is_sp_widget_context() {
		if ( isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],$this->id_base) !== false ) {
			return true;
		} elseif ( isset($_REQUEST['_wp_http_referer']) && strpos($_REQUEST['_wp_http_referer'],$this->id_base) !== false ) {
			return true;
		} elseif ( isset($_REQUEST['widget_id']) && strpos($_REQUEST['widget_id'],$this->id_base) !== false ) {
			return true;
		}
		return false;
	}
	
	/**
	 * Somewhat hacky way of replacing "Insert into Post" with "Insert into Widget"
	 *
	 * @param string $translated_text text that has already been translated (normally passed straight through)
	 * @param string $source_text text as it is in the code
	 * @param string $domain domain of the text
	 * @return void
	 * @author Shane & Peter, Inc. (Peter Chester)
	 */
	function replace_text_in_thickbox($translated_text, $source_text, $domain) {
		if ( $this->is_sp_widget_context() ) {
			if ('Insert into Post' == $source_text) {
				return __('Insert Into Widget', $this->pluginDomain );
			}
		}
		return $translated_text;
	}
	
	/**
	 * Filter image_end_to_editor results
	 *
	 * @param string $html 
	 * @param int $id 
	 * @param string $alt 
	 * @param string $title 
	 * @param string $align 
	 * @param string $url 
	 * @param array $size 
	 * @return string javascript array of attachment url and id or just the url
	 * @author Shane & Peter, Inc. (Peter Chester)
	 */
	function image_send_to_editor( $html, $id, $caption, $title, $align, $url, $size, $alt = '' ) {
		// Normally, media uploader return an HTML string (in this case, typically a complete image tag surrounded by a caption).
		// Don't change that; instead, send custom javascript variables back to opener.
		// Check that this is for the widget. Shouldn't hurt anything if it runs, but let's do it needlessly.
		if ( $this->is_sp_widget_context() ) {
			if ($alt=='') $alt = $title;
			?>
			<script type="text/javascript">
				// send image variables back to opener
				var win = window.dialogArguments || opener || parent || top;
				win.IW_html = '<?php echo addslashes($html) ?>';
				win.IW_img_id = '<?php echo $id ?>';
				win.IW_alt = '<?php echo addslashes($alt) ?>';
				win.IW_caption = '<?php echo addslashes($caption) ?>';
				win.IW_title = '<?php echo addslashes($title) ?>';
				win.IW_align = '<?php echo $align ?>';
				win.IW_url = '<?php echo $url ?>';
				win.IW_size = '<?php echo $size ?>';
			</script>
			<?php
		}
		return $html;
	}

	/**
	 * Remove from url tab until that functionality is added to widgets.
	 *
	 * @param array $tabs 
	 * @return void
	 * @author Shane & Peter, Inc. (Peter Chester)
	 */
	function media_upload_tabs($tabs) {
		if ( $this->is_sp_widget_context() ) {
			unset($tabs['type_url']);
		}
		return $tabs;
	}

	
	/**
	 * Widget frontend output
	 *
	 * @param array $args 
	 * @param array $instance 
	 * @return void
	 * @author Shane & Peter, Inc. (Peter Chester)
	 */
	function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );
		$title = apply_filters( 'widget_title', empty( $title ) ? '' : $title );
		
		include( $this->getTemplateHierarchy( 'widget' ) );
	}

	/**
	 * Update widget options
	 *
	 * @param object $new_instance Widget Instance
	 * @param object $old_instance Widget Instance 
	 * @return object
	 * @author Shane & Peter, Inc. (Peter Chester)
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( isset($new_instance['description']) ) {
			if ( current_user_can('unfiltered_html') ) {
				$instance['description'] = $new_instance['description'];
			} else {
				$instance['description'] = wp_filter_post_kses($new_instance['description']);
			}
		}
		$instance['link'] = $new_instance['link'];
		$instance['image'] = $new_instance['image'];
		$instance['imageurl'] = $this->get_image_url($new_instance['image'],$new_instance['width'],$new_instance['height']);  // image resizing not working right now
		if( $_SERVER["HTTPS"] == "on" ) {
			$instance['imageurl'] = str_replace('http://', 'https://', $instance['imageurl']);
		}
		$instance['linktarget'] = $new_instance['linktarget'];
		$instance['width'] = $new_instance['width'];
		$instance['height'] = $new_instance['height'];
		$instance['align'] = $new_instance['align'];
		$instance['alt'] = $new_instance['alt'];

		return $instance;
	}

	/**
	 * Form UI
	 *
	 * @param object $instance Widget Instance
	 * @return void
	 * @author Shane & Peter, Inc. (Peter Chester)
	 */
	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array( 
			'title' => '', 
			'description' => '', 
			'link' => '', 
			'linktarget' => '', 
			'width' => '', 
			'height' => '', 
			'image' => '',
			'imageurl' => '',
			'align' => '',
			'alt' => ''
		) );
		include( $this->getTemplateHierarchy( 'widget-admin' ) );
	}
	
	/**
	 * Admin header css
	 *
	 * @return void
	 * @author Shane & Peter, Inc. (Peter Chester)
	 */
	function admin_head() {
		?>
		<style type="text/css">
			.aligncenter {
				display: block;
				margin-left: auto;
				margin-right: auto;
			}
		</style>
		<?php
	}

	/**
	 * Loads theme files in appropriate hierarchy: 1) child theme, 
	 * 2) parent template, 3) plugin resources. will look in the image-widget/
	 * directory in a theme and the views/ directory in the plugin
	 *
	 * @param string $template template file to search for
	 * @return template path
	 * @author Shane & Peter, Inc. (Matt Wiebe)
	 **/

	function getTemplateHierarchy($template) {
		// whether or not .php was added
		$template_slug = rtrim($template, '.php');
		$template = $template_slug . '.php';
		
		if ( $theme_file = locate_template(array('image-widget/'.$template)) ) {
			$file = $theme_file;
		} else {
			$file = 'views/' . $template;
		}
		return apply_filters( 'sp_template_image-widget_'.$template, $file);
	}
}
?>