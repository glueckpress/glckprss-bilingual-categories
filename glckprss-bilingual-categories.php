<?php
/**
 * Plugin Name: The Bilingual Categories Hack
 * Plugin URI: https://github.com/glueckpress/glckprss-bilingual-categories
 * Description: Relabels default categories as “languages” and makes them available on pages. Reloads default textdomain in the front-end when current category is “de”.
 * Author: Caspar Hübinger
 * Version: 2014.01
 * Author URI: http://glueckpress.com
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) OR exit;

class GlckPress_Label_Categories {


	/**
	 * Initiantion.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'category_labels' ) );
		add_action( 'init', array( $this, 'category_post_types' ) );
		add_action( 'admin_init', array( $this, 'edit_screen' ) );
		add_filter( 'template_redirect', array( $this, 'reload_textdomain' ) );
	}


	/**
	 * Init function.
	 *
	 * @access public
	 * @return void
	 */
	public function edit_screen() {

		add_filter( 'manage_posts_columns' , array( $this, 'manage_columns' ) );
		add_filter( 'gettext', array( $this, 'dropdown_label' ), 10, 3 );
	}



	/**
	 * Relabel categories.
	 *
	 * @access public
	 * @return void
	 */
	public function category_labels() {

		global $wp_taxonomies;

		$wp_taxonomies['category']->labels = ( object ) array(
			'name' => 'Language',
			'menu_name' => 'Languages',
			'singular_name' => 'Language',
			'search_items' => 'Search Languages',
			'popular_items' => 'Popular Languages',
			'all_items' => 'All Languages',
			'parent_item' => 'Parent Language',
			'parent_item_colon' => 'Parent Language:',
			'view_item' =>  'View Language',
			'edit_item' => 'Edit Language',
			'update_item' => 'Update Language',
			'add_new_item' => 'Add New Language',
			'new_item_name' => 'New Language Name',
			'separate_items_with_commas' => 'Separate language with commas',
			'add_or_remove_items' => 'Add or remove languages',
			'choose_from_most_used' => 'Choose from the most used languages',
		);

		$wp_taxonomies['category']->label = 'Languages';
	}



	/**
	 * Enable categories for posts and pages.
	 *
	 * @access public
	 * @return void
	 */
	public function category_post_types() {

		global $wp_taxonomies;

		$wp_taxonomies['category']->object_type = array( 'post', 'page' );
	}


	/**
	 * Relabel admin columns.
	 *
	 * @access public
	 * @param mixed $columns
	 * @return string
	 */
	public function manage_columns( $columns ) {

		$columns['categories'] = 'Languages';

		return $columns;
	}



	/**
	 * Dropdown label.
	 *
	 * @access public
	 * @param mixed $translated
	 * @param mixed $original
	 * @param mixed $domain
	 * @return string
	 */
	public function dropdown_label( $translated, $original, $domain ) {

		global $pagenow;

		if( 'edit.php' !== $pagenow )
			return $translated;

		$strings = array( 'View all categories' => 'View all languages' );

		if( isset( $strings[ $original ] ) ) {

			$translations = &get_translations_for_domain( $domain );
			$translated = $translations->translate( $strings[ $original ] );
		}

		return $translated;
	}


	/**
	 * Set locale according to category.
	 *
	 * @access public
	 * @param mixed $locale
	 * @return string
	 */
	public function set_locale( $locale ) {

		global $wp_query;

		$query_vars = $wp_query->query_vars;

		if( is_admin() )
			return $locale;

		if( ( $wp_query->is_category && isset( $query_vars['category_name'] ) && 'de' == $query_vars['category_name'] ) || ( is_singular() && in_category( 'de' ) ) )
			$locale = 'de_DE';

		return $locale;
	}


	/**
	 * Relaod the default texdomain in the front-end.
	 *
	 * @access public
	 * @return void
	 */
	public function reload_textdomain() {

		add_filter( 'locale', array( $this, 'set_locale' ) );

		if( ! is_admin() )
			load_default_textdomain();
	}

}


/* Load plugin. */
function glckprss_categories_to_languages() {

	new GlckPress_Label_Categories;
}
add_filter( 'plugins_loaded', 'glckprss_categories_to_languages' );