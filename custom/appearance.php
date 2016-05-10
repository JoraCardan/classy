<?php 

/**
 * Theme Apperance Class
 *
 * Manages JS & CSS enqueuing of the theme
 */
class ThemeAppearance {

	public function __construct() {

		add_action( 'wp_head', array( $this, 'head' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'wp_print_scripts', array($this, 'init_js_vars') );

		add_action( 'after_setup_theme', array($this, 'setup_theme') );

	}

	/**
	 * Enqueues styles
	 */
	public function enqueue_styles() {

		wp_register_style( 'flotheme_general_css', THEME_DIR . 'assets/css/general.css', array(), THEME_VERSION, 'all' );
		
		wp_enqueue_style( 'flotheme_general_css' );

	}

	/**
	 * Enqueues scripts
	 */
	public function enqueue_scripts() {

		wp_deregister_script( 'jquery' );
		
		wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js', array(), THEME_VERSION, true );

		if ( ThemeFramework::get_config_var('environment') == 'production' ) {
		
			wp_register_script( 'theme_plugins', THEME_DIR . 'assets/js/min/production.js', array( 'jquery' ), THEME_VERSION, true );
		
		} else {
			
			wp_register_script( 'theme_plugins', THEME_DIR . 'assets/js/plugins.js', array( 'jquery' ), THEME_VERSION, true );

			wp_register_script( 'theme_scripts', THEME_DIR . 'assets/js/scripts.js', array( 'jquery' ), THEME_VERSION, true );
		
		}

		wp_enqueue_script( 'theme_plugins' );
		
		wp_enqueue_script( 'theme_scripts' );

		wp_enqueue_script( 'theme_production' );

	}

	/**
	 * Load needed options & translations into template.
	 */
	public function init_js_vars() {
	
		$options = array(
			'base_url'          => home_url(''),
			'blog_url'          => home_url('archives/'),
			'template_dir'      => THEME_DIR,
			'ajax_load_url'     => site_url('/wp-admin/admin-ajax.php'),
			'is_mobile'         => (int) wp_is_mobile(),
		);

		wp_localize_script(
			'theme_plugins',
			'theme',
			$options
		);

	}

	/**
	 * Head Output
	 */
	public function head() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
		<link rel="shortcut icon" href="<?php echo THEME_DIR; ?>assets/favicon.ico" />
		<base href="/">
		<?php
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	public function setup_theme() {

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support('post-thumbnails');


		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(array(
			'primary' => __('Primary Menu', ThemeFramework::textdomain()),
		));


		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support('html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
		));

	}

}

new ThemeAppearance();