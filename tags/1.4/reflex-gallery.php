<?php
/**
 * @package ReFlex_Gallery
 * @version 1.4
 */
/*
Plugin Name: ReFlex Gallery
Plugin URI: http://wordpress-photo-gallery.com/
Description: Wordpress Plugin for creating responsive image galleries. By: HahnCreativeGroup
Author: HahnCreativeGroup
Version: 1.4
Author URI: http://labs.hahncreativegroup.com/
*/
if (!class_exists("ReFlex_Gallery")) {
	class ReFlex_Gallery {
		
		//Constructor
		public function __construct() {
			$this->plugin_name = plugin_basename(__FILE__);		
			$this->define_constants();
			$this->define_db_tables();			
			$this->add_gallery_options();
			$this->reflexdb = $this->create_db_conn();
			
			register_activation_hook( $this->plugin_name,  array(&$this, 'create_db_tables') );
			add_action('init', array($this, 'create_textdomain'));
			add_action('wp_enqueue_scripts', array($this, 'add_gallery_scripts'));
			
			add_action( 'admin_init', array($this,'gallery_admin_init') );
			add_action( 'admin_menu', array($this, 'add_gallery_admin_menu') );
			
			add_shortcode( 'ReflexGallery', array($this, 'gallery_shortcode_handler') );
		}
		
		//Define textdomain
		public function create_textdomain() {
			$plugin_dir = basename(dirname(__FILE__));
			load_plugin_textdomain( 'reflex-gallery', false, $plugin_dir.'/lib/languages' );
		}
		
		//Define constants
		public function define_constants() {
			if ( ! defined( 'RESPONSIVEFLEXIGALLERY_PLUGIN_BASENAME' ) )
				define( 'RESPONSIVEFLEXIGALLERY_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		
			if ( ! defined( 'RESPONSIVEFLEXIGALLERY_PLUGIN_NAME' ) )
				define( 'RESPONSIVEFLEXIGALLERY_PLUGIN_NAME', trim( dirname( RESPONSIVEFLEXIGALLERY_PLUGIN_BASENAME ), '/' ) );
			
			if ( ! defined( 'RESPONSIVEFLEXIGALLERY_PLUGIN_DIR' ) )
				define( 'RESPONSIVEFLEXIGALLERY_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . RESPONSIVEFLEXIGALLERY_PLUGIN_NAME );
		}
		
		//Define DB tables
		public function define_db_tables() {
			global $wpdb;
			
			$wpdb->reflexGalleries = $wpdb->prefix . 'reflex_gallery';
			$wpdb->reflexImages = $wpdb->prefix . 'reflex_gallery_images';
		}
		
		//Create DB tables
		public function create_db_tables() {
			include_once (dirname (__FILE__) . '/lib/install-db.php');
			
			install_db();
		}
		
		public function create_db_conn() {
			require('lib/db-class.php');
			$reflexdb = ReflexDB::getInstance();
			return $reflexdb;
		}
		
		//Add gallery options
		public function add_gallery_options() {
			$gallery_options = array(
				'thumbnail_width'  => 'auto',
				'thumbnail_height' => 'auto'																	 
			);
			
			add_option('reflex_gallery_options', array($this, $gallery_options));
		}
		
		//Add gallery scripts
		public function add_gallery_scripts() {
			wp_enqueue_script('jquery');
			wp_register_script('flexSlider', WP_PLUGIN_URL.'/reflex-gallery/scripts/flexslider/jquery.flexslider-min.js', array('jquery'));
			wp_register_script('prettyPhoto', WP_PLUGIN_URL.'/reflex-gallery/scripts/prettyphoto/jquery.prettyPhoto.js', array('jquery'));
			wp_register_script('galleryManager', WP_PLUGIN_URL.'/reflex-gallery/scripts/galleryManager.min.js', array('flexSlider', 'prettyPhoto', 'jquery'));
			wp_enqueue_script('flexSlider');
			wp_enqueue_script('prettyPhoto');
			wp_enqueue_script('galleryManager');
			wp_register_style('flexSlider_stylesheet', WP_PLUGIN_URL.'/reflex-gallery/scripts/flexslider/flexslider.css');
			wp_register_style('prettyPhoto_stylesheet', WP_PLUGIN_URL.'/reflex-gallery/scripts/prettyPhoto/prettyPhoto.css');
			wp_enqueue_style('flexSlider_stylesheet');
			wp_enqueue_style('prettyPhoto_stylesheet');
		}
				
		//Admin Section - register scripts and styles
		public function gallery_admin_init() {
			wp_register_style( 'table_pager_stylesheet', WP_PLUGIN_URL.'/reflex-gallery/admin/scripts/TablePagination/tablePager.css');
			wp_register_style( 'prettyPhoto_admin_stylesheet', WP_PLUGIN_URL.'/reflex-gallery/scripts/prettyphoto/prettyPhoto.css');
		}
		
		public function add_default_media_uploader() {
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			wp_register_script('easy-gallery-uploader', WP_PLUGIN_URL.'/reflex-gallery/admin/scripts/MediaUpload/image-uploader.js', array('jquery','media-upload','thickbox'));
			wp_enqueue_script('easy-gallery-uploader');
			wp_enqueue_style('thickbox');
		}
		
		public function reflex_gallery_admin_style_load() {
			wp_enqueue_style('table_pager_stylesheet');
		}
		
		public function reflex_gallery_admin_style_images() {
			wp_enqueue_style('table_pager_stylesheet');
			wp_enqueue_style('prettyPhoto_admin_stylesheet');
		}
		
		//Create Admin Menu
		public function add_gallery_admin_menu() {
			$overview = add_menu_page(__('ReFlex Gallery','reflex-gallery'), __('ReFlex Gallery','reflex-gallery'), 'manage_options', 'reflex-gallery-admin', array($this, 'add_overview'));
			$add_gallery = add_submenu_page('reflex-gallery-admin', __('ReFlex Gallery >> Add Gallery','reflex-gallery'), __('Add Gallery','reflex-gallery'), 'manage_options', 'add-gallery', array($this, 'add_gallery'));
			$edit_gallery = add_submenu_page('reflex-gallery-admin', __('ReFlex Gallery >> Edit Gallery','reflex-gallery'), __('Edit Gallery','reflex-gallery'), 'manage_options', 'edit-gallery', array($this, 'edit_gallery'));
			$add_images = add_submenu_page('reflex-gallery-admin', __('ReFlex Gallery >> Add Images','reflex-gallery'), __('Add Images','reflex-gallery'), 'manage_options', 'add-images', array($this, 'add_images'));	
			
			add_action('admin_print_styles-'.$overview, array($this, 'reflex_gallery_admin_style_load'));
			add_action('admin_print_styles-'.$edit_gallery, array($this, 'reflex_gallery_admin_style_load'));
			add_action('admin_print_styles-'.$add_images, array($this, 'reflex_gallery_admin_style_images'));
			add_action('admin_print_styles-'.$add_images, array($this, 'add_default_media_uploader'));
		}
		
		//Create Admin Pages
		public function add_overview() {			
			include("admin/overview.php");
		}
		
		public function add_gallery() {
			include("admin/add-gallery.php");	
		}
		public function edit_gallery() {
			include("admin/edit-gallery.php");	
		}
		
		public function add_images() {
			include("admin/add-images.php");
		}	
		
		//Create gallery
		public function create_gallery($galleryId) {
			require_once('lib/gallery-class.php');			
			global $responsiveGallery;
			
			if (class_exists('ResponsiveGallery')) {
				$responsiveGallery = new ResponsiveGallery($galleryId, $this->reflexdb);
				return $responsiveGallery->render();
			}
			else {
				return "Gallery not found.";	
			}	
		}
		
		//Create Short Code
		public function gallery_shortcode_handler($atts) {
			return $this->create_gallery($atts['id']);
		}
	}
}

if (class_exists("ReFlex_Gallery")) {
    global $ob_ReFlex_Gallery;
	$ob_ReFlex_Gallery = new ReFlex_Gallery();
}
?>