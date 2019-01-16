<?php
   /*
   Plugin Name: Awesomeness Blog Search
   // Plugin URI: http://wwww.abdelrauof.me
   description: create awesomeness search for blogs in the website
   Version: 0.1.0
   Author: Abdelrauof Mohammed
   Author URI: http://wwww.abdelrauof.me
   // License: GPL2
   */



class PageTemplater {

	/**
	 * A reference to an instance of this class.
	 */
	private static $instance;

	/**
	 * The array of templates that this plugin tracks.
	 */
	protected $templates;

	/**
	 * Returns an instance of this class.
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new PageTemplater();
		}

		return self::$instance;

	}

	/**
	 * Initializes the plugin by setting filters and administration functions.
	 */
	private function __construct() {

		$this->templates = array();


		// Add a filter to the attributes metabox to inject template into the cache.
		if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {

			// 4.6 and older
			add_filter(
				'page_attributes_dropdown_pages_args',
				array( $this, 'register_project_templates' )
			);

		} else {

			// Add a filter to the wp 4.7 version attributes metabox
			add_filter(
				'theme_page_templates', array( $this, 'add_new_template' )
			);

		}

		// Add a filter to the save post to inject out template into the page cache
		add_filter(
			'wp_insert_post_data',
			array( $this, 'register_project_templates' )
		);


		// Add a filter to the template include to determine if the page has our
		// template assigned and return it's path
		add_filter(
			'template_include',
			array( $this, 'view_project_template')
		);


		// Add your templates to this array.
		$this->templates = array(
			'goodtobebad-template.php' => 'It\'s Good to Be Bad',
		);

	}

	/**
	 * Adds our template to the page dropdown for v4.7+
	 *
	 */
	public function add_new_template( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, $this->templates );
		return $posts_templates;
	}

	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doens't really exist.
	 */
	public function register_project_templates( $atts ) {

		// Create the key used for the themes cache
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		// Retrieve the cache list.
		// If it doesn't exist, or it's empty prepare an array
		$templates = wp_get_theme()->get_page_templates();
		if ( empty( $templates ) ) {
			$templates = array();
		}

		// New cache, therefore remove the old one
		wp_cache_delete( $cache_key , 'themes');

		// Now add our template to the list of templates by merging our templates
		// with the existing templates array from the cache.
		$templates = array_merge( $templates, $this->templates );

		// Add the modified cache to allow WordPress to pick it up for listing
		// available templates
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $atts;

	}

	/**
	 * Checks if the template is assigned to the page
	 */
	public function view_project_template( $template ) {

		// Get global post
		global $post;

		// Return template if post is empty
		if ( ! $post ) {
			return $template;
		}

		// Return default template if we don't have a custom one defined
		if ( ! isset( $this->templates[get_post_meta(
			$post->ID, '_wp_page_template', true
		)] ) ) {
			return $template;
		}

		$file = plugin_dir_path( __FILE__ ). get_post_meta(
			$post->ID, '_wp_page_template', true
		);

		// Just to be safe, we check if the file exist first
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			echo $file;
		}

		// Return template
		return $template;

	}

}
add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );


/**
 * A function used to programmatically create a post in WordPress. The slug, author ID, and title
 * are defined within the context of the function.
 *
 * @returns -1 if the post was never created, -2 if a post with the same title exists, or the ID
 *          of the post if successful.
 */
function programmatically_create_post() {

 // Initialize the page ID to -1. This indicates no action has been taken.
 $post_id = -1;

 // Setup the author, slug, and title for the post
 $author_id = 1;
 $slug = 'search';
 $title = 'Blog Search';

 // If the page doesn't already exist, then create it
 if( null == get_page_by_title( $title ) ) {

   // Set the post ID so that we know the post was created successfully
   $post_id = wp_insert_post(
     array(
       'comment_status'	=>	'closed',
       'ping_status'		=>	'closed',
       'post_name'		=>	$slug,
       'post_title'		=>	$title,
       'post_status'		=>	'publish',
       'post_type'		=>	'page',
       'page_template'  => 'goodtobebad-template.php',
     )
   );

 // Otherwise, we'll stop
 } else {

       // Arbitrarily use -2 to indicate that the page with the title already exists
       $post_id = -2;

 } // end if

} // end programmatically_create_post
add_filter( 'after_setup_theme', 'programmatically_create_post' );


function geotags_add_rewrite_rules($wp_rewrite_rules) {
    global $wp_rewrite;

    $rule_key = '%search%';
    $url_pattern = '([^/]+)';
    $query_string = 'pagename=search&search=';

    $wp_rewrite->add_rewrite_tag($rule_key, $url_pattern, $query_string);

    $url_structure = $wp_rewrite->root . "blog/search/$rule_key/";
    $rewrite_rules = $wp_rewrite->generate_rewrite_rules($url_structure);

    $wp_rewrite_rules = $rewrite_rules + $wp_rewrite_rules;
    return $wp_rewrite_rules;
}

add_filter('rewrite_rules_array', 'geotags_add_rewrite_rules');

function geotags_add_query_var($query_vars) {
    array_push($query_vars, 'search');
    return $query_vars;
}

add_filter('query_vars','geotags_add_query_var');

?>
